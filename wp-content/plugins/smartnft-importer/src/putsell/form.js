import React, { useEffect, useReducer, useState } from "react";
import PriceField from "./price";
import NameField from "./name";
import SplitPaymentComponent from "./split-payment";
import PutonSellButton from "./putonsellBtn";
import useWeb3provider from "../web3";
import { INISIAL_STATE, REDUCER, FORM_CONTEX } from "./state";
import { Modal, Spin } from "antd";
import { escapeHTML } from "@wordpress/escape-html";
const SNFT_IMPORTER_MEDIA_URL = importer_local?.SNFT_IMPORTER_MEDIA_URL;
const { __ } = wp.i18n;
const SLUG = "smartnft_importer";

const PutonSellForm = ({ showForm, setShowForm, nft }) => {
  const [state, dispatch] = useReducer(REDUCER, INISIAL_STATE);
  const web3Provider = useWeb3provider();
  const [loading, setLoading] = useState(false);

  if (!nft) return null;

  useEffect(() => {
    function setNft() {
      if (!nft) return;
      dispatch({ type: "SET_NFT", payload: nft });
    }
    setNft();
  }, []);

  const handleCancel = () => {
    setShowForm(false);
  };
  return (
    <FORM_CONTEX.Provider value={{ state, dispatch, web3Provider }}>
      <Modal
        title="Put on sale"
        open={showForm}
        footer={null}
        onCancel={handleCancel}
      >
        {loading && (
          <div style={{ textAlign: "center" }}>
            <Spin
              wrapperClassName="align-center"
              spinning={loading}
              tip={escapeHTML(__("Please wait...", SLUG))}
            ></Spin>
          </div>
        )}
        {!loading && (
          <>
            <PriceField />
            <NameField />
            <SplitPaymentComponent />
            <PutonSellButton
              setLoading={setLoading}
              setShowForm={setShowForm}
            />
          </>
        )}
      </Modal>
    </FORM_CONTEX.Provider>
  );
};

export default PutonSellForm;
