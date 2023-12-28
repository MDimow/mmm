import { createContext } from "react";

export const FORM_CONTEX = createContext();

export const INISIAL_STATE = {
  price: 0,
  hasSplitPayment: false,
  splitPaymentsAccounts: [], //{address,percentage}
  nft: null, //will get replace when first inisialize
  name: "",
};

export const REDUCER = (state, action) => {
  if (action.type == "CHANGE_PRICE") {
    return {
      ...state,
      price: action.payload,
    };
  }

  if (action.type == "CHANGE_NAME") {
    return {
      ...state,
      name: action.payload,
    };
  }

  if (action.type == "CHANGE_SPLIT_PAYMENT") {
    return {
      ...state,
      splitPaymentsAccounts: action.payload,
    };
  }

  if (action.type == "RESET_SPLIT_PAYMENT") {
    return {
      ...state,
      splitPaymentsAccounts: [],
      hasSplitPayment: false,
    };
  }

  if (action.type == "ACTIVE_SPLIT_PAYMENT") {
    return {
      ...state,
      splitPaymentsAccounts: [{ address: "", percentage: 0 }],
      hasSplitPayment: true,
    };
  }

  if (action.type == "SET_NFT") {
    return {
      ...state,
      nft: action.payload,
    };
  }
};
