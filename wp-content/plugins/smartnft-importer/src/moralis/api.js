const MORALIS_API_KEY = importer_local?.MORALIS_API_KEY;

const formatPropertys = (attributes) => {
  if (!attributes) return "";

  const formatedProperties = attributes.map((cur) => ({
    key: cur?.trait_type,
    value: cur?.value,
  }));

  return formatedProperties;
};

const medaiUrlGenaretor = (url) => {
  if (!url) return null;
  if (url.startsWith("ipfs")) {
    const splited = url.split("//");
    return `https://ola.infura-ipfs.io/ipfs/${splited[1]}`;
  }

  return url;
};

export const formatNftData = (nft, owner) => {
  const metaData = nft.metadata;

  const nftData = {
    name: metaData?.name || nft.name || nft.token_id,
    nftId: nft.token_id,
    description: metaData?.description,
    mediaType: "image/",
    size: "",
    image: metaData?.image,
    mediaUrl: medaiUrlGenaretor(metaData?.image),
    unlockableContent: "",
    collection: "",
    category: "",
    alternatePreview: "",
    royalties: 0,
    hasSplitPayment: false,
    jsonUrl: nft.token_uri,
    price: 0,
    ethPrice: 0,
    creator: owner,
    owner,
    tokenId: nft.token_id,
    isListed: false,
    contract: importer_local?.ACTIVE_CONTRACT?.address,
    ownerAdd: owner,
    properties: formatPropertys(metaData?.properties || metaData?.attributes),
    is_imported_nft: true,
    imported_from_contract: nft.token_address,
  };

  return nftData;
};

const fetchNftByWallet = async ({
  address,
  contract,
  chain,
  limit,
  cursor,
  owner,
}) => {
  if (!address) throw new Error(__("provide account address"));
  let END_POINT = `https://deep-index.moralis.io/api/v2/${address}/nft?chain=${chain}&format=decimal&limit=${limit}&cursor=${
    cursor ? cursor : ""
  }`;
  if (contract) END_POINT += `&token_addresses=${contract}`;

  try {
    const options = {
      method: "GET",
      headers: {
        "Content-Type": "application/json",
        "X-API-Key": MORALIS_API_KEY,
      },
    };

    const res = await fetch(END_POINT, options);
    const nfts = await res.json();
    const formatedNfts = [];

    nfts.result.forEach((cur) => {
      if (cur.contract_type == "ERC721" && cur.metadata) {
        const data = { ...cur, metadata: JSON.parse(cur.metadata) };
        formatedNfts.push(formatNftData(data, owner));
      }
    });

    return {
      formatedNfts,
      cursor: nfts.cursor,
    };
  } catch (err) {
    console.log(err);
  }
};

const fetchNftByContract = async ({ address, chain, limit, cursor, owner }) => {
  if (!address) throw new Error(__("provide Contract address"));
  const END_POINT = `https://deep-index.moralis.io/api/v2/nft/${address}?chain=${chain}&format=decimal&limit=${limit}&cursor=${
    cursor ? cursor : ""
  }`;
  try {
    const options = {
      method: "GET",
      headers: {
        "Content-Type": "application/json",
        "X-API-Key": MORALIS_API_KEY,
      },
    };

    const res = await fetch(END_POINT, options);
    const nfts = await res.json();
    const formatedNfts = [];

    nfts.result.forEach((cur) => {
      if (cur.contract_type == "ERC721" && cur.metadata) {
        const data = { ...cur, metadata: JSON.parse(cur.metadata) };
        formatedNfts.push(formatNftData(data, owner));
      }
    });

    return {
      formatedNfts,
      cursor: nfts.cursor,
    };
  } catch (err) {
    console.log(err);
  }
};

export const useMoralisProvider = () => {
  return { loading: false, fetchNftByWallet, fetchNftByContract };
};
