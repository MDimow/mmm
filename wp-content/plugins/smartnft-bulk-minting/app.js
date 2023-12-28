import React, { useReducer } from "react";
import { createRoot } from "react-dom/client";
import { Form } from "./form/main";
import { QueueList } from "./queue-list/main";
import useWeb3provider from "../../../../common/hook/wallet.hook";
import MainErrorCapturer from "../../../../common/component/error-comp/main-error";
import useDeployedContractsOnNetworks from "../../../../common/hook/contracts.hook";
import NoDeployedContract from "../create-nft/no-deployed-contract";
import Networks from "./form/networks";
import { CreateNftContext, INISIAL_STATE, REDUCER } from "./form/state";
import SelectContractStandard from "./form/standard";

const App = () => {
  const web3Provider = useWeb3provider();
  const deployedContracts = useDeployedContractsOnNetworks();
  const [state, dispatch] = useReducer(REDUCER, INISIAL_STATE);

  if (web3Provider.loading || deployedContracts.isLoading) return null;
  if (!deployedContracts?.contracts?.length) return <NoDeployedContract />;

  const comp = [
    <Networks
      web3Provider={web3Provider}
      deployedContracts={deployedContracts.contracts}
    />,
    <SelectContractStandard web3Provider={web3Provider} />,
    <div id="nft-bulk-minting">
      <Form web3Provider={web3Provider} />
      <QueueList web3Provider={web3Provider} />
    </div>,
  ];

  return (
    <MainErrorCapturer>
      <CreateNftContext.Provider value={{ state, dispatch }}>
        <div>{comp[state.component]}</div>
      </CreateNftContext.Provider>
    </MainErrorCapturer>
  );
};

const container = document.getElementById("smartnft-root");
const appRoot = createRoot(container);
if (container) {
  appRoot.render(<App />);
}
