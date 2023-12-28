import React, { useContext } from "react";
import { escapeHTML } from "@wordpress/escape-html";
import { SNFT_IMPORTER_APP_CONTEX } from "./state";
import { Alert } from "antd";

const { __ } = wp.i18n;
const SLUG = "smartnft_importer";
const ESCROW_CONTRACT = importer_local?.ESCROW_CONTRACT;

export const WrongNetworkError = () => {
  const { state, dispatch, web3Provider } = useContext(
    SNFT_IMPORTER_APP_CONTEX
  );

  if (
    ESCROW_CONTRACT?.chain_id !== web3Provider?.network?.chainId?.toString() ||
    state?.network?.id?.toString() !== ESCROW_CONTRACT?.chain_id
  ) {
    return (
      <div style={{ marginBottom: "30px" }}>
        <Alert
          message="Warning"
          description={escapeHTML(
            __(
              `Your current Escrow Contracts is deployed in ${ESCROW_CONTRACT?.network} network. But your wallet is not connected to the same network or you are trying to import NFTS from  different network. Remember if your NFTS are not in the same network as your Escrow Contract or your SMARTNFT Contract, You can't put them for sell in the market.`,
              SLUG
            )
          )}
          type="warning"
          showIcon
          closable
        />
      </div>
    );
  }

  if (!ESCROW_CONTRACT.address) {
    return (
      <div style={{ marginBottom: "30px" }}>
        <Alert
          message="Warning"
          description={escapeHTML(
            __(
              "You don't have Deployed ESCROW Contract. Deploy your ESCROW Contract first. Otherwise you can't be able to list your NFTS on market.",
              SLUG
            )
          )}
          type="warning"
          showIcon
          closable
        />
      </div>
    );
  }

  return null;
};
