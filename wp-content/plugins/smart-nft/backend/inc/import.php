<?php

class Smartnft_Import {
	function __construct() {
		//add_action("init", array($this, "delete_all_nfts"), 10);
		//register_activation_hook( PLUGIN_ROOT . "smart-nft.php", [ $this, "delete_all_nfts" ] );
	}

	function import_nft() {
	 //read json file content
	 $data = wp_remote_get("https://ivory-hidden-alligator-385.mypinata.cloud/ipfs/QmPLfDs9qa5MUiiJNEuHidrJNyPoa77SvoUgL7B4C9nexU");
	 if( is_wp_error( $data ) ) {
		var_dump( $data );
	 }

	 $data_array = json_decode($data["body"], true);


	 foreach( $data_array as $newNft ) {
		$args = array(
			'post_title' 	=> $newNft['meta']['name'],
			'post_type'		=> 'smartnft',
			'post_status'	=> 'publish',
			'meta_input'	=> array(
				'smartnftData'   	  => $newNft,
				'tokenId'			  => intval($newNft['tokenId']),
				'contractAddress'	  => strtolower( $newNft['contractAddress'] ),
				'owners'			  => $newNft["owners"] ,
				'creator'			  => strtolower( $newNft["creator"] ),
				'isListed'			  => $newNft["isListed"],
				'price'			  	  => $newNft["price"],
				'priceInWei'		  => $newNft["priceInWei"],
				'standard'			  => $newNft["standard"],
				'chainId'			  => $newNft["selectedContract"]["network"]["chainId"],
				'auction'			  => $newNft["auction"]["isAuctionSet"]
			)
		);

		$id = wp_insert_post( $args );

		if( !empty( $newNft['category']['name'] ) ){
			$term_category = term_exists( $newNft['category']['name'], 'smartnft_category' );
			wp_set_object_terms( $id, (int)$term_category['term_id'], 'smartnft_category' );
		}
		if( !empty( $newNft['collection']['name'] ) ){
			$term_collection = term_exists( $newNft['collection']['name'], 'smartnft_collection' );
			wp_set_object_terms( $id, (int)$term_collection['term_id'], 'smartnft_collection' );
		}
		update_post_meta( $id, 'nft_views', 1 );

	 }

	}


	function delete_all_nfts() {
			$ids = get_posts(array(
					"post_type"	  => "smartnft",
					"numberposts" => -1,
					"fields"	  => "ids"
			));


			foreach( $ids as $id ) {
				wp_delete_post( $id, true );
			}
	}
}

//new Smartnft_Import();


