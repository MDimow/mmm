const storeImportedNft = async (nft) => {
  try {
    const res = await jQuery.ajax({
      type: "post",
      url: importer_local.BACKEND_AJAX_URL,
      data: { nft, action: "store_nft" },
    });

    return res;
  } catch (err) {
    console.log(err);
  }
};

export default storeImportedNft;
