import React, { useContext, useEffect, useState } from "react";
import { SNFT_IMPORTER_APP_CONTEX } from "./state";
import { formatNftData } from "./moralis/api";
import { Button } from "antd";
import { notification } from "antd";
import { WarningOutlined } from "@ant-design/icons";
import { escapeHTML } from "@wordpress/escape-html";
import { WrongNetworkError } from "./common";
const SLUG = "smartnft_importer";
const { __ } = wp.i18n;

const getBalance = async ({ contract, address }) => {
  const balance = await contract.balanceOf(address);
  console.log(balance.toNumber());
  const balanceNum = parseInt(balance.toNumber());
  return balanceNum;
};

const getIds = async ({ contract, balance, ownerAddress }) => {
  const tempArr = Array.from(Array(balance).keys());
  console.log(tempArr);

  const t1 = Date.now();
  const ids = await Promise.all(
    tempArr.map(async (cur) => {
      return (await contract.tokenOfOwnerByIndex(ownerAddress, cur)).toNumber();
    })
  );
  const t2 = Date.now();

  console.log(ids);
  console.log(escapeHTML(`Time take for ids${(t2 - t1) / 1000} second`));
  return ids;
};

const getUris = async ({ contract, idsArr }) => {
  const t1 = Date.now();

  const uris = await Promise.all(
    idsArr.map(async (cur) => {
      const uri = await contract.tokenURI(cur);
      return {
        tokenId: cur,
        tokenURI: uri,
        contract: contract.address,
      };
    })
  );

  const filterJsonUris = [];

  uris.forEach((cur) => {
    if (cur.tokenURI.startsWith("https")) {
      filterJsonUris.push(cur);
    }
  });

  const t2 = Date.now();

  console.log(`Time takes for URI ${(t2 - t1) / 1000} second.`);
  console.log(uris);
  return filterJsonUris;
};

const getJsonsFromLocal = async (uri) => {
  const res = await jQuery.ajax({
    type: "post",
    url: importer_local.BACKEND_AJAX_URL,
    data: {
      uri,
      action: "smartnft_importer_fetch_token_uri",
    },
  });

  return res;
};

const formatData = (data, owner) => {
  const temp = {
    token_id: data.tokenId,
    token_uri: data.tokenURI,
    token_address: data.contract,
    metadata: data.data,
  };

  return formatNftData(temp, owner);
};

const getJsons = async ({ uris }) => {
  const t1 = Date.now();
  const jsons = await Promise.allSettled(
    uris.map(async (cur) => {
      const res = await getJsonsFromLocal(cur.tokenURI);
      return {
        ...cur,
        data: res,
      };
    })
  );
  const foramtdAndFilterdFaildReq = [];

  console.log(jsons);

  jsons.forEach((cur) => {
    if (cur.status == "fulfilled" && cur.value.data.data) {
      // console.log(JSON.parse(cur.value.data.data))
      foramtdAndFilterdFaildReq.push({
        ...cur.value,
        data: JSON.parse(cur.value.data?.data),
      });
    }
  });

  const t2 = Date.now();
  console.log(`Time takes for jsons ${(t2 - t1) / 1000} second.`);
  return foramtdAndFilterdFaildReq;
};

export const directFetchTokenFromNetwork = async ({
  state,
  dispatch,
  web3Provider,
}) => {
  try {
    const contract = web3Provider.contractEnumurable({
      contract: state.contract,
      signer: web3Provider.signer,
    });
    console.log(contract);

    const ownerAddress = state.address;

    const balance = await getBalance({ contract, address: ownerAddress });

    const ids = await getIds({
      contract,
      balance,
      ownerAddress,
    });

    const start = state.limit * state.directNftPage;
    const end = start + state.limit;
    const idsPart = ids.slice(start, end);

    const uris = await getUris({ contract, idsArr: idsPart });

    const jsons = await getJsons({ uris });

    const nfts = jsons.map((cur) => formatData(cur, ownerAddress));

    //dispatch
    dispatch({ type: "CHANGE_TOTAL_FOUND", payload: balance });
    dispatch({
      type: "CHANGE_NFTS",
      payload: [
        ...state.nfts,
        ...nfts.map((cur) => ({
          id: Date.now() + Math.round(Math.random() * 1000),
          nft: cur,
        })),
      ],
    });
    dispatch({ type: "CHANGE_STEP", payload: 3 });
    dispatch({
      type: "CHANGE_DIRECT_NFT_PAGE",
      payload: state.directNftPage + 1,
    });
    dispatch({ type: "CHANGE_LOADING", payload: false });
  } catch (err) {
    console.log(err);
    return notification.error({
      message: escapeHTML("Fetching error"),
      description: escapeHTML(
        "Got error while fetching nft! Give valid info and make sure your Contract implemented standard functionality."
      ),
      icon: <WarningOutlined style={{ color: "#108ee9" }} />,
    });
  }
};

export const DirectContractFetch = () => {
  const { state, dispatch, web3Provider } = useContext(
    SNFT_IMPORTER_APP_CONTEX
  );
  const [loading, setLoading] = useState(false);
  const [contractAddress, setContractAddress] = useState("");
  const [ownerAddress, setOwnerAddress] = useState("");

  return (
    <div>
      <div className="wrong-network-warning">
        <WrongNetworkError />
      </div>

      <h3>{escapeHTML(__("Fetch From Contract and wallet address", SLUG))}</h3>
      <h3>
        {escapeHTML(
          __("Make sure your wallet is connected to ETH POW network.", SLUG)
        )}
      </h3>
      <div style={{ marginBottom: 20 }}>
        <p>{escapeHTML(__("Contract Address:", SLUG))}</p>
        <input
          style={{ width: 300 }}
          type="text"
          value={contractAddress}
          onChange={(e) => {
            const value = e.target.value.trim();
            dispatch({ type: "CHANGE_CONTRACT", payload: value });
            setContractAddress(value);
          }}
          placeholder="contract address"
        />
      </div>
      <div style={{ marginBottom: 20 }}>
        <p>{escapeHTML(__("Owner Address:", SLUG))}</p>
        <input
          style={{ width: 300 }}
          type="text"
          value={ownerAddress}
          onChange={(e) => {
            const value = e.target.value.trim();
            dispatch({ type: "CHANGE_ADDRESS", payload: value });
            setOwnerAddress(value);
          }}
          placeholder="owner address"
        />
      </div>

      <div style={{ marginBottom: 20 }}>
        <p>{escapeHTML(__("Limit:", SLUG))}</p>
        <input
          type="number"
          value={state.limit}
          onChange={(e) => {
            const value = e.target.value;
            dispatch({ type: "CHANGE_LIMIT", payload: value });
          }}
          placeholder="limit"
        />
      </div>

      <div style={{ marginTop: 20 }}>
        <Button
          style={{ marginRight: 10 }}
          onClick={() => {
            dispatch({
              type: "CHANGE_STEP",
              payload: 1,
            });
          }}
        >
          {escapeHTML(__("Previous", SLUG))}
        </Button>
        <Button
          loading={loading}
          type="primary"
          onClick={() => {
            directFetchTokenFromNetwork({ state, dispatch, web3Provider });
            setLoading(true);
          }}
        >
          {escapeHTML(__("Fetch NFTs", SLUG))}
        </Button>
      </div>
    </div>
  );
};
