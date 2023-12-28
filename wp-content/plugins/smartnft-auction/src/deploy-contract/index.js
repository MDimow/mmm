const auctionComponent = (defaultValue, options) => options.component;
wp.hooks.addFilter(
  "SMNFT_RENDER_AUCTION_COMPONENT_ON_LIST",
  "SNFT",
  auctionComponent,
  10
);

const auctionWithdraw = (_defaultCom, payloadCom) => payloadCom;
wp.hooks.addFilter(
  "SMARTNFT_ADD_AUCTION_WITHDRAW_COMPONENT",
  "SNFT",
  auctionWithdraw,
  10
);
