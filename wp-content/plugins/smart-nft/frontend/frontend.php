<?php
class MergeFrontend 
{
	
	function __construct() {
		$this->include_frontend_files();
	}

	public function include_frontend_files () {
		include "inc/activity.php";
		include "inc/profile.php";
		include "inc/public-profile.php";
		include "inc/edit-profile.php";
		include "inc/collection.php";
		include "inc/category.php";
		include "inc/create-collection.php";
		include "inc/all-nft.php";
		include "inc/all-collection.php";
		include "inc/single-nft.php";
		include "api/save-profile.php";
		include "api/get-profile.php";
		include "api/collection.php";
		include "api/dynamic-css.php";
		include "api/userNft.php";
		include "api/filter-category-nfts.php";
		include "api/verify.php";
		// Theme Support
		include "integration/header.php";

	}
}

new MergeFrontend();

