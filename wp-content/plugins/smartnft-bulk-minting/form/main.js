import React from "react";
import NetworkInfo from "../../create-nft/network-info";
import MediaUpload from "./media";
import Name from "./name";
import Description from "./description";
import Properties from "./properties";
import Labels from "./labels";
import Stats from "./stats";
import AddQueueBtn from "./add-queue";
import Amount from "./amount";

export const Form = ({ web3Provider }) => {
  return (
    <div>
      <NetworkInfo web3Provider={web3Provider} />
      <MediaUpload />
      <Name />
      <Description />
      <Amount />
      <Properties />
      <Labels />
      <Stats />
      <AddQueueBtn web3Provider={web3Provider} />
    </div>
  );
};
