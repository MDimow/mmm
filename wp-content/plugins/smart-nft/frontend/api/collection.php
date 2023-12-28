<?php

class SmartNftCollections 
{
    function __construct() {
    // Create collection
    add_action("wp_ajax_smartnft_create_collection",array($this,"smartnft_create_collection"),10);
    add_action("wp_ajax_nopriv_smartnft_create_collection",array($this,"smartnft_create_collection"),10);
    
    // Collection(singular)
    add_action( "wp_ajax_smartnft_get_collection_by_con_address",array( $this,"smartnft_get_collection_by_con_address" ), 10 );
    add_action( "wp_ajax_nopriv_smartnft_get_collection_by_con_address",array( $this,"smartnft_get_collection_by_con_address" ), 10 );

    add_action( "wp_ajax_smartnft_get_collection_by_coll_id",array( $this,"smartnft_get_collection_by_coll_id" ), 10 );
    add_action( "wp_ajax_nopriv_smartnft_get_collection_by_coll_id",array( $this,"smartnft_get_collection_by_coll_id" ), 10 );

    add_action( "wp_ajax_smartnft_get_collection_by_post_id",array( $this,"smartnft_get_collection_by_post_id" ), 10 );
    add_action( "wp_ajax_nopriv_smartnft_get_collection_by_post_id",array( $this,"smartnft_get_collection_by_post_id" ), 10 );
    
    add_action( "wp_ajax_smartnft_update_collection",array( $this,"smartnft_update_collection" ), 10 );
    add_action( "wp_ajax_nopriv_smartnft_update_collection",array( $this,"smartnft_update_collection" ), 10 );
    
    // user Collections
    add_action("wp_ajax_smartnft_get_user_collections", array($this,"smartnft_get_user_collections"), 10);
    add_action("wp_ajax_nopriv_smartnft_get_user_collections", array($this,"smartnft_get_user_collections"), 10);
    
    // Get nfts by collection
    add_action("wp_ajax_get_collections_nfts",array($this,"smartnft_get_nfts_by_collection"),10);
    add_action("wp_ajax_nopriv_get_collections_nfts",array($this,"smartnft_get_nfts_by_collection"),10);
    
    // Get collection info
    add_action("wp_ajax_get_collection_info",array($this,"smartnft_get_single_collection_info"),10);
    add_action("wp_ajax_nopriv_get_collection_info",array($this,"smartnft_get_single_collection_info"),10);
    
    //collection exist
    add_action("wp_ajax_collection_exist",array($this,"smartnft_collection_exist"),10);
    add_action("wp_ajax_nopriv_collection_exist",array($this,"smartnft_collection_exist"),10);
    
    //get All collections
    add_action("wp_ajax_get_all_collections",array($this,"smartnft_get_all_collections"),10);
    add_action("wp_ajax_nopriv_get_all_collections",array($this,"smartnft_get_all_collections"),10);
    
    //get All collections with total item
    add_action("wp_ajax_get_all_collections_with_total_item",array($this,"smartnft_get_all_collections_with_total_item"),10);
    add_action("wp_ajax_nopriv_get_all_collections_with_total_item",array($this,"smartnft_get_all_collections_with_total_item"),10);
	
	//get collection vloume and folur
    add_action("wp_ajax_smartnft_get_flour_volume_of_collection",array($this,"smartnft_get_flour_volume_of_collection"),10);
    add_action("wp_ajax_nopriv_smartnft_get_flour_volume_of_collection",array($this,"smartnft_get_flour_volume_of_collection"),10);
  }
  
