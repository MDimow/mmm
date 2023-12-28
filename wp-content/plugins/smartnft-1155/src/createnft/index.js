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
      <img src={`${local_1155.MEDIA_URL}erc1155.svg`} />
      <h2>{__("Multiple", global)}</h2>
      <p>{__("If you want to share your NFT with a large number of community members", global)}</p>
    </div>
  );
};

wp.hooks.addFilter(
  "RENDER_CONTRACT_STANDARD",
  "NFT_STANDARD",
  renderMultipleStandard,
  11
);
