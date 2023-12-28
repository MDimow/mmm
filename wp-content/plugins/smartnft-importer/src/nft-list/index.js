import React, { useState } from "react";
import PutonSellForm from "../putsell/form";
import { NftList } from "./table";

const TableAndPutonSellContainer = () => {
  const [showForm, setShowForm] = useState(false);
  const [nft, setNft] = useState(null);

  return (
    <>
      <NftList showForm={setShowForm} setNft={setNft} />
      {showForm ? <PutonSellForm showForm={showForm} setShowForm={setShowForm} nft={nft} /> : null}
    </>
  );
};

export default TableAndPutonSellContainer;
