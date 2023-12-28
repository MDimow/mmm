import React, { useContext, useEffect } from "react";
import { CreateNftContext } from "./state";
const { __ } = wp.i18n;
import { BACKENDMEDIAURL, SLUG } from "../../../../../common/store";

const renderSingleStandard = (component, state, dispatch) => {
  const selectStandard = (standard) => {
    dispatch({ type: "SET_STANDARD", payload: standard });
    dispatch({ type: "CHANGE_COMPONENT", payload: 2 });
  };

  return [
    ...component,
    state.selectedContract?.contract?.Erc721 &&
    state.selectedContract?.contract?.Erc721?.address ? (
      <SingleStandard selectStandard={selectStandard} key="single-standard" />
    ) : null,
  ];
};

wp.hooks.addFilter(
  "RENDER_CONTRACT_STANDARD",
  "NFT_STANDARD",
  renderSingleStandard,
  10
);

const SelectContractStandard = ({ web3Provider }) => {
  const { state, dispatch } = useContext(CreateNftContext);
  const component = wp.hooks.applyFilters(
    "RENDER_CONTRACT_STANDARD",
    [],
    state,
    dispatch
  );

  useEffect(() => {
    async function action() {
      if (component.length == 1) {
        dispatch({ type: "SET_STANDARD", payload: "Erc721" });
        dispatch({ type: "CHANGE_COMPONENT", payload: 2 });
      }

      if (
        web3Provider.network.chainId !=
        parseInt(state.selectedContract.network.chainId)
      ) {
        await web3Provider.switchNetwork(
          state.selectedContract.network,
          web3Provider.wallet
        );
      }
    }

    action();
  }, []);

  if (component.length == 1) return null;

  return (
    <>
      <div className="deployed-networks-heading">
        <h2>{__("Choose Contract Standard", SLUG)}</h2>
        <p>
          {__(
            "Choose the most suitable blockchain for your needs. You need to connect wallet for creation",
            SLUG
          )}
        </p>
      </div>
      <div className="contracts-standard">{component}</div>
    </>
  );
};

const SingleStandard = ({ selectStandard }) => {
  return (
    <div
      className="contract__single"
      onClick={() => {
        selectStandard("Erc721");
      }}
    >
      <img src={`${BACKENDMEDIAURL}erc721.svg`} />
      <h2>{__("Single", SLUG)}</h2>
      <p>
        {__(
          "If you want to highlight the uniqueness and individuality of your item",
          SLUG
        )}
      </p>
    </div>
  );
};

export default SelectContractStandard;
