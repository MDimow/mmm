import React, { useContext, useEffect } from "react";
import { SNFT_IMPORTER_APP_CONTEX } from "./state";
import { DirectContractFetch } from "./direct-contract-fetch";
import { escapeHTML } from "@wordpress/escape-html";
import { Button } from "antd";
import { notification } from "antd";
import { WarningOutlined } from "@ant-design/icons";
import { WrongNetworkError } from "./common";

const SLUG = "smartnft_importer";
const { __ } = wp.i18n;

const SelectWallet = () => {
  const { state, dispatch, web3Provider } = useContext(
    SNFT_IMPORTER_APP_CONTEX
  );

  useEffect(() => {
    dispatch({ type: "CHANGE_ADDRESS", payload: web3Provider.account[0] });
  }, [web3Provider.account[0]]);

  const handleWalletTypeChange = (e) => {
    dispatch({ type: "CHANGE_NFT_FROM", payload: e.target.value });
  };

  const nextStepAllowd = () => {
    if (state.nftFrom === "wallet") {
      return state.address ? false : true;
    }
    if (state.nftFrom === "contract") {
      return state.contract ? false : true;
    }

    return true;
  };

  const handleAddressChange = (e) => {
    const value = e.target.value;
    if (state.nftFrom == "wallet") {
      return dispatch({ type: "CHANGE_ADDRESS", payload: value });
    }
    if (state.nftFrom == "contract") {
      return dispatch({ type: "CHANGE_CONTRACT", payload: value });
    }
  };

  const handleLimitChange = (e) => {
    const value = parseInt(e.target.value);
    return dispatch({ type: "CHANGE_LIMIT", payload: value });
  };

  const nextStep = () => {
    if (nextStepAllowd()) {
      notification.error({
        message: escapeHTML("Error"),
        description: escapeHTML("Fillup the info properly."),
        icon: <WarningOutlined style={{ color: "#108ee9" }} />,
      });
      throw new Error(escapeHTML("Fillup the info properly."));
    }
    return dispatch({ type: "CHANGE_STEP", payload: 3 });
  };

  const prevStep = () => {
    return dispatch({ type: "CHANGE_STEP", payload: 1 });
  };

  const value = () => {
    if (state.nftFrom == "wallet") return state.address;
    if (state.nftFrom == "contract") return state.contract;
  };

  if (state.isDirectNetworkFetch) return <DirectContractFetch />;

  return (
    <div className="step-two">
      <div className="wrong-network-warning">
        <WrongNetworkError />
      </div>
      <p className="normal bold">{escapeHTML("Select import sources")}</p>
      <p className="normal mb-big">
        {escapeHTML(
          "Choose your import source details from which NFTs will be imported."
        )}
      </p>

      <div className="flex mb-big limit-type-con">
        <div>
          <p className="normal bold">{escapeHTML("Select type")}</p>
          <p className="normal mb-small">
            {escapeHTML("The maximum assets you want to import.")}
          </p>
          <select id="wallet-type" onChange={handleWalletTypeChange}>
            <option value="wallet">{__("Wallet", SLUG)}</option>
            <option value="contract">{__("Contract", SLUG)}</option>
          </select>
        </div>
        <div>
          <p className="normal bold">{escapeHTML("Limit")}</p>
          <p className="normal mb-small">
            {escapeHTML("The maximum assets you want to import.")}
          </p>
          <div>
            <input
              type="number"
              value={state.limit}
              onChange={(e) => handleLimitChange(e)}
              placeholder={escapeHTML(__("limit", SLUG))}
              className="input-text"
            />
          </div>
        </div>
      </div>
      <div className="mb-big">
        <p className="normal bold">{escapeHTML("Address")}</p>
        <p className="normal mb-small">
          {escapeHTML("The maximum assets you want to import.")}
        </p>
        <input
          type="text"
          value={value()}
          onChange={(e) => handleAddressChange(e)}
          placeholder={escapeHTML(__("Address", SLUG))}
          className="input-text"
        />
      </div>
      <div className="flex right-align">
        <Button
          style={{ marginRight: 10 }}
          className="btn-normal next-btn"
          onClick={prevStep}
        >
          {escapeHTML("Previous")}
        </Button>
        <Button
          type="primary"
          className="btn-normal next-btn"
          onClick={nextStep}
          disabled={nextStepAllowd()}
        >
          {escapeHTML("Continue")}
        </Button>
      </div>
    </div>
  );
};

export default SelectWallet;
