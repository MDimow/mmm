import { createContext } from "react";

export const CreateNftContext = createContext();

const meta = {
  name: "",
  description: "",
  image: null,
  attributes: [],
};

export const INISIAL_STATE = {
  selectedContract: null,
  component: 0,
  standard: "Erc721",
  meta: meta,
  file: null,
  fileType: null,
  mediaUrl: null,
  mediaBinary: null,
  thumbnailMediaUrl: null,
  properties: [],
  labels: [],
  stats: [],
  jsonUrl: null,
  price: 0,
  priceInWei: 0,
  amount: 1,
  url: null,
  hasSplitPayment: false,
  // property {address:"0x0xxxx",percentage:0 - 10000} percentage * 100
  splitPayments: [],
  // royalty percentage:0 - 10000.percentage * 100
  royalty: 0,
  unlockableContent: null,
  unlockableFiles: [],
  category: { name: "", slug: "", id: "" },
  collection: { name: "", slug: "", id: "" },
  auction: {
    isAuctionSet: false,
    startDate: 0,
    startTime: 0,
    endDate: 0,
    endTime: 0,
    minPrice: 0,
  },
  customCoin: {
    isCustomCoin: false,
    contract: {},
  },
  isFreeMinting: false,
  isListed: false,
  isBulkMinted: true,
};

export const REDUCER = (state, action) => {
  if (action.type == "SET_DEPLOYED_CONTRACTS") {
    return { ...state, deployedContracts: action.payload };
  }

  if (action.type == "SET_MEDIA_BINARY") {
    return { ...state, mediaBinary: action.payload };
  }

  if (action.type == "SET_URL") {
    return { ...state, url: action.payload };
  }

  if (action.type == "CHANGE_COMPONENT") {
    return { ...state, component: action.payload };
  }

  if (action.type == "CHANGE_CATEGORY") {
    return { ...state, category: action.payload };
  }

  if (action.type == "CHANGE_COLLECTION") {
    return { ...state, collection: action.payload };
  }

  if (action.type == "SET_MEDIA_URL") {
    return { ...state, mediaUrl: action.payload };
  }

  if (action.type == "SET_FILE") {
    return { ...state, file: action.payload };
  }

  if (action.type == "CHANGE_AMOUNT") {
    return { ...state, amount: action.payload };
  }

  if (action.type == "SET_PROPERTIES") {
    return { ...state, properties: action.payload };
  }

  if (action.type == "SET_LABELS") {
    return { ...state, labels: action.payload };
  }

  if (action.type == "SET_STATS") {
    return { ...state, stats: action.payload };
  }

  if (action.type == "SET_THUMBNAIL_BINARY") {
    return { ...state, thumbnailBinary: action.payload };
  }

  if (action.type == "SET_THUMBNAIL_MEDIA_URL") {
    return { ...state, thumbnailMediaUrl: action.payload };
  }

  if (action.type == "SET_SELECTED_CONTRACT") {
    return { ...state, selectedContract: action.payload };
  }

  if (action.type == "SET_FILE_TYPE") {
    return { ...state, fileType: action.payload };
  }

  if (action.type == "CHANGE_META") {
    return { ...state, meta: changeMeta(state, action) };
  }

  if (action.type == "SET_STANDARD") {
    return { ...state, standard: action.payload };
  }

  if (action.type == "SET_JSON_URL") {
    return { ...state, jsonUrl: action.payload };
  }

  return { ...state };
};

const changeMeta = (state, action) => {
  if (action.key == "NAME") {
    return { ...state.meta, name: action.payload };
  }

  if (action.key == "DESCRIPTION") {
    return { ...state.meta, description: action.payload };
  }

  if (action.key == "IMAGE") {
    return { ...state.meta, image: action.payload };
  }

  if (action.key == "ATTRIBUTES") {
    return { ...state.meta, attributes: action.payload };
  }

  return { ...state.meta };
};
