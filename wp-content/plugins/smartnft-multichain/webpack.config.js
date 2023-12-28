const path = require("path");

const config = {
  module: {
    rules: [
      {
        test: /\.(js|jsx)$/,
        exclude: /node_modules/,
        use: { loader: "babel-loader" },
      },
    ],
  },
  optimization: {
    minimize: true,
  },
};

const createNft_multichain_addon_config = Object.assign({}, config, {
  entry: {
    assets: "./src/createnft/index",
  },
  output: {
    path: path.join(__dirname),
    filename: "[name]/js/createnft-multichain-addon.bundle.js",
  },
});

const createColl_multichain_addon_config = Object.assign({}, config, {
  entry: {
    assets: "./src/create-collection/index",
  },
  output: {
    path: path.join(__dirname),
    filename: "[name]/js/createcoll-multichain-addon.bundle.js",
  },
});

const allnft_multichain_addon_config = Object.assign({}, config, {
  entry: { assets: "./src/allnft/index" },
  output: {
    path: path.join(__dirname),
    filename: "[name]/js/allnft-multichain-addon.bundle.js",
  },
});

const deploy_contract_multichain_addon_config = Object.assign({}, config, {
  entry: {
    assets: "./src/deploy-contract/index",
  },
  output: {
    path: path.join(__dirname),
    filename: "[name]/js/deploy-contract-multichain-addon.bundle.js",
  },
});

module.exports = [
  createNft_multichain_addon_config,
  createColl_multichain_addon_config,
  allnft_multichain_addon_config,
  deploy_contract_multichain_addon_config,
];
