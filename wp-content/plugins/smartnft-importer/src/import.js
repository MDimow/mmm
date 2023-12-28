import React, { useContext, useState } from "react";
import { SNFT_IMPORTER_APP_CONTEX } from "./state";
import storeImportedNft from "./store-imported-nft";
import { Button, notification } from "antd";
import { WarningOutlined } from "@ant-design/icons";
import { escapeHTML } from "@wordpress/escape-html";
const SLUG = "smartnft_importer";
const { __ } = wp.i18n;

const ConfirmAndImport = () => {
  const { state, dispatch, web3Provider } = useContext(
    SNFT_IMPORTER_APP_CONTEX
  );

  if (!state.selectedNfts.length) return <NothingFound />;

  return (
    <div>
      <div className="selected-nfts-list">
        {state.selectedNfts.map((cur) => (
          <Nft nft={cur.nft} key={cur.id} dispatch={dispatch} id={cur.id} />
        ))}
      </div>
      <br />
      <br />
      <p>Category: {state.category}</p>
      <p>Collection: {state.collection}</p>
      <PrevStep dispatch={dispatch} />
      <ImportButton />
    </div>
  );
};
const PrevStep = ({ dispatch, state, step }) => {
  const prevStep = () => {
    dispatch({ type: "CHANGE_STEP", payload: 4 });
  };

  return (
    <Button
      style={{ marginRight: 10 }}
      className="btn-normal next-btn"
      onClick={prevStep}
    >
      Previous
    </Button>
  );
};

const ImportButton = () => {
  const { state, dispatch, web3Provider } = useContext(
    SNFT_IMPORTER_APP_CONTEX
  );
  const [loading, setLoading] = useState(false);

  const saveInDb = async () => {
    setLoading(true);
    try {
      await Promise.all(
        state.selectedNfts.map((cur) => storeImportedNft(cur.nft))
      );

      notification.open({
        message: "Success",
        description: "The selected NFTs has been imported successfully",
        icon: <WarningOutlined style={{ color: "#108ee9" }} />,
      });

      dispatch({ type: "CHANGE_STEP", payload: 6 });
      setLoading(false);
    } catch (err) {
      console.log(err);
      notification.error({
        message: "Somethin is wrong!",
        description: "Import fail for unknown reason.",
        icon: <WarningOutlined style={{ color: "#108ee9" }} />,
      });
      setLoading(false);
    }
  };

  if (!state.selectedNfts.length) return null;

  return (
    <Button loading={loading} type="primary" onClick={saveInDb}>
      {escapeHTML(__("Start importting", SLUG))}
    </Button>
  );
};

const Nft = ({ nft, dispatch, id }) => {
  const remove = (id) => {
    return dispatch({
      type: "REMOVE_SELECTED_NFTS",
      payload: id,
    });
  };

  return (
    <figure onClick={() => remove(id)} className="selected">
      <img src={nft.mediaUrl} alt={nft.name} />
      <p>{nft.name}</p>
    </figure>
  );
};

const NothingFound = () => {
  return <p>nothing found</p>;
};

export default ConfirmAndImport;
