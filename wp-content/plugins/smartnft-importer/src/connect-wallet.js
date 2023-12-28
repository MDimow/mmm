import React, { useContext, useEffect } from "react";
import { SNFT_IMPORTER_APP_CONTEX } from "./state";
import { escapeHTML } from "@wordpress/escape-html";
import { notification } from "antd";
import { WarningOutlined } from "@ant-design/icons";
import { Button } from "antd";
const SLUG = "smartnft_importer";
const { __ } = wp.i18n;

export const MetamaskNotConnectedError = () => {
  const { state, dispatch, web3Provider } = useContext(
    SNFT_IMPORTER_APP_CONTEX
  );

  useEffect(() => {
    if (web3Provider.account[0]) {
      dispatch({ type: "CHANGE_ADDRESS", payload: web3Provider.account[0] });
    }
  }, [web3Provider.account]);

  const nextStepAllowd = () => {
    if (state.network && state.address) return false;
    return true;
  };

  const connect = async () => {
    if (web3Provider.account[0]) return null;
    if (!web3Provider.isMetamaskInstall) {
      return notification.error({
        message: escapeHTML("Metamask error"),
        description: escapeHTML(
          "Install metamask. Metamask extension not found."
        ),
        icon: <WarningOutlined style={{ color: "#108ee9" }} />,
      });
    }

    try {
      await web3Provider.connect();
      dispatch({ type: "CHANGE_ADDRESS", payload: web3Provider.account[0] });
    } catch (err) {
      console.log(err);
    }
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
    dispatch({ type: "CHANGE_STEP", payload: 2 });
  };

  return (
    <div className="step-one">
      <p className="normal bold">
        {escapeHTML("Drop in your wallet details.")}
      </p>
      <p className="normal mb-big">
        {escapeHTML("Connect your MetaMask wallet!")}
      </p>

      <p className="normal bold mb-small">{escapeHTML("Select your chain.")}</p>
      <div className="mb-big">
        <MoralisNetworks />
      </div>

      <p className="normal bold mb-small">{escapeHTML("Wallet address")}</p>
      <div className="wallet-btn-con mb-big">
        <input
          type="text"
          value={web3Provider.account[0]}
          className="input-text"
          placeholder={escapeHTML("Enter Your Wallet Address")}
          readOnly={true}
        />
        <button className="btn-normal" onClick={connect}>
          {escapeHTML(web3Provider.account[0] ? "Connected" : "Connect")}
        </button>
      </div>
      <div className="flex right-align">
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

const MoralisNetworks = () => {
  const { state, dispatch, web3Provider } = useContext(
    SNFT_IMPORTER_APP_CONTEX
  );

  const onChange = (e) => {
    const network = state.networks.find((cur) => cur.id == e.target.value);
    if (!network) throw new Error("Network not found!!");

    if (network.id == 10001) {
      dispatch({ type: "DIRECT_NETWORK_FETCH", payload: true });
      dispatch({ type: "CHANGE_NFT_FROM", payload: "directContract" });
    }

    dispatch({ type: "CHANGE_NETWORK", payload: network });
  };

  return (
    <select
      id="network"
      onChange={(e) => {
        onChange(e);
      }}
    >
      <option value=""></option>
      {state.networks.map((cur) => (
        <option key={cur.id} value={cur.id}>
          {cur.name}
        </option>
      ))}
    </select>
  );
};
