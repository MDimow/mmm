import React, { useState } from "react";
import useWeb3provider from "./web3";
import contractJson from "../contracts/smartnft-importer-escrow";
import { Button, Alert, message } from "antd";
import { escapeHTML } from "@wordpress/escape-html";

const ESCROW_CONTRACT = importer_local?.ESCROW_CONTRACT;
const { __ } = wp.i18n;
const SLUG = "smartnft_importer";

const saveDeployedEscrowContractInfo = async ({
  address,
  network,
  chainId,
}) => {
  const res = await jQuery.ajax({
    type: "post",
    url: importer_local.BACKEND_AJAX_URL,
    data: {
      contract_address: address,
      contract_network: network,
      chain_id: chainId,
      action: "smartnft_importer_store_escrow_contract_info",
    },
  });

  console.log(res);
};

const DeployEscrow = () => {
  const web3Provider = useWeb3provider();
  const [loading, setLoading] = useState(false);
  if (web3Provider.loading) return null;
  const key = "updatable";

  const deploy = async () => {
    try {
      setLoading(true);
      message.loading({ content: "Deploying Escrow Contract...", key });

      const res = await web3Provider.deployContract({
        solidityCompiledJsonObj: contractJson,
        signer: web3Provider.signer,
      });

      await saveDeployedEscrowContractInfo({
        address: res.toLowerCase(),
        network: web3Provider.network.name,
        chainId: web3Provider.network.chainId,
      });

      message.success({
        content: "Contract deployed successfully!",
        key,
        duration: 2,
      });

      setLoading(false);
      setTimeout(() => {
        window.location.reload();
      }, 1000);
    } catch (err) {
      console.log(err);
    }
  };

  return (
    <div>
      <Alert
        message="Warning"
        description="You need to deplpoy escrow contract on the network same as the current contract deployed from SmartNFT. Please go to Metamask and choose the correct network"
        type="warning"
        showIcon
        closable
      />

      <p className="deployed-contract">
        {escapeHTML(__("Your deployed Escrow Contract address:", SLUG))}
        <span>{ESCROW_CONTRACT?.address}</span>
      </p>
      <p className="deployed-contract">
        {escapeHTML(__("Your deployed Escrow Contract Network:", SLUG))}
        <span>{ESCROW_CONTRACT?.network}</span>
      </p>

      <Button
        loading={loading}
        type="primary"
        size="large"
        style={{ marginTop: 20 }}
        onClick={deploy}
      >
        {escapeHTML(__("Deploy escrow contract", SLUG))}
      </Button>
    </div>
  );
};

export default DeployEscrow;
