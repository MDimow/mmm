const auctionComponent = (_defaultValue, options) => options.component;
wp.hooks.addFilter(
  "SMNFT_RENDER_AUCTION_COMPONENTS",
  "SNFT",
  auctionComponent,
  10
);

wp.hooks.addFilter(
  "SNFT_RENDER_SINGLE_PAGE_PUT_ON_AUCTION",
  "SNFT",
  auctionComponent,
  10
);