  function smartnft_create_collection () {
      $payload = $_POST['collection'];
      $must_have_fields = [
            "name" => "No collection name is send",
            "creator" => "No creator is send",
            "contractAddress" => "No contract address is send",
      ];

      foreach( $must_have_fields as $key => $value ) {
            if( !isset( $payload[$key] ) || empty( $payload[ $key ] ) ){
              wp_send_json( ["message" => esc_html__( $value, WP_SMART_NFT ) ], 400);
            }
      }

    try{
        $name = sanitize_text_field( $payload["name"] );
        $exist = term_exists( trim( $name ), "smartnft_collection" );
        $creator = strtolower( $payload[ 'creator' ] );
        $contractAddress = strtolower( $payload[ 'contractAddress' ] );
        
        if( $exist == 0 || $exist == null ) { 

            $collection = wp_insert_term( $name, 'smartnft_collection');
            $term_id = $collection['term_id'];
            add_term_meta( $term_id, 'creator', $creator, true );
            add_term_meta( $term_id, 'contractAddress', $contractAddress, true );
            add_term_meta( $term_id, 'date', time(), true );
            add_term_meta( $term_id, 'isActive', "true", true );

            if(!empty($payload['profileImg']) && !empty( $payload['profileMimeType'] ) ) {
                //fn define in backend/utils/utils.php
                $payload['profileImg'] = smartnft_upload_image_to_media_library( $payload['profileImg'], 'profile', $payload["profileMimeType"] ); 
            }

            if( !empty( $payload['bannerImg'] )  && !empty( $payload['bannerMimeType'] ) ) {
                //fn define in backend/utils/utils.php
                $payload['bannerImg'] = smartnft_upload_image_to_media_library( $payload['bannerImg'], 'banner', $payload["bannerMimeType"] ); 
            }

            if( !empty( $payload['thumbImg'] )  && !empty( $payload['thumbMimeType'] ) ) {
                //fn define in backend/utils/utils.php
                $payload['thumbImg'] = smartnft_upload_image_to_media_library( $payload['thumbImg'], 'thumb', $payload["thumbMimeType"] ); 
            }

			$payload['date'] = time();

			//CORN JOB DATA( NECESSARY OR GIVES ERROR ON FIRST TIME CREATION )
			$payload["flour_price"] = 0;
			$payload["total_volume"] = 0;
			$payload["total_owners"] = 0;
			$payload["listed_amount"] = 0;
			$payload["unlisted_amount"] = 0;

			$stats = array();
			$type = array("mint" => 0, "mint_count" => 0,  "buy" => 0, "buy_count" => 0, "list" => 0, "list_count" => 0, "floor" => false, "owners" => array() );

			for( $i = 0; $i < 6; $i++ ) {
				$stats[] = $type;			
			}
			$payload["stats"] = $stats;
			$payload["prev_stats"] = $stats;

            add_term_meta( $term_id, 'collection_meta', $payload, true );

            wp_send_json(
                [ 
                    "status" => esc_html__("Collection created"), 
                    "data" => array(
                        "col_link" => get_term_link($term_id),
                        "coll"     => get_term( $term_id, "smartnft_collection" )
                    )
                ], 
                200
            );
        }

        wp_send_json([ "status" => esc_html__("Collection creation fail"), "data" => false ], 400);

    }catch(Exception $e) {
        wp_send_json([ "status" => false, "message" => $e->getMessage() ], 400 );
    }

  }

