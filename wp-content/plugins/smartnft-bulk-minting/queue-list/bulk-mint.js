import { BACKEND_AJAX_URL, SLUG } from "../../../../../common/store";
import { Contract } from "ethers";
import Erc1155Contract from "../../../../../contracts/Erc1155";
import Erc721BulkContract from "../../../../../contracts/Erc721Bulk";
import useCollection from "../../../../../common/hook/useCollection.hook";
import { errorMessage } from "../../../../../common/component/message/error";
import useNft from "../../../../../common/hook/useNft.hook";
import useErc1155Owners from "../../../../../common/hook/useErc1155Owners";
import useTxHash from "../../../../../common/hook/useTxhash.hook";
import useActivity from "../../../../../common/hook/useActivity.hook";
const { __ } = wp.i18n;

const COLL_DATA = {
  bannerImg: "",
  bannerMimeType: "",
  profileImg: "",
  profileMimeType: "",
  thumbImg: "",
  thumbMimeType: "",
  name: "",
  symbol: "",
  description: "",
  socialProfiles: "",
  creator: null, //creator gets replaced when first use in REDUCER
  standard: null,
  contractAddress: null,
  network: null,
};

export const bulk_mint = async ({
  web3Provider,
  queue,
  name,
  symbol,
  standard,
}) => {
  if (!name || !symbol) {
    errorMessage(__("Name or Symbol cant be blank.", SLUG));
    throw new Error("Colltion need name and symbol.");
  }

  const { isCollectionExist } = useCollection();

  if (await isCollectionExist(name)) {
    errorMessage(__("Collection name already exist.", SLUG));
    throw new Error("Collection name already exist.");
  }

  const { storeNft } = useNft();
  const { updateErc1155Owners } = useErc1155Owners();
  const { storeTxHashLocally } = useTxHash();
  const { createNewActivity } = useActivity();

  //DEPLOY THE CONTRACT
  let address;
  if (standard == "Erc721") {
    address = await web3Provider.deployContract({
      solidityCompiledJsonObj: Erc721BulkContract,
      signer: web3Provider.signer,
      name,
      symbol,
    });
  }
  if (standard == "Erc1155") {
    address = await web3Provider.deployContract({
      solidityCompiledJsonObj: Erc1155Contract,
      signer: web3Provider.signer,
      name,
      symbol,
    });
  }

  if (!address) throw new Error("No contract address");

  const keys = Object.keys(queue);

  //CREATE COLLECTION
  const coll = await jQuery.ajax({
    type: "post",
    url: BACKEND_AJAX_URL,
    data: {
      collection: {
        ...COLL_DATA,
        name,
        symbol,
        creator: web3Provider.account[0].toLowerCase(),
        standard: standard,
        contractAddress: address,
        network: queue[keys[0]]?.selectedContract?.network,
      },
      action: "smartnft_create_collection",
    },
  });

  const amounts = keys.map((cur) => parseInt(queue[cur].amount));
  const uris = keys.map((cur) => queue[cur].jsonUrl);

  //MINT THE NFTS
  let contract;
  let tx;
  if (standard == "Erc721") {
    contract = new Contract(
      address,
      Erc721BulkContract.abi,
      web3Provider.signer
    );
    tx = await contract.mintBatch(uris.length, uris);
  }
  if (standard == "Erc1155") {
    contract = new Contract(address, Erc1155Contract.abi, web3Provider.signer);
    tx = await contract.mintBatch(amounts, uris);
  }
  await tx.wait();

  keys.forEach((curKey, index) => {
    queue[curKey].tokenId = index + 1; //genareting the tokenId
    queue[curKey].contractAddress = address;
    queue[curKey].collection.name = coll?.data?.coll?.name;
    queue[curKey].collection.slug = coll?.data?.coll?.slug;
    queue[curKey].collection.id = coll?.data?.coll?.term_id;
  });

  //LIST NFT ON SITE
  const promises = keys.map(async (curKey) => {
    return storeNft(queue[curKey]).then((res) => {
      queue[curKey].postId = res.id;
    });
  });

  await Promise.allSettled(promises);

  //SAVE TRANSECTION HASH WITH EVENT NAME
  const promises3 = keys.map((curKey) => {
    return storeTxHashLocally(
      {
        eventType: "Mint",
        signer: web3Provider.account[0].toLowerCase(),
        hash: tx?.hash,
      },
      queue[curKey].postId
    );
  });

  await Promise.allSettled(promises3);

  const erc1155nfts = [];
  keys.forEach((curKey) => {
    if (queue[curKey].standard == "Erc1155") erc1155nfts.push(queue[curKey]);
  });

  //SAVE THE NEW OWNERS
  const promises2 = erc1155nfts.map((cur) => {
    return updateErc1155Owners(
      cur.postId,
      web3Provider.account[0].toLowerCase(),
      {
        amount: cur.amount,
        price: 0,
        isListed: false,
      }
    );
  });

  await Promise.allSettled(promises2);

  //SAVE THE ACTIVITY
  const promises4 = keys.map((curKey) => {
    return createNewActivity({
      post_id: parseInt(queue[curKey].postId),
      activity_type: "mint",
      price: 0,
      addr_from: address.toLowerCase(),
      addr_to: web3Provider.account[0].toLowerCase(),
      chain_id: web3Provider.network.chainId,
      collection_id: coll?.data?.coll?.term_id,
      category_id: "",
    });
  });

  await Promise.allSettled(promises4);

  //DELETE THE NFTS AFTER MINT
  await jQuery.ajax({
    type: "post",
    url: BACKEND_AJAX_URL,
    data: {
      action: "nbm_delete_all_queue",
      account: web3Provider.account[0],
      chainId: web3Provider.network.chainId,
      standard: standard,
    },
  });

  return coll;
};
