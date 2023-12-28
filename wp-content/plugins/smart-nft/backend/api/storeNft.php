<?php
class StoreNft 
{
	function __construct() {
		//store
		add_action("wp_ajax_store_nft",array($this,"smartnft_store_nft"),10);
		add_action("wp_ajax_nopriv_store_nft",array($this,"smartnft_store_nft"),10);
		
		// Upload NFT WP Media
		add_action("wp_ajax_upload_nft_wpmedia",array( $this,"smartnft_upload_nft_wpmedia"), 10 );
		add_action("wp_ajax_nopriv_upload_nft_wpmedia",array( $this,"smartnft_upload_nft_wpmedia"), 10 );

		//get
		add_action("wp_ajax_smartnft_get_nfts",array($this,"smartnft_get_nfts"),10);
		add_action("wp_ajax_nopriv_smartnft_get_nfts",array($this,"smartnft_get_nfts"),10);

		
		add_action("wp_ajax_get_nfts_by_user",array($this,"smartnft_get_nfts_by_user"),10);
		add_action("wp_ajax_nopriv_get_nfts_by_user",array($this,"smartnft_get_nfts_by_user"),10);
		
		add_action("wp_ajax_smartnft_get_nft_by_post_id",array($this,"smartnft_get_nft_by_post_id"),10);
		add_action("wp_ajax_nopriv_smartnft_get_nft_by_post_id",array($this,"smartnft_get_nft_by_post_id"),10);
		

		//get nfts by category
		add_action("wp_ajax_smartnft_get_nft_by_category",array($this,"smartnft_get_nft_by_category"), 10);
		add_action("wp_ajax_nopriv_smartnft_get_nft_by_category",array($this,"smartnft_get_nft_by_category"), 10);
		
		//update
		add_action("wp_ajax_smartnft_update_nft_meta",array($this,"smartnft_update_nft_meta"),10);
		add_action("wp_ajax_nopriv_smartnft_update_nft_meta",array($this,"smartnft_update_nft_meta"),10);
		
		//visibility
		add_action("wp_ajax_change_nft_visibility",array($this,"smartnft_change_nft_visibility"),10);
		add_action("wp_ajax_nopriv_change_nft_visibility",array($this,"smartnft_change_nft_visibility"),10);

		//delete 
		add_action( "wp_ajax_smartnft_delete_nft", array( $this, "smartnft_delete_nft" ), 10 );
		add_action( "wp_ajax_nopriv_smartnft_delete_nft", array( $this, "smartnft_delete_nft" ), 10 );
		
		//Upload unlockable file
		add_action( "wp_ajax_unlockable_upload", array( $this, "smartnft_upload_unlockable_files" ), 10 );
		add_action( "wp_ajax_nopriv_unlockable_upload", array( $this, "smartnft_upload_unlockable_files" ), 10 );
		
		// Update views
		add_action( "wp_ajax_smartnft_update_views", array( $this, "smartnft_update_views" ), 10 );
		add_action( "wp_ajax_nopriv_smartnft_update_views", array( $this, "smartnft_update_views" ), 10 );

	}
	public function smartnft_update_views(){
		$post_id = intval( $_POST["post_id"] );
		$views = intval( $_POST["views"] );

		try{
			if( empty( $post_id ) ) {
				wp_send_json([ "status"  => esc_html__("fail",WP_SMART_NFT) , "message" => esc_html__("no post id", WP_SMART_NFT)], 400);
			}
			if( empty( $views ) ) {
				wp_send_json([ "status"  => esc_html__("fail",WP_SMART_NFT) , "message" => esc_html__("no view count", WP_SMART_NFT)], 400);
			}

			$meta = update_post_meta( $post_id, 'nft_views', $views );
			wp_send_json([ "data"    => $meta ],200);

		}catch (Exception $e) {
			wp_send_json(["status" => esc_html__("fail", WP_SMART_NFT),"message" => $e->getMessage() ],400);
		}
	}
	public function smartnft_upload_unlockable_files(){
		$posted_data =  isset( $_POST ) ? $_POST : array();
	    $file_data = isset( $_FILES ) ? $_FILES : array();
	    $data = array_merge( $posted_data, $file_data );
	    $response = array();
		$fileErrors = array(
	        0 => "There is no error, the file uploaded with success",
	        1 => "The uploaded file exceeds the upload_max_files in server settings",
	        2 => "The uploaded file exceeds the MAX_FILE_SIZE from html form",
	        3 => "The uploaded file uploaded only partially",
	        4 => "No file was uploaded",
	        6 => "Missing a temporary folder",
	        7 => "Failed to write file to disk",
	        8 => "A PHP extension stoped file to upload" 
	    );
		// File upload
		$attachment_id = media_handle_upload( 'upload_file', 0 );
	        
		if ( is_wp_error( $attachment_id ) ) { 
			$response['response'] = "ERROR";
			$response['error'] = $fileErrors[ $data['upload_file']['error'] ];
		} else {
			$fullsize_path = get_attached_file( $attachment_id );
			$pathinfo = pathinfo( $fullsize_path );
			$url = wp_get_attachment_url( $attachment_id );
			$response['response'] = "SUCCESS";
			$response['filename'] = basename( get_attached_file( $attachment_id ) );
			$response['filesize'] = filesize( get_attached_file( $attachment_id ) );
			$response['time'] = date( 'H:i d-m-Y', current_time( 'timestamp', 1 ) );
			$response['id'] = $attachment_id;
			$response['url'] = $url;
		}

		wp_send_json( $response, 200 );
	}
	public function smartnft_upload_nft_wpmedia () {
		//$posted_data =  isset( $_POST ) ? $_POST : array();
		//$file_data = isset( $_FILES ) ? $_FILES : array();
		////$data = array_merge( $posted_data, $file_data );

		
		$settings = get_option("smartnft_settings",[]);
		$quality = $settings['thumbQuality'] != '' ? 'medium' : $settings['thumbQuality'];

		$attach_id = media_handle_upload( 'upload_file', 0 );
		$attach_url = wp_get_attachment_image_url( $attach_id, $quality );

		$res = [
			'attach_id' 	=> $attach_id,
			'attach_url' 	=> $attach_url,
		];

		wp_send_json( $res, 200 );
	}

