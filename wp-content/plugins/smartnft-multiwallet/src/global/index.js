import { global } from "./store";
const { __ } = wp.i18n;

const coinbaseConnectMarkup = (prevValue) => {
  return `
  	${prevValue}
    <div class="coinbase-connect">
      <img src="${global.MEDIA_URL}coinbase.svg"/>
      <h3>${__("Coinbase", global.SLUG)}</h3>
    </div>
	`;
};

wp.hooks.addFilter(
  "SNFT_RENDER_WALLET_NAME_FOR_CONNECT",
  "SNFT_CB",
  coinbaseConnectMarkup,
  11
);

const addListenerOnCbEl = () => {
  const cb = document.querySelector(".coinbase-connect");
  if (!cb) return null;

  cb.addEventListener("click", () => {
    if (!SMNFT_WEB3_PROVIDER.web3Provider.isCoinbaseInstalled()) {
      return smartnftRenderNotInstallMessage("cb");
    }

    const wallet = SMNFT_WEB3_PROVIDER.web3Provider.selectCoinbaseWallet();

    SMNFT_WEB3_PROVIDER.web3Provider.connectWallet(wallet);
  });
};

wp.hooks.addAction(
  "SNFT_ADD_LISTENER_ON_RENDERED_WALLET",
  "SNFT_CB_LISTENER",
  addListenerOnCbEl,
  10
);
