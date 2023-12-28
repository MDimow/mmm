<?php

class MergeBackend 
{
	
	function __construct() {
		$this->include_backend_files();
	}

    function include_backend_files () {
		include "api/storeNft.php";
		include "api/contract-address.php";
		include "api/store-txhash.php";
		include "api/store-likes.php";
		include "api/settings.php";
		include "api/top-collector.php";
		include "api/category.php";
		include "api/erc1155-owners.php";
		include "api/auction.php";
		include "api/bulk-minting.php";
		include "api/importer.php";
		include "api/vb.php";
		include "api/tools.php";
		include "api/users.php";
		// NFT filters
		include "api/filter-nfts.php";
		// Collections filter
		include "api/collection.php";
		// Loading widgest [elementor and others]
		include "inc/widgets.php";
		// Load Shortcodes
		include "inc/shortcode.php";
		// register post type
		include "inc/custom-post.php";
		include "inc/corn/corn-generate-coll-stats.php";
		include "inc/translation.php";
		//include "inc/support-mime.php";
		//update db and other stauf
		//include "inc/update/update.php";
	}
}

new MergeBackend();
