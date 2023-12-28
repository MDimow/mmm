import React from "react";

import { global } from "../global/store";
const { __ } = wp.i18n;

const renderMultipleStandard = (component, state, dispatch) => {
  const selectStandard = (standard) => {
    dispatch({ type: "SET_STANDARD", payload: standard });
    dispatch({ type: "CHANGE_COMPONENT", payload: 2 });
  };

  return [
    ...component,
    state?.selectedContract?.contract?.Erc1155 &&
    state?.selectedContract?.contract?.Erc1155?.address ? (
      <MultipleStandard selectStandard={selectStandard} key="multi-standard" />
    ) : null,
  ];
};

const MultipleStandard = ({ selectStandard }) => {
  return (
    <div
      className="contract__multiple"
      onClick={() => {
        selectStandard("Erc1155");
      }}
    >
      <h2>{__("Multiple", global.SLUG)}</h2>
      <p>{__("Create collection on ERC-1155 standard.", global.SLUG)}</p>
    </div>
  );
};

wp.hooks.addFilter(
  "RENDER_CONTRACT_STANDARD_FOR_COLLECTION",
  "NFT_STANDARD_FOR_COLLECTION",
  renderMultipleStandard,
  11
);
