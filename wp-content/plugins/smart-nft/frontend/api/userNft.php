<?php

class SmartNft_User_Nfts 
{

	function __construct () {
		add_action( "wp_ajax_get_user_owned_nft",array( $this,"smartnft_get_user_owned_nft" ), 10 );
		add_action( "wp_ajax_nopriv_get_user_owned_nft",array( $this,"smartnft_get_user_owned_nft" ), 10 );
	}

	function smartnft_get_user_owned_nft () {
		if( !isset( $_POST["accAdd"] ) || empty( $_POST["accAdd"] ) ) {
			wp_send_json (
				[
					"status" 	=> esc_html__("fail",WP_SMART_NFT ),
				],
				400
			);
		}

		$userAdd 			= sanitize_text_field( $_POST["accAdd"] );
		$offset 			= sanitize_text_field($_POST["offset"]);	
		$limit  			= sanitize_text_field($_POST["limit"]);	
		$price_range   		= map_deep($_POST['priceRange'], 'sanitize_text_field');
		$status       		= sanitize_text_field($_POST['status']);
		$price_order   		= sanitize_text_field($_POST['priceOrder']);
    	$search_text   		= sanitize_text_field($_POST['search']);
		$chainId 			= $_POST['chainId'];

		try{
			//get owned user  nfts from database	
			$meta_query = array();

			$owner_meta_query = array(
					'key'     => 'owners',
					'value'   => serialize(strtolower($userAdd)),
					'compare' => 'LIKE'
					);
			if( !empty($chainId) ){				
				$owner_meta_query = array(
					'key'     => 'chainId',
					'value'   => $chainId,
					'compare' => '='
				);
			}
			$creator_meta_query = array(
					'key'     => 'creator',
					'value'   => strtolower($userAdd),
					'compare' => '='
					);
			if( !empty($chainId) ){				
				$creator_meta_query = array(
					'key'     => 'chainId',
					'value'   => $chainId,
					'compare' => '='
				);
			}
		//filter
		if( isset($_POST["tab"]) && $_POST["tab"] == "ALL_TAB" ) {
				$meta_query[] = array (
					"relation"	=> "OR",
					$owner_meta_query,
					$creator_meta_query
				 	);
		}

		if( isset($_POST["tab"]) && $_POST["tab"] == "OWNED" ) {
				$meta_query[] = $owner_meta_query;
		}

		if( isset($_POST["tab"]) && $_POST["tab"] == "CREATED" ) {
				$meta_query[] = $creator_meta_query;
		}

		if( !empty( $price_range ) ){
				$meta_query[] = array(
						'key'       => 'priceInWei',
						'value'     => $price_range,
						'type'      => "numeric",
						'compare'   => 'BETWEEN'
				);
		}

		if( !empty( $status ) ){
				$meta_query[] = array(
						'key'       => 'isListed',
						'value'     => $status,
						'compare'   => '='
				);
		}

		$args = array (
			'post_type'      => 'smartnft',
			'post_status'    => 'publish',
			's'				 => $search_text,
			'posts_per_page' => intval( $limit ),
			'offset' 		 => intval( $offset ),
			'fields' 		 => 'ids',
			'meta_query'	 => $meta_query	
		);

		if( !empty( $price_order ) ) {
			switch( $price_order ) {
			case 'priceasc' :
					$args['meta_key']   = 'price';
					$args['orderby']    = 'meta_value_num';
					$args['order']      = 'ASC';
					break;
			case 'pricedesc' :
					$args['meta_key']   = 'price';
					$args['orderby']    = 'meta_value_num';
					$args['order']      = 'DESC';
					break;
			default:
			}
		}

			$the_query = new WP_Query( $args );

			$all_nfts = array();

      if( $the_query->have_posts() ) {
          while ( $the_query->have_posts() ) {
              $the_query->the_post();
              $post_meta = get_post_meta( get_the_ID(), 'smartnftData', false );
              $post_permalink = get_the_permalink();

              $post_meta[0]['permalink'] = $post_permalink;

              array_push( $all_nfts, $post_meta[0] );
          }
      }



			wp_send_json (
				[
					"data"    => [
						"nfts" => $all_nfts, 
						"total_post_found" => $the_query->found_posts 
					]
				],
				200
			);

		}catch( Exception $e ) {
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

new SmartNft_User_Nfts();