  function smartnft_update_collection(){
        if( !isset( $_POST[ "collection_info" ] ) || empty( $_POST[ "collection_info" ] ) ) {
			wp_send_json( false, 400 );
		}

        try{
            $collection = map_deep($_POST['collection_info'], 'sanitize_text_field');
            $tax_id = intval($collection['taxID']);
            
            $term = get_term( $tax_id, 'smartnft_collection' );

			if( is_wp_error( $term ) ) { wp_send_json([ "message" => esc_html__("coll not exist", WP_SMART_NFT) ], 400); }

			$term_meta = get_term_meta( $term->term_id, "collection_meta", true );

            if( !empty( $collection['collectionName'] ) ){
                $term_meta['name'] = $collection['collectionName'];
            }

            if( !empty( $collection['collectionDesc'] ) ){
                $term_meta['description'] = $collection['collectionDesc'];
            }

            if( !empty($collection['profileImg']) && !empty( $collection['profileMimeType'] ) ) {
				//fn define in backend/utils/utils.php
                $profile_url = smartnft_upload_image_to_media_library( $collection['profileImg'], 'profile', $collection["profileMimeType"] ); 
                $term_meta['profileImg'] = $profile_url;
                $term_meta['profileMimeType'] = $collection['profileMimeType'] ;
            }

            if( !empty( $collection['bannerImg'] )  && !empty( $collection['bannerMimeType'] )) {
 				//fn define in backend/utils/utils.php
                $banner_url = smartnft_upload_image_to_media_library( $collection['bannerImg'], 'banner', $collection["bannerMimeType"] );
                $term_meta['bannerImg'] = $banner_url;
                $term_meta['bannerMimeType'] = $collection['bannerMimeType'] ;
            }

            if( !empty( $collection['thumbImg'] )  && !empty( $collection['thumbMimeType'] )) {
 				//fn define in backend/utils/utils.php
                $banner_url = smartnft_upload_image_to_media_library( $collection['thumbImg'], 'banner', $collection["thumbMimeType"] );
                $term_meta['thumbImg'] = $banner_url;
                $term_meta['thumbMimeType'] = $collection['thumbMimeType'] ;
            }

			update_term_meta( $term->term_id, "collection_meta", $term_meta );
            wp_send_json( $term_meta, 200 );

        }catch( Exception $e ){
            wp_send_json([ "status" => false, "message" => $e->getMessage() ], 400);
        }
    }

	function smartnft_get_collection_by_con_address () {
		//check if contract address is send or not
		if( !isset( $_POST[ "conAddress" ] ) || empty( $_POST[ "conAddress" ] ) ) {
			wp_send_json( ["message" => esc_html__("Send contract address",WP_SMART_NFT)], 400 );
		}

		try {
            $contract_address  = sanitize_text_field( $_POST["conAddress"] );

			$terms = get_terms(array(
					'taxonomy'	=>	'smartnft_collection',
					'hide_empty'	=>	false,
					'meta_query'	=>	array( 
							array(
								'key'	=>	'contractAddress',
								'value' =>  strtolower( $contract_address ), 
								'compare'	=>	'='
							)
					)
			));
			wp_send_json( [ "status"  => true, "data"    => $terms ], 200);
			
		} catch( Exception $e ) {
			wp_send_json( ["status" => false, "message" => $e->getMessage()], 400);
		}
  }

	function smartnft_get_collection_by_coll_id () {
		if( !isset( $_POST[ "collId" ] ) || empty( $_POST[ "collId" ] ) ) {
			wp_send_json( ["message" => esc_html__("Send coll id",WP_SMART_NFT)], 400 );
		}

		try {
			$collId = sanitize_text_field( $_POST['collId'] );

			$term = get_term( $collId, 'smartnft_collection');

			if( is_wp_error( $term ) ) {
				wp_send_json( [ "status"  => "fail", "messahe" => esc_html__("Collection not exist.", WP_SMART_NFT) ], 400);
			}

			$term_meta = get_term_meta( $term->term_id, 'collection_meta', true );
			$term_meta['link'] = get_term_link( $term->term_id, "smartnft_collection" );

			wp_send_json( [ "status"  => esc_html__("Success",WP_SMART_NFT), "data" => array( "term_meta" => $term_meta, "term_data" => $term) ], 200);
			
		} catch( Exception $e ) {
			wp_send_json( ["status" => false, "message" => $e->getMessage()], 400);
		}
  }

    function smartnft_get_collection_by_post_id () {
		//check if contract address is send or not
		if( !isset( $_POST[ "postId" ] ) || empty( $_POST[ "postId" ] ) ) {
			wp_send_json( ["message" => esc_html__("Send contract address",WP_SMART_NFT)], 400 );
		}

		try {
			$postId = sanitize_text_field( $_POST['postId'] );

			$terms = get_the_terms( $postId, 'smartnft_collection' );

			if( empty( $terms ) ) {
				wp_send_json( [ "status"  => true, "data"    => $terms ], 200);
			}

			$term_meta = get_term_meta( $terms[0]->term_id, 'collection_meta' );
			$term_meta[0]['link'] = get_term_link( $terms[0]->term_id, "smartnft_collection" );

			wp_send_json( [ "status"  => true, "data"    => $term_meta[0] ], 200);

		} catch( Exception $e ) {
			wp_send_json( ["status" => false, "message" => $e->getMessage()], 400);
		}
  }


