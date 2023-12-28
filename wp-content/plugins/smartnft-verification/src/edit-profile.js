const renderVBComp = (_default, option) => option.component;
wp.hooks.addFilter(
  "SMNFT_RENDER_VB_COMP_ON_EDIT_PROFILE_PAGE",
  "SMNFT_VB",
  renderVBComp,
  10
);
