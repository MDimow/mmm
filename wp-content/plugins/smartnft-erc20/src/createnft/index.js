const renderErc20Symbol = (_default, erc20) => Object.keys(erc20);

wp.hooks.addFilter(
  "SMNFT_RENDER_MULTI_CURRENCY_SYMBOL",
  "SNFT_ERC20",
  renderErc20Symbol,
  10
);
