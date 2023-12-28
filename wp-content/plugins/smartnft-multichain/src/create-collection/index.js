const filterContracts = (contracts, originalContracts) => originalContracts;
wp.hooks.addFilter(
  "SMARTNFT_FILTER_CONTRACTS_BEFORE_RENDER_COLL_PAGE",
  "SNFT",
  filterContracts,
  11
);