	public function smartnft_store_nft () {
		if(! isset($_POST["nft"]) || empty($_POST["nft"])){
			wp_send_json(
				[
					"message" => esc_html__("No nft is send. Please send valid nfts", WP_SMART_NFT),
				],
				400
			);
		}

		try{
			//new nft form user
			$newNft = $_POST["nft"];
			$tokenId = strlen($newNft["tokenId"]) > 10 ? $newNft["tokenId"] : intval($newNft["tokenId"]); 

			//gurd check if nft already present then dont store
			$posts = get_posts( array(
				'post_type'		=> 'smartnft',
				'post_status'	=> 'publish',
              	'fields' 		=> 'ids',
				'meta_query'	=> array(
						array( "key" => "tokenId", "value" => $tokenId, "compare" => "=" ),
						array( "key" => "contractAddress", "value" => strtolower( $newNft['contractAddress'] ), "compare" => "=" ),
				)	
			) );

			if( !empty( $posts ) ) {
				wp_send_json("post already exist", 200);
				die();
			}

			//save new nfts to the database
			$args = array(
				'post_title' 	=> $newNft['meta']['name'],
				'post_type'		=> 'smartnft',
				'post_status'	=> 'publish',
				'meta_input'	=> array(
					'smartnftData'   	  => $newNft,
					'tokenId'			  => $tokenId,
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

			if( !empty( $newNft['category'] ) ){
				$term_category = term_exists( $newNft['category']['name'], 'smartnft_category' );
				wp_set_object_terms( $id, (int)$term_category['term_id'], 'smartnft_category' );
			}
			if( !empty( $newNft['collection'] ) ){
				$term_collection = term_exists( $newNft['collection']['name'], 'smartnft_collection' );
				wp_set_object_terms( $id, (int)$term_collection['term_id'], 'smartnft_collection' );
			}

			update_post_meta( $id, 'nft_views', 1 );

			wp_send_json( 
				[ 
					"id"  => $id,
					"permalink" => get_post_permalink( $id ),
				],
				200 
			);

		}catch( Exception $e ) {
			wp_send_json( [ "status" => esc_html__("fail", WP_SMART_NFT), "message" => $e->getMessage() ], 400);
		}

	}

	public function smartnft_get_nfts () {

		$offset = sanitize_text_field( $_POST["offset"] );	
		$limit  = sanitize_text_field( $_POST["limit"] );	

		try{
			//get all other nfts from database	
			$args = [
				'post_type'      => 'smartnft',
				'orderby'        => 'date',
				'order'          => 'DESC', 
				'posts_per_page' => intval( $limit ),
				'offset' 		 => intval( $offset ),
				'fields' 		 => 'ids',
			];

			if( current_user_can('administrator') ){
				$args['post_status'] = '';
			}

			if( !current_user_can('administrator') ){
				$args['post_status'] = 'publish';
			}

			$the_query = new WP_Query( $args );

			$all_nfts = array();

    		if( $the_query->have_posts() ) {
    		    while ( $the_query->have_posts() ) {
    		        $the_query->the_post();
    		        $post_meta = get_post_meta( get_the_ID(), 'smartnftData', true );
    		        $post_permalink = get_the_permalink();

    		        $post_meta['permalink'] = $post_permalink;
    		        $post_meta['post_status'] = get_post_status();
    		        $post_meta['post_id'] = get_the_ID();

    		        array_push( $all_nfts, $post_meta );
    		    }
    		}

			wp_send_json(
				[
					"status"  => esc_html__("success", WP_SMART_NFT),
					"data"    => [ "nfts" => $all_nfts, "total_post_found" => $the_query->found_posts ]
				],
				200
			);

		}catch (Exception $e) {
			wp_send_json(
				[
					"status" => esc_html__("fail", WP_SMART_NFT),
					"message" => $e->getMessage()
				],
				400
			);
		}
	}

	public function smartnft_get_nft_by_post_id () {
		$post_id = intval( $_POST["post_id"] );

		try{
			if( empty( $post_id ) ) {
				wp_send_json([ "status"  => esc_html__("fail",WP_SMART_NFT) , "message" => esc_html__("no post id", WP_SMART_NFT)], 400);
			}

			$meta = get_post_meta( $post_id, "smartnftData", false );
			$meta[0]['views'] = get_post_meta( $post_id, 'nft_views', true );
			wp_send_json([ "data"    => $meta[0] ],200);

		}catch (Exception $e) {
			wp_send_json(["status" => esc_html__("fail", WP_SMART_NFT),"message" => $e->getMessage() ],400);
		}
	}


	public function smartnft_get_nfts_by_user () {
		$all_nfts = array();

		$offset = sanitize_text_field($_POST["offset"]);	
		$limit  =  sanitize_text_field($_POST["limit"]);	
		$contract_addr = sanitize_text_field($_POST["contract_addr"]);	
		$owner = sanitize_text_field($_POST["owner"]);	

		try{
			//get all other nfts from database	
			$nfts = get_posts([
				'post_type'      => 'smartnft',
				'post_status'    => 'publish',
				'orderby'        => 'date',
				'order'          => 'DESC', 
				'posts_per_page' => intval( $limit ),
				'offset' 				 => intval( $offset ),
				'fields' 			   => 'ids',
				'meta_query'	   => array(
					array(
							'key'   => 'contract_addr',
							'value' => strtolower($contract_addr),
							'compare' => '='
					),
					array(
							'key'   => 'owner',
							'value' => strtolower($owner),
							'compare' => '='
					),
				 )
			] );

			$post_count = count($nfts);
			
			foreach( $nfts as $nft ){
				$post = get_post_meta( $nft, 'smartnft_meta', false );
				$post_permalink = get_the_permalink( $nft );

				$post[0]['permalink'] = $post_permalink;

				array_push( $all_nfts, $post[0] );
			}


			wp_send_json(
				[
					"data"    => ["nfts" => $all_nfts, "total_post_found" => $post_count ]
				],
				200
			);

		}catch (Exception $e) {
			wp_send_json(
				[
					"message" => $e->getMessage()
				],
				400
			);
		}
	}
	

	public function smartnft_update_nft_meta () {
		if(	!isset( $_POST["postId"] ) || empty( $_POST["postId"] ) ){
			wp_send_json([ "status" => esc_html__("fail", WP_SMART_NFT)], 400);
		}

		try{
			$post_id = intval( $_POST["postId"] );
			$meta = get_post_meta( $post_id, "smartnftData" )[0];

			$new_meta = $_POST['newMeta'];
			$new_direct_meta = $_POST['newDirectMeta'];

			foreach( $new_meta as $key => $value ) {
				$meta[ $key ] = sanitize_texts_field( $value );
			}

			//update the new  meta
			update_post_meta( $post_id, 'smartnftData', $meta );

			//update any direct single value if send
			//this values gets their own key on meta data
			if( !empty( $new_direct_meta ) ) {
				foreach( $new_direct_meta as $key => $value ) {
					update_post_meta( $post_id, $key, $value );
				}
			}

			wp_send_json([ "status" => esc_html__("Success", WP_SMART_NFT), "data" => $meta ], 200);
							
		}catch( Exception $e ) {
			wp_send_json([ "message" => $e->getMessage() ], 400);
		}

	}
	public function smartnft_change_nft_visibility(){
		$id = intval( $_POST["nft_id"] );
		$visibility = sanitize_text_field( $_POST["visibility"] );

		try{
			if(empty($id)) {
				wp_send_json(
					[
						"msg"  => esc_html__('No nft id is sent', WP_SMART_NFT),
					],
					400
				);
				
			}

			wp_update_post( 
				array(
					'ID' => $id,
					'post_status' => $visibility
				) 
			);

			wp_send_json(
				[
					"data"    => true
				],
				200
			);

		}catch (Exception $e) {
			wp_send_json(
				[
					"message" => $e->getMessage()
				],
				400
			);
		}
	}

	function smartnft_delete_nft () {
		if(! isset($_POST["postId"]) || empty($_POST["postId"])){
			wp_send_json([ "message" => esc_html__("No nft id send.", WP_SMART_NFT), ], 400);
		}

		try{
			wp_delete_post( sanitize_text_field( $_POST["postId"] ), true );
			wp_send_json(
				[
					"status" => esc_html__("Success", WP_SMART_NFT),
					"message" => esc_html__("Nft deleted success fully.", WP_SMART_NFT)
				],
				200
			);

		}catch( Exception $e ) {
			wp_send_json( [ "status" => esc_html__("fail", WP_SMART_NFT),"message" => $e->getMessage() ], 400);
		}
	} 

	function smartnft_get_nft_by_category () {
		if( !isset( $_POST["cat_slug"] ) || empty( $_POST["cat_slug"] ) ) {
			wp_send_json( [ "status" => esc_html__("fail", WP_SMART_NFT), "message" => esc_html__( "Send category slug", WP_SMART_NFT ) ], 400 );
		}
		
		try{
			$limit  = sanitize_text_field( $_POST["limit"] );	
			$slug = sanitize_text_field( $_POST["cat_slug"] );
			$coll_slugs = isset( $_POST["coll_slugs"] ) && !empty( $_POST["coll_slugs"] ) ? map_deep( $_POST["coll_slugs"], 'sanitize_text_field' ) : "";

			$args = [
				'post_type'      => 'smartnft',
				'orderby'        => 'date',
				'order'          => 'DESC', 
				'post_status'	 => 'publish',
				'posts_per_page' => intval( $limit ),
				'fields' 		 => 'ids',
				'tax_query'		 => array(),
			];


			$cat_tax = array( 'taxonomy' => 'smartnft_category', 'field' => 'slug', 'terms' => $slug );
			$coll_tax = array( 'taxonomy' => 'smartnft_collection', 'field' => 'slug', 'terms' => $coll_slugs );

			if( !empty( $coll_slugs ) ) {
				$args["tax_query"][] = $coll_tax;
			}

			if( $slug !== "all" ) {
					$args['tax_query'][] = $cat_tax;
			}

			$the_query = new WP_Query( $args );

			$all_nfts = array();
			$settings = get_option("smartnft_settings",[]);
			$quality = $settings['thumbQuality'] == '' ? 'medium' : $settings['thumbQuality'];
		
      if( $the_query->have_posts() ) {
          while ( $the_query->have_posts() ) {
              $the_query->the_post();
              $post_meta = get_post_meta( get_the_ID(), 'smartnftData', true );
              $post_permalink = get_the_permalink();

              $post_meta['permalink'] = $post_permalink;
              $post_meta['post_status'] = get_post_status();
              $post_meta['post_id'] = get_the_ID();
			  $post_meta['creator_name'] = get_option('profile_' . $post_meta["creator"], true)['name'];
			  $post_meta['is_creator_verified'] = get_option('profile_' . $post_meta["creator"], true)['verified'];
			  $thumbnail_id = $post_meta['thumbnailMediaUrl']['attach_id'];
			  $thumbnail_url = wp_get_attachment_image_url( (int)$thumbnail_id, $quality);
			  $post_meta['permalink'] = $post_permalink;
			  $post_meta['thumbnailMediaUrl']['attach_url'] = $thumbnail_url ? $thumbnail_url : $post_meta["thumbnailMediaUrl"]["attach_url"];

              array_push( $all_nfts, $post_meta );
          }
      }

			wp_send_json(
				[
					"status"  => esc_html__("success", WP_SMART_NFT),
					"data"    => [
						"nfts" => $all_nfts, 
						"total_post_found" => $the_query->found_posts 
					]
				],
				200
			);
					


		}catch(Exception $e) {
			wp_send_json(
				[
					"status" => esc_html__("fail", WP_SMART_NFT),
					"message" => $e->getMessage()
				],
				400
			);

		}

	}
}
new StoreNft();
