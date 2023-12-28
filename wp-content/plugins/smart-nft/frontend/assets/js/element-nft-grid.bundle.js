/*! For license information please see element-nft-grid.bundle.js.LICENSE.txt */
(()=>{"use strict";var t={408:(t,e)=>{Symbol.for("react.element"),Symbol.for("react.portal"),Symbol.for("react.fragment"),Symbol.for("react.strict_mode"),Symbol.for("react.profiler");var r=Symbol.for("react.provider"),n=Symbol.for("react.context"),o=(Symbol.for("react.forward_ref"),Symbol.for("react.suspense"),Symbol.for("react.memo"),Symbol.for("react.lazy"),Symbol.iterator,{isMounted:function(){return!1},enqueueForceUpdate:function(){},enqueueReplaceState:function(){},enqueueSetState:function(){}}),a=Object.assign,s={};function i(t,e,r){this.props=t,this.context=e,this.refs=s,this.updater=r||o}function l(){}function c(t,e,r){this.props=t,this.context=e,this.refs=s,this.updater=r||o}i.prototype.isReactComponent={},i.prototype.setState=function(t,e){if("object"!=typeof t&&"function"!=typeof t&&null!=t)throw Error("setState(...): takes an object of state variables to update or a function which returns an object of state variables.");this.updater.enqueueSetState(this,t,e,"setState")},i.prototype.forceUpdate=function(t){this.updater.enqueueForceUpdate(this,t,"forceUpdate")},l.prototype=i.prototype;var d=c.prototype=new l;d.constructor=c,a(d,i.prototype),d.isPureReactComponent=!0;Array.isArray,Object.prototype.hasOwnProperty;e.createContext=function(t){return(t={$$typeof:n,_currentValue:t,_currentValue2:t,_threadCount:0,Provider:null,Consumer:null,_defaultValue:null,_globalName:null}).Provider={$$typeof:r,_context:t},t.Consumer=t}},294:(t,e,r)=>{t.exports=r(408)}},e={};function r(n){var o=e[n];if(void 0!==o)return o.exports;var a=e[n]={exports:{}};return t[n](a,a.exports,r),a.exports}(()=>{(0,r(294).createContext)();local.backendMediaUrl;const t=(local.frontendMediaUrl,local.backend_ajax_url,local.profile,local.profile_edit,local.site_root,local.site_title,local.settings||{fixedListingPriceForCustomCoin:0,general:{loadmore:"infinite"},nftpages:{all:{cols:3,width:1e3,perpage:12,search:!0,filter:!0,filterToggle:!0},single:{likebtn:!0,sharebtn:!0,infotabs:!0,width:1e3},create:{splitPayment:!0,royalty:!0,unlockable:!0,properties:!0,freeminting:!0,width:900,uploadsize:2,category:!0,collection:!0,labels:!0,stats:!0}},collections:{all:{cols:5,width:1e3,perpage:12,view:"grid"},create:{width:991,thumb:!1},single:{cols:5,width:1e3,perpage:12,desc:!0,creator:!0,links:!0,search:!0,filter:!0,filterToggle:!0}},categories:{all:{cols:5,width:1e3,perpage:12},create:{width:991},single:{cols:5,width:1e3,perpage:12,desc:!0,search:!0,filter:!0,filterToggle:!0}},profile:{single:{width:1e3,address:!0,desc:!0,links:!0,filterToggle:!0},edit:{width:990},nfts:{cols:4,perpage:12,filter:!0,search:!0},collections:{cols:5,perpage:12}}}),e=local.translation||{},n=(local.addons,local.MORALIS_API_KEY,local.settings?.infuraProjectId?local.settings?.infuraProjectId:null),o=local.settings?.infuraIpfsSecret?local.settings?.infuraIpfsSecret:null,{__:a}=(btoa(`${n}:${o}`),t.nftpages?.all?.perpage&&parseInt(t.nftpages?.all?.perpage),local.custom_networks.map((t=>({...t,chainId:parseInt(t.chainId)}))),wp.i18n),s=t=>{let r=null,n=null,o=null,a=null;const s=async t=>{if(n.category!=t&&t)try{a.startLoader();const e=await n.fetchNfts(t);a.renderNfts(e)}catch(t){console.log(t)}};r=JSON.parse(t),o=new class{constructor(t,e){this.categories=t||[],this.rootEl=e}renderCategories(){const t=this.categories.map(((t,e)=>this.renderCategory(t,e))).join(" ");this.clearRootEl(),this.rootEl&&this.rootEl.insertAdjacentHTML("afterbegin",t)}renderCategory(t,e){return`<p class="nft-grid__category ${0==e?"active":""}">${t.replace("-"," ")}</p>`}clearRootEl(){this.rootEl.innerHTML=""}}(r.category_selector,document.querySelector(`.smartnft_nft_grid_categories_${r.unique_id}`)),a=new class{constructor(t,e){this.settings=t,this.rootEl=e,this.limit=t.limit||12}startLoader(){const t=[...Array(this.limit)].map((t=>'\n        <div class="all-nft-skeleton__col" >\n          <div class="all-nft-skeleton__inner">\n            <div class="all-nft-skeleton__image-con">\n              <span class="skeleton-box all-nft-skeleton__image"></span>\n            </div>\n            <div class="all-nft-skeleton__contents">\n              <span class="skeleton-box all-nft-skeleton__name"></span>\n              <span class="skeleton-box all-nft-skeleton__price-label"></span>\n              <span class="skeleton-box all-nft-skeleton__price"></span>\n            </div>\n          </div>\n        </div>')).join(" ");this.clearRootEl(),this.rootEl&&this.rootEl.insertAdjacentHTML("afterbegin",t)}renderNfts(t){if(console.log(t),!t.length)return this.clearRootEl(),void this.rootEl.insertAdjacentHTML("afterbegin",this.notFound());const e=t.map((t=>this.renderNft(t))).join(" ");this.clearRootEl(),this.rootEl.insertAdjacentHTML("afterbegin",e),this.addMouseHoverVideoPlayListener()}addMouseHoverVideoPlayListener(){this.rootEl.querySelectorAll(".video-preview video").forEach((t=>{t.addEventListener("mouseenter",(t=>t.target.play())),t.addEventListener("mouseleave",(t=>t.target.pause()))}))}notFound(){return`\n      <div class="nonft-found">\n        <h3> ${e?.not_for_sale}</h3>\n      </div>`}videoMarkup(t){return`\n          <div class="video-preview">\n            <video\n              class="card__img"\n              src=${t?.mediaUrl}\n              alt=${t?.meta?.name}\n            />\n            <span class="video-icon">\n              <svg\n                xmlns="http://www.w3.org/2000/svg"\n                width="16"\n                height="16"\n                fill="currentColor"\n                class="bi bi-play-fill"\n                viewBox="0 0 16 16"\n              >\n                <path d="m11.596 8.697-6.363 3.692c-.54.313-1.233-.066-1.233-.697V4.308c0-.63.692-1.01 1.233-.696l6.363 3.692a.802.802 0 0 1 0 1.393z" />\n              </svg>\n            </span>\n          </div>`}audioMarkup(t){const e=t?.thumbnailMediaUrl?.attach_url;return`\n        <div class="audio-preview">\n          <span class="form-preview-icon">\n            <svg\n              xmlns="http://www.w3.org/2000/svg"\n              width="16"\n              height="16"\n              fill="currentColor"\n              class="bi bi-music-note"\n              viewBox="0 0 16 16"\n            >\n              <path d="M9 13c0 1.105-1.12 2-2.5 2S4 14.105 4 13s1.12-2 2.5-2 2.5.895 2.5 2z" />\n              <path fillRule="evenodd" d="M9 3v10H8V3h1z" />\n              <path d="M8 2.82a1 1 0 0 1 .804-.98l3-.6A1 1 0 0 1 13 2.22V4L8 5V2.82z" />\n            </svg>\n          </span>\n          ${e&&`<img src=${e} />`}\n        </div>`}imgMarkup(t){return`<img class="card__img" src=${t?.thumbnailMediaUrl?.attach_url} alt=${t.meta?.name} />`}userNameMarkup(t){return t.creator_name?`<p class=" meta-font card__creator">\n\t\t\t\t${t.creator_name}\n\t  \t\t ${t?.is_creator_verified?`<img src="${nft_grid_local?.frontendMediaUrl}verified.svg" />`:""}\n\t\t\t  </p>`:`\n      <p class="meta-font card__creator">\n        ${t.creator?.substring(0,7)}....\n        ${t.creator?.substring(t.creator.length-4)}\n\t  \t\t ${t?.is_creator_verified?`<img src="${nft_grid_local?.frontendMediaUrl}verified.svg" />`:""}\n      </p>`}listedMarkup(t){return"true"==t.isListed?`\n      <p class="header-three card__price">\n            ${t.price} ${"true"==t?.customCoin?.isCustomCoin?t?.customCoin?.contract?.symbol:t?.selectedContract?.network.currencySymbol}\n      </p>`:`\n        <p class="header-three card__price">\n          ${e?.not_for_sale}\n        </p>`}nftNameMarkup(t){return`\n        <p class="header-three card__name">\n          ${t?.meta?.name}\n        </p>`}renderNft(t){return`\n    <div class="card">\n      <figure>\n        <a href=${t.permalink}>\n\t\t  ${t.fileType.startsWith("video")?this.videoMarkup(t):""}\n          ${t.fileType.startsWith("audio")?this.audioMarkup(t):""}\n          ${t.fileType.startsWith("image")?this.imgMarkup(t):""}\n        </a>\n      </figure>\n      <a href=${t.permalink}>\n        <div class="card__info">\n          ${this.userNameMarkup(t)}\n          ${this.nftNameMarkup(t)}\n          <span class="meta-font">${e?.price}</span>\n          ${this.listedMarkup(t)}\n        </div>\n      </a>\n    </div>\n    `}clearRootEl(){this.rootEl.innerHTML=""}}(r,document.querySelector(`.smartnft_nft_grid_nfts_${r.unique_id}`)),n=new class{constructor(t){this.settings=t,this.category=null,this.collection=t?.collection_selector||null,this.nfts=[],this.limit=t.limit,console.log(t)}async fetchNfts(t){if(this.category===t||!t)return this.nfts;try{const e=await jQuery.ajax({type:"post",url:nft_grid_local.BACKEND_AJAX_URL,data:{limit:this.limit||12,cat_slug:t,coll_slugs:this.collection,action:"smartnft_get_nft_by_category"}});return this.category=t,this.nfts=e.data.nfts,this.nfts}catch(t){console.log(t)}}}(r),"yes"===r.isTabOn&&(o.renderCategories(),(()=>{const t=document.querySelectorAll(`.smartnft_nft_grid_categories_${r.unique_id} .nft-grid__category`);t.forEach(((e,r)=>e.addEventListener("click",(e=>{t.forEach((t=>{if(t==e.target)return t.classList.add("active");t.classList.remove("active")})),s(o.categories[r])}))))})());const i=r.category_selector[0];s(i)};window.SMARTNFT_NFT_GRID_RERUN_APP=()=>{"undefined"!=typeof smartnftNftGridElementSettings&&smartnftNftGridElementSettings.forEach((t=>{let e=0;requestAnimationFrame((function r(n){if(e<=n&&(e=n+300,void 0!==t)){const e=JSON.parse(t);if(document.querySelector(`.smartnft_nft_grid_categories_${e.unique_id}`))return void s(t)}requestAnimationFrame(r)}))}))},window.SMARTNFT_NFT_GRID_RERUN_APP()})()})();