  function smartnft_get_user_collections() {
	$query = array(
		'taxonomy'   => 'smartnft_collection',
		'parent'     => 0,
		'hide_empty' => false,
		'meta_query' => array(
		    array(
			'key'   => 'creator',
			'value' => strtolower( sanitize_text_field($_POST["creator"] ) ),
			'compare' => '='
			),
    	 	), 
    	);

	if( !current_user_can("administrator") ) {
		$query['meta_query'][] = array(
						'key'=>	'isActive',
						'value' => 'false',
						'compare' => '!='
					);
	}

	$terms = get_terms( $query );

	$collections = array();

	foreach( $terms as $term ) {
		$meta =  get_term_meta( $term->term_id, "collection_meta" )[0];
		$collections[] = array( 
      "data" => $term, 
      "meta" => $meta,
      "permalink" => get_term_link( $term->term_id )
    );
	}

	wp_send_json(
		array(
		    "status"  => esc_html__("success", WP_SMART_NFT),
		    "data"      => array( "collections" => $collections  ),
      ),
		200
	);
  }


  public function smartnft_get_all_collections () {
		// if( !isset( $_POST[ "contract_addr" ] ) || empty( $_POST[ "contract_addr" ] ) ) {
		// 	wp_send_json( false, 400 );
		// }

    try{
      $contract_addr  = sanitize_text_field( $_POST["contract_addr"] );

      $terms = get_terms( 
        array(
          'taxonomy'   => 'smartnft_collection',
          'parent'     => 0,
          'hide_empty' => false,
          'orderby' => 'count',
          'order' => 'DESC',
          'meta_query' => array(
              // array(
              //     "key"     => "contract_add",
              //     "value"   =>  strtolower($contract_addr),
              //     "compare" => "="
              //     )
              ) 
          )
      );
  
      $collections = array();
      if( !empty($terms) ){
        foreach( $terms as $term ) {
            $data = array(
                "term_data" => $term,
                "term_meta" => get_term_meta( $term->term_id ),
                "term_link" => get_term_link(  $term->term_id  )
            );
            $collections[] =  $data;
        }
        wp_send_json(
          [
            "data" => [
              "collections" => $collections
            ]
          ],
          200
        );
      }else{
        wp_send_json(
          [
            "data" => [
              "collections" => []
            ]
          ],
          200
        );
      }
    }catch( Exception $e ){
        wp_send_json(
          [
            "message" => $e->getMessage()
          ],
          400
        );
    }
  }
  
  public function smartnft_get_all_collections_with_total_item () {
	$limit = intval($_POST['limit']);
	$offset = intval($_POST['offset']);
	$search = sanitize_text_field($_POST['search']);
	$query = array(
		'taxonomy'   => 'smartnft_collection',
		'hide_empty' => false,
		'orderby'    => 'count',
		'order'      => 'DESC',
		'meta_query' => array(),
		'offset'	=> 0,
		'limit'		=> 10,
		'search'	=> ''
	);

	if( !empty( $offset ) ){
		$query['offset'] = $offset;
	}
	if( !empty( $limit ) ){
		$query['number'] = $limit;
	}
	if( !empty( $search ) ){
		$query['search'] = $search;
	}
	if( !current_user_can("administrator") ) {
		$query['meta_query'][] = array(
				"relation" => "OR",
					array(
					'key'=>	'isActive',
					'value' => 'false',
					'compare' => '!='
					),
					array(
					'key'=>	'isActive',
					'compare' => 'NOT EXISTS'
					)
		);
	}

	$terms = get_terms( $query );
	
	$total_terms = wp_count_terms( array(
		'taxonomy'   => 'smartnft_collection',
		'hide_empty' => false,
		'search'	=> $search
	));

    $collections = array();

    foreach( $terms as $term ) {
        $data = array(
            "term_data" => $term,
            "term_meta" => get_term_meta( $term->term_id, "collection_meta", true ),
            "term_link" => get_term_link(  $term->term_id  ),
        );


        $collections[] =  $data;
    }

    if( is_wp_error($terms) ) {
        wp_send_json (
          [
            "status"  => esc_html__("success", WP_SMART_NFT),
            "message" => esc_html__("operation success full", WP_SMART_NFT),
            "data"    => [
              "collections" => []
            ]
          ],
          200
        );
    }

	wp_send_json(
			[
				"status"  => esc_html__("success", WP_SMART_NFT),
				"message" => esc_html__("operation success full", WP_SMART_NFT),
				"data"    => [
					"collections" => $collections,
					"total_collections" => $total_terms
				]
			],
			200
	);

  }

