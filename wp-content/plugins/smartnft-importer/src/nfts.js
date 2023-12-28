import React, { useContext, useEffect, useState } from "react";
import { SNFT_IMPORTER_APP_CONTEX } from "./state";
import { useMoralisProvider } from "./moralis/api";
import { directFetchTokenFromNetwork } from "./direct-contract-fetch";
import { CheckCircleFilled, WarningOutlined } from "@ant-design/icons";
import { escapeHTML } from "@wordpress/escape-html";
import { Button } from "antd";
import { notification } from "antd";
import NftsLoader from "./loaders/nfts-loader";

const Nfts = () => {
  const { state, dispatch, web3Provider } = useContext(
    SNFT_IMPORTER_APP_CONTEX
  );

  const moralisProvider = useMoralisProvider();
  console.log(state);

  async function fetchNfts() {
    try {
      const options = {
        address: state.address,
        chain: state.network?.chain,
        limit: state.limit,
        cursor: state.cursor,
        owner: state.address,
      };
      let res;

      if (state.nftFrom == "wallet") {
        res = await moralisProvider.fetchNftByWallet({
          ...options,
          contract: state.contract,
        });
      }

      if (state.nftFrom == "contract") {
        res = await moralisProvider.fetchNftByContract({ ...options });
      }

      if (state.nftFrom == "directContract") {
        return await directFetchTokenFromNetwork({
          state,
          dispatch,
          web3Provider,
        });
      }

      dispatch({
        type: "CHANGE_NFTS",
        payload: [
          ...state.nfts,
          ...res.formatedNfts.map((cur) => ({
            id: Date.now() + Math.round(Math.random() * 1000),
            nft: cur,
          })),
        ],
      });

      dispatch({ type: "CHANGE_CURSOR", payload: res.cursor });
      dispatch({ type: "CHANGE_LOADING", payload: false });
    } catch (err) {
      console.log(err);
    }
  }

  useEffect(() => {
    if (!state.isDirectNetworkFetch) {
      fetchNfts().catch((err) => {
        console.log(err);
        return notification.error({
          message: escapeHTML("Fetching error"),
          description: escapeHTML(
            "Got error while fetching nft! Give valid info and make sure your Contract implemented standard functionality."
          ),
          icon: <WarningOutlined style={{ color: "#108ee9" }} />,
        });
      });
    }
  }, []);

  if (state.loading) {
    return <NftsLoader />;
  }

  return (
    <div>
      <div className="imported-nfts-list">
        {state.nfts.map((cur, i) => (
          <Nft
            key={i}
            nft={cur.nft}
            id={cur.id}
            state={state}
            dispatch={dispatch}
          />
        ))}
      </div>
      <div
        style={{
          marginTop: 20,
          display: "flex",
          justifyContent: "space-between",
        }}
      >
        <NextBtn dispatch={dispatch} state={state} fetchNfts={fetchNfts} />
        <div>
          <PrevStep dispatch={dispatch} state={state} />
          <NextStep dispatch={dispatch} state={state} />
        </div>
      </div>
    </div>
  );
};

const NextStep = ({ dispatch, state, step }) => {
  const nextStep = () => {
    dispatch({ type: "CHANGE_STEP", payload: 4 });
  };

  return (
    <Button
      type="primary"
      className="btn-normal next-btn"
      disabled={!state.selectedNfts.length}
      onClick={nextStep}
    >
      {escapeHTML("Continue")}
    </Button>
  );
};

const PrevStep = ({ dispatch, state, step }) => {
  const prevStep = () => {
    dispatch({ type: "CHANGE_STEP", payload: 2 });
  };

  return (
    <Button
      style={{ marginRight: 10, marginLeft: 10 }}
      className="btn-normal next-btn"
      onClick={prevStep}
    >
      {escapeHTML("Previous")}
    </Button>
  );
};

const NextBtn = ({ fetchNfts, state, dispatch }) => {
  const totalPage = Math.round(state.totalNftFound / state.limit);
  if (state.isDirectNetworkFetch) {
    if (state.directNftPage >= totalPage) return null;
  } else {
    if (!state.cursor) return null;
  }

  return (
    <Button
      className="btn-normal next-btn"
      onClick={() => {
        fetchNfts();
        dispatch({ type: "CHANGE_LOADING", payload: true });
      }}
    >
      {escapeHTML("Next Page")}
    </Button>
  );
};

const Nft = ({ nft, state, dispatch, id }) => {
  const [selected, setSelected] = useState(false);

  const onClick = (id) => {
    const exist = state.selectedNfts.find((cur) => cur.id == id);
    if (!exist) {
      return dispatch({
        type: "ADD_SELECTED_NFTS",
        payload: { id, nft },
      });
    }

    return dispatch({
      type: "REMOVE_SELECTED_NFTS",
      payload: id,
    });
  };

  return (
    <figure
      onClick={() => {
        onClick(id);
        setSelected(!selected);
      }}
      className={selected ? "Selected" : "not-selected"}
    >
      <img src={nft.mediaUrl} alt={nft.name} />
      {selected && (
        <span className="checked">
          <CheckCircleFilled />
        </span>
      )}
      <p>{nft.name}</p>
    </figure>
  );
};

export default Nfts;
