const addChainFilter = (_defaultCom, payloadCom) => payloadCom;

wp.hooks.addFilter(
  "SMARTNFT_ADD_CHAIN_FILTER_ON_FILTER",
  "SNFT",
  addChainFilter,
  10
);
