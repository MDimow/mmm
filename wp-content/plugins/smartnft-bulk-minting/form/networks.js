import React, { useContext, useEffect } from "react";
import { SLUG } from "../../../../../common/store";
import { CreateNftContext } from "./state";
const { __ } = wp.i18n;

const Networks = ({ web3Provider, deployedContracts }) => {
  const { state, dispatch } = useContext(CreateNftContext);

  useEffect(() => {
    if (deployedContracts.length == 1) {
      dispatch({
        type: "SET_SELECTED_CONTRACT",
        payload: deployedContracts[0],
      });
      dispatch({ type: "CHANGE_COMPONENT", payload: 1 });
    }
  }, []);

  if (deployedContracts.length == 1) return null;

  return (
    <>
      <div className="deployed-networks-heading">
        <h2>{__("Choose Blockchain", SLUG)}</h2>
        <p>
          {__(
            "Choose the most suitable blockchain for your needs. You need to connect wallet for creation",
            SLUG
          )}
        </p>
      </div>
      <div className="deployed-networks">
        {deployedContracts.map((cur, i) => (
          <Network
            contract={cur}
            dispatch={dispatch}
            key={i}
            web3Provider={web3Provider}
          />
        ))}
      </div>
    </>
  );
};

const Network = ({ contract, dispatch, web3Provider }) => {
  const changeNetworkAndSetContract = async () => {
    try {
      if (web3Provider.network.chainId != parseInt(contract.network.chainId)) {
        return await web3Provider.switchNetwork(
          contract.network,
          web3Provider.wallet
        );
      }

      dispatch({ type: "SET_SELECTED_CONTRACT", payload: contract });
      dispatch({ type: "CHANGE_COMPONENT", payload: 1 });
    } catch (err) {
      console.error(err);
    }
  };

  return (
    <div className="network" onClick={changeNetworkAndSetContract}>
      <img src={contract?.network?.icon} />
      <h2>{contract?.network?.nickName}</h2>
    </div>
  );
};

export default Networks;
