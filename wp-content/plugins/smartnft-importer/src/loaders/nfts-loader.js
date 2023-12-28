import React from "react";

const NftsLoader = () => {
  const tempArr = Array.from(Array(18).keys());

  return (
    <div className="nfts-loader-container">
      {tempArr.map((cur) => (
        <NftLoader key={cur} />
      ))}
    </div>
  );
};

const NftLoader = () => (
  <div className="nft-loader">
    <figure className="skeleton-box"></figure>
    <div>
      <p className="skeleton-box"></p>
    </div>
  </div>
);

export default NftsLoader;
