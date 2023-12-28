<?php

class Smartnft_Collection_Manager
{
    function __construct(){
		add_action("wp_ajax_smartnft_toggle_collection_visibility", array( $this, "toggle_collection_visibility" ) );
		add_action("wp_ajax_nopriv_smartnft_is_coll_verified", array( $this, "is_coll_verified" ) );
		add_action("wp_ajax_smartnft_is_coll_verified", array( $this, "is_coll_verified" ) );

		add_action("wp_ajax_nopriv_smartnft_add_or_update_collection_meta", array( $this, "add_or_update_collection_meta" ) );
		add_action("wp_ajax_smartnft_add_or_update_collection_meta", array( $this, "add_or_update_collection_meta" ) );
		add_action("wp_ajax_smartnft_delete_collection", array( $this, "smartnft_delete_collection" ) );
    }

	private function must_fields_error( $must_fields ) {
		foreach( $must_fields as $field ) {
			if( !$_POST[ $field ] || empty( $_POST[ $field ] ) ) {
				wp_send_json( array( "data" => esc_html__("Send proper information.", WP_SMART_NFT) ), 400 );
			}
		}
	}

	function add_or_update_collection_meta() {
		//check for necessary field
		$this->must_fields_error( array( "collId", "meta" ) );
		$collId = intval($_POST["collId"]);
		$meta = $_POST["meta"];

		$coll = get_term_meta( $collId, "collection_meta", true );
		foreach( $meta as $key => $value ) {
			$coll[$key] = $value;
		}

		update_term_meta( $collId, "collection_meta", $coll );

		wp_send_json( array( "data" => $coll ), 200 );
	}

	function is_coll_verified () {
		//check for necessary field
		$this->must_fields_error( array( "collId" ) );
		$collId = intval($_POST["collId"]);

        $exist = term_exists( $collId, "smartnft_collection" );
		if( $exist == 0 || $exist == null ) {
			wp_send_json( array( "data" => false ), 200 );
		}

		$meta = get_term_meta( $collId, "collection_meta", true );
		$is_verified = isset( $meta['verified'] ) ? $meta['verified'] : false ;

		wp_send_json( array( "data" => $is_verified ), 200 );
	}

	function smartnft_delete_collection () {
		//check for necessary field
		$this->must_fields_error( array( "collId" ) );

		$collId = intval($_POST["collId"]);
        $exist = term_exists( $collId, "smartnft_collection" );
		if( $exist == 0 || $exist == null ) {
			wp_send_json( array( "data" => esc_html__("Collection not exist", WP_SMART_NFT) ), 400 );
		}

		$this->toggle_nft_visibility_on_this_collection( $collId, true );
		$is_success = wp_delete_term( $collId, "smartnft_collection" );

		if( $is_success  ) {
			wp_send_json( array("data" => true), 200 );
		}else{
			$this->toggle_nft_visibility_on_this_collection( $collId, false );
			wp_send_json( array( "data" => false ), 400 );
		} 		
	}

	function toggle_collection_visibility () {
		//check for necessary field
		$this->must_fields_error( array( "collId", "isActive" ) );

		$collId = intval($_POST["collId"]);
		$isActive = $_POST["isActive"];

        $exist = term_exists( $collId, "smartnft_collection" );
		if( $exist == 0 || $exist == null ) {
			wp_send_json( array( "data" => esc_html__("Collection not exist", WP_SMART_NFT) ), 400 );
		}

		$meta = get_term_meta( $collId, "collection_meta", true );

		if( $isActive == "true" ) {
			//update collection meta
            update_term_meta( $collId, 'isActive', $isActive, false );
			//var_dump(get_term_meta( $collId, 'isActive',true ));
			$meta["isActive"] = "true";

			$this->toggle_nft_visibility_on_this_collection( $collId, false );
		}

		if( $isActive == "false" ) {
			//update collection meta
            update_term_meta( $collId, 'isActive', $isActive, false );
			$meta["isActive"] = "false";
			$this->toggle_nft_visibility_on_this_collection( $collId, true );
		}

		update_term_meta( $collId, "collection_meta", $meta );

		//var_dump(get_term_meta( $collId, 'isActive', false ));
		wp_send_json( array( "data" => array ( "success" => true ) ), 200 );

	}

	function toggle_nft_visibility_on_this_collection( $collId, $isMakeDraft ) {
		
			$post_ids = get_posts( array(
				'post_type'      => 'smartnft',
				'post_status'    => 'any',
				'orderby'        => 'date',
				'order'          => 'DESC', 
				'posts_per_page' => -1,
				'fields' 		 => 'ids',
				'tax_query'		 => array(
						array(
							'taxonomy' => 'smartnft_collection',
							'field'    => 'term_id',
							'terms'    =>  $collId,
						)
				)

			) );

			$post_status = $isMakeDraft ? "draft" : "publish";

			foreach( $post_ids as $id ) {
					$posts_data = array( "ID" => $id, "post_status" => $post_status );
					wp_update_post( $posts_data );
			}
	}
}


new Smartnft_Collection_Manager();
