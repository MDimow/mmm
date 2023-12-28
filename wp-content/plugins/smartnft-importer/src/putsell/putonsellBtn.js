import { parseEther } from "ethers/lib/utils";
import React, { useContext, useState } from "react";
import { FORM_CONTEX } from "./state";
import { Button, notification } from "antd";
import { WarningOutlined } from "@ant-design/icons";
import { escapeHTML } from "@wordpress/escape-html";

const { __ } = wp.i18n;
const SLUG = "smartnft_importer";
const ESCROW_CONTRACT_ADDRESS = importer_local?.ESCROW_CONTRACT?.address;

const updateNftData = async ({
  postId,
  price,
  ethPrice,
  hasSplitPayment,
  splitPaymentsAccounts,
}) => {
  const res = await jQuery.ajax({
    type: "post",
    url: importer_local.BACKEND_AJAX_URL,
    data: {
      nft_id: postId,
      update_type: "imported_put_on_sell",
      price,
      ethPrice,
      has_split_payment: hasSplitPayment,
      split_payments: splitPaymentsAccounts,
      action: "update_local_nft",
    },
  });
  console.log(res);
};

const updateTitle = async ({ postId, title }) => {
  if (!postId || !title) return null;

  const res = await jQuery.ajax({
    type: "post",
    url: importer_local.BACKEND_AJAX_URL,
    data: {
      id: postId,
      title,
      action: "smartnft_importer_update_post_title",
    },
  });
  console.log(res);
};

const PutonSellButton = ({ setLoading, setShowForm }) => {
  const { state, dispatch, web3Provider } = useContext(FORM_CONTEX);

  const putonsell = async () => {
    const magicContract = web3Provider.magicContract(
      state.nft.imported_from_contract,
      web3Provider.signer
    );

    try {
      console.log("starting add to escrow...");
      //check if caller is the owner of the token
      const owner = await magicContract.ownerOf(parseInt(state.nft.tokenId));

      if (owner.toLowerCase() !== web3Provider.account[0].toLowerCase()) {
        notification.error({
          message: escapeHTML("Owner Error"),
          description: escapeHTML("You are not the owner of this NFT."),
          icon: <WarningOutlined style={{ color: "#108ee9" }} />,
        });
        throw new Error(
          escapeHTML(__("You are not the owner of the token.", SLUG))
        );
      }

      //check if escrow contract have approve to transfer this token
      const isApprovedForAll = await magicContract.isApprovedForAll(
        web3Provider.account[0],
        ESCROW_CONTRACT_ADDRESS
      );
      console.log("isApprovedForAll--------->", isApprovedForAll);

      if (isApprovedForAll !== true) {
        await magicContract.setApprovalForAll(ESCROW_CONTRACT_ADDRESS, true);
      }

      //correct user called the function now add this token in escrow
      const tx = await web3Provider.contract.add_token_to_escrow(
        parseInt(state.nft.tokenId),
        state.nft.imported_from_contract,
        parseEther(state.price).toString(),
        state.hasSplitPayment,
        state.splitPaymentsAccounts.map(
          (cur) => parseInt(cur.percentage) * 100
        ),
        state.splitPaymentsAccounts.map((cur) => cur.address)
      );

      //wait for confirmation
      await tx.wait();

      //update local db
      await updateNftData({
        postId: state.nft.post_id,
        price: parseEther(state.price).toString(),
        ethPrice: state.price,
        hasSplitPayment: state.hasSplitPayment,
        splitPaymentsAccounts: state.splitPaymentsAccounts,
      });

      //update title if new title is set
      if (state.name) {
        await updateTitle({ postId: state.nft.post_id, title: state.name });
      }

      window.setTimeout(() => {
        window.location.reload();
      }, 1000);
      setLoading(false);
      setShowForm(false);

      console.log("finished add to escrow...");
    } catch (err) {
      console.log(err);
      notification.error({
        message: "Puton sell Error",
        description: escapeHTML(
          "Puton sale fail for unknown reason. Make sure your token original contract has standard functionality."
        ),
        icon: <WarningOutlined style={{ color: "#108ee9" }} />,
      });
      throw new Error(
        escapeHTML(
          __(
            "Puton sale fail for unknown reason. Make sure your token original contract has standard functionality.",
            SLUG
          )
        )
      );
    }
  };

  if (!state.price || !state.nft) return null;

  return (
    <>
      <Button
        style={{ marginTop: 20 }}
        type="primary"
        block
        onClick={() => {
          putonsell();
          setLoading(true);
        }}
      >
        {escapeHTML(__("Put on sell", SLUG))}
      </Button>
    </>
  );
};

export default PutonSellButton;
