import React, { createContext } from "react";

import { MetamaskNotConnectedError } from "./connect-wallet";
import SelectWallet from "./select-wallet";
import Nfts from "./nfts";
import AddCollectionAndCategory from "./add-collection";
import ConfirmAndImport from "./import";
import Success from "./success";

export const SNFT_IMPORTER_APP_CONTEX = createContext();

export const SNFT_IMPORTER_APP_REDUCER = (state, action) => {
  if (action.type === "CHANGE_STEP") {
    return {
      ...state,
      curStep: action.payload,
    };
  }

  if (action.type === "CHANGE_NETWORK") {
    return {
      ...state,
      network: action.payload,
    };
  }

  if (action.type === "CHANGE_ADDRESS") {
    return {
      ...state,
      address: action.payload,
    };
  }

  if (action.type === "DIRECT_NETWORK_FETCH") {
    return {
      ...state,
      isDirectNetworkFetch: action.payload,
    };
  }

  if (action.type === "CHANGE_CONTRACT") {
    return {
      ...state,
      contract: action.payload,
    };
  }

  if (action.type === "CHANGE_CURSOR") {
    return {
      ...state,
      cursor: action.payload,
    };
  }

  if (action.type === "CHANGE_TOTAL_FOUND") {
    return {
      ...state,
      totalNftFound: action.payload,
    };
  }

  if (action.type === "CHANGE_DIRECT_NFT_PAGE") {
    return {
      ...state,
      directNftPage: action.payload,
    };
  }

  if (action.type === "CHANGE_LIMIT") {
    return {
      ...state,
      limit: parseInt(action.payload),
    };
  }

  if (action.type === "CHANGE_NFT_FROM") {
    return {
      ...state,
      nftFrom: action.payload,
    };
  }

  if (action.type === "CHANGE_NFTS") {
    return {
      ...state,
      nfts: action.payload,
    };
  }

  if (action.type === "ADD_SELECTED_NFTS") {
    return {
      ...state,
      selectedNfts: [...state.selectedNfts, action.payload],
    };
  }

  if (action.type === "CHANGE_SELECTED_NFTS") {
    return {
      ...state,
      selectedNfts: action.payload,
    };
  }

  if (action.type === "REMOVE_SELECTED_NFTS") {
    const id = action.payload;
    const index = state.selectedNfts.findIndex((cur) => cur.id == id);
    if (index >= 0) {
      const newNfts = [...state.selectedNfts];
      newNfts.splice(index, 1);
      return {
        ...state,
        selectedNfts: [...newNfts],
      };
    }
  }

  if (action.type === "ADD_CATEGORY") {
    return {
      ...state,
      category: action.payload,
    };
  }

  if (action.type === "ADD_COLLECTION") {
    return {
      ...state,
      collection: action.payload,
    };
  }

  if (action.type === "CHANGE_LOADING") {
    return {
      ...state,
      loading: action.payload,
    };
  }
};

export const SNFT_IMPORTER_APP_DEFAULT_STATE = {
  totalStep: 5,
  curStep: 1,
  steps: [
    {
      stepNo: 1,
      name: "CONNECT_METAMASK",
      title: "Connect",
      component: () => <MetamaskNotConnectedError />,
    },
    {
      stepNo: 2,
      name: "SELECT_WALLET",
      title: "Choose",
      component: () => <SelectWallet />,
    },
    {
      stepNo: 3,
      name: "FETCH_NFTS",
      title: "Fetch NFTs",
      component: () => <Nfts />,
    },
    {
      stepNo: 4,
      name: "ADD_COLLECTION",
      title: "Collection/Category",
      component: () => <AddCollectionAndCategory />,
    },
    {
      stepNo: 5,
      name: "IMPORT_NFTS",
      title: "Import",
      component: () => <ConfirmAndImport />,
    },
    {
      stepNo: 6,
      name: "SUCCESS_MESSAGE",
      title: "Finish",
      component: () => <Success />,
    },
  ],
  networks: [
    {
      id: 1,
      name: "Ethereum mainnet",
      chain: "eth",
    },
    {
      id: 5,
      name: "Goerli",
      chain: "goerli",
    },
    {
      id: 137,
      name: "Polygon mainnet",
      chain: "polygon",
    },

    {
      id: 80001,
      name: "Mumbai",
      chain: "mumbai",
    },
    {
      id: 97,
      name: "BNB Testnet",
      chain: "bsc testnet",
    },
    {
      id: 66,
      name: "BNB Mainnet",
      chain: "bsc",
    },
    {
      id: 43114,
      name: "Avalanche C-Chain",
      chain: "avalance",
    },
    {
      id: 10001,
      name: "Eth Pow",
      chain: "ethpow",
    },
  ],
  network: null,
  address: "",
  contract: "",
  limit: 10,
  cursor: "",
  nftFrom: "wallet", //wallet,contract,directContract
  nfts: [],
  selectedNfts: [],
  collection: "",
  category: "",
  isDirectNetworkFetch: false,
  totalNftFound: 0,
  directNftPage: 0,
  loading: true,
};
