import React, { useEffect, useState } from "react";
import useNftList from "./useNftList";
import { Pagination } from "./pagination";
import TableLoading from "./table-loader";
import { Empty } from "antd";
import { escapeHTML } from "@wordpress/escape-html";

const ACTIVE_CONTRACT = importer_local.ACTIVE_CONTRACT;
const SLUG = "smartnft_importer";
const { __ } = wp.i18n;

const changeNftVisibility = async (nft_id, visibility) => {
  try {
    const res = await jQuery.ajax({
      type: "post",
      url: importer_local.BACKEND_AJAX_URL,
      data: {
        nft_id,
        visibility,
        action: "change_nft_visibility",
      },
    });

    console.log(res);

    return res;
  } catch (err) {
    console.log(err);
  }
};

const NftList = ({ showForm, setNft }) => {
  const [curPage, setCurPage] = useState(1);
  const { nfts, totalNfts, isLoading, setLoading } = useNftList(curPage);

  const tableTitles = [
    __("Image", SLUG),
    __("Name", SLUG),
    __("Creator", SLUG),
    __("Owner", SLUG),
    __("Listed", SLUG),
    __("Price", SLUG),
    __("Sell", SLUG),
  ];

  useEffect(() => {}, []);

  return (
    <>
      {isLoading && <TableLoading cols={7} rows={8} indeximg={true} />}
      {!isLoading && (
        <>
          <table className="nft-list__table">
            <thead>
              <tr>
                {tableTitles.map((cur, i) => (
                  <td key={i}>{cur}</td>
                ))}
              </tr>
            </thead>
            <tbody>
              {nfts.map((cur, i) => (
                <TableRow
                  nft={cur}
                  showForm={showForm}
                  setNft={setNft}
                  key={i}
                />
              ))}
            </tbody>
          </table>
          {!nfts.length && (
            <Empty
              image="https://gw.alipayobjects.com/zos/antfincdn/ZHrcdLPrvN/empty.svg"
              imageStyle={{
                height: 60,
                margin: "40px auto",
              }}
              description={
                <span>
                  {escapeHTML(
                    "No NFTs has been imported yet. Please import some NFT first"
                  )}
                </span>
              }
            ></Empty>
          )}
          <Pagination
            totalNfts={totalNfts}
            setCurPage={setCurPage}
            curPage={curPage}
            setLoading={setLoading}
          />
        </>
      )}
    </>
  );
};

const TableRow = ({ nft, showForm, setNft }) => {
  const post_status = nft.post_status == "publish" ? true : false;
  const [status, setStatus] = useState(post_status);

  const changeNFTvisibility = async (post_id, status) => {
    setStatus(status);
    const visibility = status ? "publish" : "private";
    try {
      const res = await changeNftVisibility(post_id, visibility);
      console.log(res);
    } catch (err) {
      console.log(err);
    }
  };

  const shortAddGenaretor = (add) =>
    add ? `${add.substring(0, 8)}...${add.substring(add.length - 4)}` : "";

  return (
    <tr>
      <td className="b-img">
        {nft.mediaType.startsWith("image") && (
          <img src={nft.mediaUrl} alt={nft.name} />
        )}
      </td>
      <td>
        <p>{nft.name}</p>
      </td>
      <td>
        <p>{shortAddGenaretor(nft.creator)}</p>
      </td>
      <td>
        <p>{shortAddGenaretor(nft.owner)}</p>
      </td>
      <td>
        <p>{__("Not Listed", SLUG)}</p>
      </td>
      <td>
        <p>
          {nft.price} {ACTIVE_CONTRACT.network.currencySymbol}
        </p>
      </td>
      <td>
        <a
          onClick={(e) => {
            showForm(true);
            setNft(nft);
          }}
        >
          {__("Put on Sell", SLUG)}
        </a>
      </td>
    </tr>
  );
};
export { NftList };
