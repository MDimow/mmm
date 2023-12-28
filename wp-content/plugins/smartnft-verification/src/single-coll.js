const renderVBComp = (_default, option) => option.component;
wp.hooks.addFilter(
  "SMNFT_RENDER_VB_COMP_ON_SINGLE_COLL_PAGE",
  "SMNFT_VB",
  renderVBComp,
  10
);
