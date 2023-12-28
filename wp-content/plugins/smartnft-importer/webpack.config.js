const path = require("path");

const config = {
  module: {
    rules: [
      {
        test: /\.(js|jsx)$/,
        exclude: /node_modules/,
        use: {
          loader: "babel-loader",
        },
      },
      {
        test: /\.css$/i,
        use: ["style-loader", "css-loader"],
      },
    ],
  },
};

const adminImportNftConfig = Object.assign({}, config, {
  entry: "./src/index.js",
  output: {
    path: path.join(__dirname),
    filename: "assets/js/import-nft.bundle.js",
  },
});
const frontendImportNftConfig = Object.assign({}, config, {
  entry: "./src/elements/index.js",
  output: {
    path: path.join(__dirname),
    filename: "assets/js/import-nft-frontend.bundle.js",
  },
});

module.exports = [
  adminImportNftConfig,
  frontendImportNftConfig
];
