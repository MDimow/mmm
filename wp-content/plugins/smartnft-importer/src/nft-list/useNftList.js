import { useState, useEffect } from "react";
import { formatEther } from "ethers/lib/utils";
const NFT_PER_PAGE = 10;

const getNfts = async (pageNo = 1) => {
  try {
    const offset = (pageNo - 1) * NFT_PER_PAGE; //offset means how many result to escape

    const res = await jQuery.ajax({
      type: "post",
      url: importer_local.BACKEND_AJAX_URL,
      data: {
        offset: offset,
        limit: NFT_PER_PAGE,
        contract_addr: importer_local.ACTIVE_CONTRACT.address,
        imported_nfts: true,
        action: "get_nfts",
      },
    });

    console.log(res);

    return res;
  } catch (err) {
    console.log(err);
  }
};

const convertPriceToEther = (price) => formatEther(price);

const useNftList = (pageNo) => {
  const [nfts, setNfts] = useState([]);
  const [totalNfts, setTotalNfts] = useState(0);
  const [isLoading, setLoading] = useState(true);

  useEffect(() => {
    async function fetchData() {
      try {
        //get nfts nfts
        const res = await getNfts(pageNo);
        const resNfts = res.data.nfts;

        console.log("NFts----------------------", res);
        const priceFormatedNfts = resNfts.map((cur) => ({
          ...cur,
          price: convertPriceToEther(cur.price),
        }));

        //set state
        setNfts(priceFormatedNfts);
        setTotalNfts(parseInt(res.data.total_post_found));
        setLoading(false);
      } catch (err) {
        console.error(err);
      }
    }
    fetchData();
  }, [pageNo]);

  return {
    nfts,
    setNfts,
    isLoading,
    setLoading,
    totalNfts,
  };
};

export default useNftList;
