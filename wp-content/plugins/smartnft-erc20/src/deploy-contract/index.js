const renderErc20Contract = (_component, option) => option.component;
wp.hooks.addFilter(
  "SMNFT_RENDER_ERC20_IMPORT_OR_DEPLOY_ITEM",
  "SNFT_ERC20",
  renderErc20Contract,
  10
);
