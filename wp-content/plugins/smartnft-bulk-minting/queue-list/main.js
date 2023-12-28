import React, { useState, useEffect, useContext } from "react";
import {
  SLUG,
  BACKEND_AJAX_URL,
  FRONTENDMEDIAURL,
  BACKENDMEDIAURL,
} from "../../../../../common/store";
import { Popup } from "../common/popup";
const { __ } = wp.i18n;
import { bulk_mint } from "./bulk-mint";
import { CreateNftContext } from "../form/state";
import { errorMessage } from "../../../../../common/component/message/error";

const CollNameAndSymbol = ({ name, setName, symbol, setSymbol }) => {
  return (
    <>
      <p className="form-wallet__title header-two">Create collection</p>
      <div className="name-symbol">
        <input
          type="text"
          onChange={(e) => setName(e.target.value)}
          placeholder={__("Name", SLUG)}
          value={name}
        />
        <input
          type="text"
          onChange={(e) => setSymbol(e.target.value)}
          placeholder={__("Symbol", SLUG)}
          value={symbol}
        />
      </div>
    </>
  );
};

export const QueueList = ({ web3Provider }) => {
  const { state, dispatch } = useContext(CreateNftContext);
  const [queue, setQueue] = useState({});
  const [name, setName] = useState("");
  const [symbol, setSymbol] = useState("");

  async function fetchQueue() {
    try {
      const res = await jQuery.ajax({
        type: "post",
        url: BACKEND_AJAX_URL,
        data: {
          action: "nbm_get_queue",
          account: web3Provider.account[0],
          chainId: web3Provider.network.chainId,
          standard: state.standard,
        },
      });

      setQueue(res.data);
      console.log("queued item: ", res.data);
    } catch (err) {
      console.error(err);
    }
  }

  const keys = Object.keys(queue);

  useEffect(() => {
    if (web3Provider?.account[0]) fetchQueue();
  }, [web3Provider?.account[0]]);

  return (
    <div>
      <CollNameAndSymbol
        name={name}
        symbol={symbol}
        setName={setName}
        setSymbol={setSymbol}
      />
      {keys.length ? (
        <p className="form-wallet__title header-two">Queued NFTs</p>
      ) : null}
      <div className="queue-list">
        {keys.map((cur, i) => (
          <QueueItem
            _data={{ data: queue[cur], id: cur }}
            key={cur}
            number={i + 1}
            setQueue={setQueue}
            web3Provider={web3Provider}
            standard={state.standard}
          />
        ))}
      </div>
      {keys.length ? (
        <BulkMintBtn
          web3Provider={web3Provider}
          queue={queue}
          name={name}
          symbol={symbol}
          standard={state.standard}
        />
      ) : null}
    </div>
  );
};

const QueueItem = ({ _data, number, setQueue, web3Provider, standard }) => {
  const { data, id } = _data;

  const delete_queued_item = async () => {
    try {
      const res = await jQuery.ajax({
        type: "post",
        url: BACKEND_AJAX_URL,
        data: {
          action: "nbm_delete_queue",
          account: web3Provider.account[0],
          chainId: web3Provider.network.chainId,
          id,
          standard,
        },
      });

      setQueue(res.data);
    } catch (err) {
      console.error(err);
    }
  };

  return (
    <div className="queue-item">
      <span>{number}. </span>
      <span>{data?.meta?.name}</span>
      <img className="m-img" src={data.meta?.image} alt={data?.meta?.name} />
      <img
        className="m-svg"
        onClick={delete_queued_item}
        src={`${FRONTENDMEDIAURL}cross.svg`}
        alt="cross"
      />
    </div>
  );
};

const BulkMintBtn = ({ web3Provider, queue, name, symbol, standard }) => {
  const [open, setOpen] = useState(false);
  const [success, setSuccess] = useState(false);
  const [redirect, setRedirect] = useState(null);

  const mint = async () => {
    try {
      setOpen(true);
      const coll = await bulk_mint({
        web3Provider,
        queue,
        name,
        symbol,
        standard,
      });
      setSuccess(true);
      setRedirect(coll?.data?.col_link);
    } catch (err) {
      console.error(err);
      setOpen(false);
      errorMessage("Operation fail!");
    }
  };

  return (
    <>
      <button onClick={mint}>{__("Start bulk minting", SLUG)}</button>
      {open && !success ? (
        <Popup>
          <img
            className="rotating"
            src={`${BACKENDMEDIAURL}/loaders/loading.svg`}
          />
          <h3>{__("Wait! Bulk minting started. Confirm gas fees.")}</h3>
        </Popup>
      ) : null}

      {open && success ? (
        <Popup>
          <img src={`${BACKENDMEDIAURL}/loaders/done.svg`} />
          <h3>{__("Bulk minting complete.")}</h3>
          <button onClick={() => window.location.assign(redirect)}>
            {__("Close", SLUG)}
          </button>
        </Popup>
      ) : null}
    </>
  );
};
