import React from "react";
import { global } from "../global/store";
const { __ } = wp.i18n;

const render1155Form = (component, web3Provider, Form) => {
  return [
    ...component,
    <Form
      title={__("ERC 1155 Contract", global.SLUG)}
      des={__(
        "To buy and sell your nfts  in this network you need to deploy your market contract.",
        global.SLUG
      )}
      web3Provider={web3Provider}
      standard="Erc1155"
      hasField={true}
      key="erc1155"
    />,
  ];
};

wp.hooks.addFilter(
  "RENDER_DEPLOYABLE_CONTRACT",
  "RENDER_DEPLOYABLE_CONTRACT_FORM",
  render1155Form,
  11
);

const renderErc1155Item = (component, option, Item) => {
  return [
    ...component,
    <Item
      name={__("ERC-1155", global.SLUG)}
      address={option?.contract?.Erc1155?.address}
      fn={option.fn("Erc1155", true)}
      key="Erc1155"
    />,
  ];
};

wp.hooks.addFilter(
  "SMNFT_RENDER_ERC1155_ITEM",
  "SMNFT_ITEM",
  renderErc1155Item,
  10
);