	public function smartnft_get_nfts_by_collection() {
		if( !isset( $_POST["tax_id"] ) || empty( $_POST["tax_id"] ) ) {
			wp_send_json (
				[
					"status" 	=> esc_html__("fail",WP_SMART_NFT ),
					"message" => esc_html__("Send collection id", WP_SMART_NFT)
				],
				400
			);
		}

		$tax_id 			 = sanitize_text_field( $_POST["tax_id"] );
		$offset 			 = sanitize_text_field( $_POST["offset"] );	
		$limit  			 = sanitize_text_field( $_POST["limit"] );	
		$contract_addr = sanitize_text_field( $_POST["contract_addr"] );	
		$price_range   = map_deep($_POST['priceRange'], 'sanitize_text_field');
		$status        = sanitize_text_field( $_POST['status'] );
		$price_order   = sanitize_text_field( $_POST['priceOrder'] );
	    $search_text   = sanitize_text_field( $_POST['search'] );

		try{
			//get owned user  nfts from database	
			$meta_query = array(
							array(
									'key'     => 'contract_addr',
									'value'   => strtolower($contract_addr),
									'compare' => '=',
							),
					);

      $tax_query = array(
              array(
                'taxonomy'	=> 'smartnft_collection',
                'field'	=> 'term_id',
                'terms'	=> $tax_id
              )
				);

      //
       if( !empty( $price_range ) ){
           $meta_query[] = array(
               'key'       => 'price',
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
         's'						  => $search_text,
         'posts_per_page' => intval( $limit ),
         'offset' 			  => intval( $offset ),
         'fields' 			  => 'ids',
         'meta_query'	    => $meta_query,	
         'tax_query'      => $tax_query  
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
              $post_meta = get_post_meta( get_the_ID(), 'smartnft_meta', false );
              $post_permalink = get_the_permalink();

              $post_meta[0]['permalink'] = $post_permalink;

              array_push( $all_nfts, $post_meta[0] );
          }
      }
			
			wp_send_json (
				[
					"status"  => esc_html__("success", WP_SMART_NFT),
					"message" => esc_html__("operation success full", WP_SMART_NFT),
					"data"    => [
						"nfts" => $all_nfts, 
						"total_post_found" => $the_query->found_posts 
					]
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

	public function smartnft_get_single_collection_info(){
		if(! isset($_POST["id"]) || empty($_POST["id"])){
			wp_send_json(
				[
					"message" => esc_html__("No nft id is sent", WP_SMART_NFT),
				],
				400
			);
		}
		try{
			$id = intval($_POST['id']);
			$collection_info = array();
			$terms = get_the_terms($id,'smartnft_collection' );

			if( $terms ){
				$collection_info['name']    = $terms[0]->name;
				$collection_info['url']     = get_term_link($terms[0]->slug,"smartnft_collection");
				$collection_info['status']  = true;
			}else{
				$collection_info['status'] = false;
			}
			wp_send_json(
				[
					"data"  => $collection_info
				],
				200
			);
		}catch( Exception $e ) {
			wp_send_json(
				[
					"message" => $e->getMessage()
				],
				400
			);
		}
	}

  public function smartnft_collection_exist () {
		if(! isset($_POST["collection_name"]) || empty($_POST["collection_name"])){
			wp_send_json(
				[
					"message" => esc_html__("No collection name is sent", WP_SMART_NFT),
				],
				400
			);
		}

    $name = sanitize_text_field( $_POST["collection_name"] );

    $exist = term_exists( $name, "smartnft_collection" );

    if( $exist == null || $exist == 0 ) {
        wp_send_json(
            [
					    "message" => esc_html__("Collection does not exist", WP_SMART_NFT),
              "data"    => false
            ],
            200
        );
        die();
    }

    wp_send_json(
        [
          "message" => esc_html__("Collection allready exist", WP_SMART_NFT),
          "data"    => true
        ],
        200
    );
    
  }

  function smartnft_get_flour_volume_of_collection () {
	if( !isset($_POST["collId"]) || empty($_POST["collId"] ) ){
		wp_send_json([ "message" => esc_html__("No collection id is sent", WP_SMART_NFT) ],400);
	}
	
	$collId = intval( $_POST["collId"] );
	$term = get_term( $collId, 'smartnft_collection' );

	if( is_wp_error( $term ) ) { wp_send_json([ "message" => esc_html__("coll not exist", WP_SMART_NFT) ], 400); }

	//post ids that use this term
	$post_ids = get_posts(
		array(
			'post_type' => 'smartnft',
			'tax_query' => array( array( 'taxonomy' => 'smartnft_collection', 'field' => 'term_id','terms'=> $collId ) ),
			'fields'	=> 'ids'
		)
	);

	//default value
	$flour_price = 0;
	$total_volume = 0;
	$total_owners = [];
	$listed_amount = 0;
	$unlisted_amount = 0;

	$term_meta = get_term_meta( $term->term_id, "collection_meta", true );

	//if collection is a 721 standard token
	if( $term_meta['standard'] == "Erc721" ) {
			foreach( $post_ids as $id ) {
				$price = get_post_meta( $id, 'price', true );
				$price = floatval( $price );
				$_owner = get_post_meta( $id, "owners", true );
				$is_listed = get_post_meta( $id, "isListed", true );
				if($is_listed == "true" ){ $listed_amount++; }else{ $unlisted_amount++; }
				$total_owners[ $_owner[0] ] = 1; // 1 just for dummy value
				$total_volume = $total_volume + $price ;

					if( $is_listed == "true" ) {
						//update flour price if cur price is less then previous flour price 
						if( $flour_price > $price || $flour_price == 0 ) { $flour_price = $price; }
					}
			}
	}

	//if collection is a 1155 standard token
	if( $term_meta['standard'] == "Erc1155" ) {
			foreach( $post_ids as $id ) {
				$owners = get_post_meta( $id, "smartnft_erc1155_token_owners", true );
				foreach( $owners as $key =>  $owner ) {
					$price = floatval( $owner['price'] );
					$amount = intval( $owner['amount'] );
					if( $owner['isListed'] == "true" ){ $listed_amount++; }else{ $unlisted_amount++; }
					$total_owners[ $key ] = 1; //1 just for dummy value
					$total_volume = $total_volume + ( $price * $amount );
					if( $owner['isListed'] == "true" ) {
						//update flour price if cur price is less then previous flour price 
						if($flour_price > $price || $flour_price == 0 ) { $flour_price = $price; }
					}
				}
			}	
	}

	wp_send_json( array( 
			"status" => "success", 
			"data"	=> array( 
					"flour_price" => $flour_price, 
					"total_volume" => $total_volume,
					"owners" => count( $total_owners ),
				    "listed_amount"	=> $listed_amount,	
				    "unlisted_amount"	=> $unlisted_amount	
			)
	),200);
  }


}

new SmartNftCollections();
