<?php

class Smartnft_Category {
	function __construct() {
    //create category
		add_action("wp_ajax_smartnft_create_category", array( $this, "smartnft_create_category" ), 10);
		add_action("wp_ajax_nopriv_smartnft_create_category", array( $this, "smartnft_create_category" ), 10);
    
    //category exist
		add_action("wp_ajax_smartnft_category_exist", array( $this, "smartnft_category_exist"), 10);
		add_action("wp_ajax_nopriv_smartnft_category_exist", array( $this, "smartnft_category_exist"), 10);

    //get All categories
		add_action("wp_ajax_smartnft_get_all_categories", array($this,"smartnft_get_all_categories"),10);
		add_action("wp_ajax_nopriv_smartnft_get_all_categories", array($this,"smartnft_get_all_categories"),10);

    //get All categories
		add_action("wp_ajax_smartnft_get_all_categories_name_only", array($this,"smartnft_get_all_categories_name_only"),10);
		add_action("wp_ajax_nopriv_smartnft_get_all_categories_name_only", array($this,"smartnft_get_all_categories_name_only"),10);

    //get single category info categories
		add_action("wp_ajax_get_category", array($this,"smartnft_get_category"),10);

    // Update category
		add_action("wp_ajax_update_category", array($this,"smartnft_update_category"),10);
    // delete category
		add_action("wp_ajax_delete_category", array($this,"smartnft_delete_category"),10);
	}
  function smartnft_get_category () {
    //check if account is send or not
    if( !isset( $_POST[ "slug" ] ) || empty( $_POST[ "slug" ] ) ) {
      wp_send_json( false, 400 );
    }

    try {
            $tax_id         = intval( $_POST[ "slug" ] );

            $term = get_term( $tax_id );

            // category Data
            $profile_img = get_term_meta( $tax_id, 'profile_image', false );
            $cover_img = get_term_meta( $tax_id, 'cover_image', false );

            $response = array(
                'name'             => $term->name,
                'description'      => get_term_meta($tax_id, 'description',true ),
                'profileImg'    => $profile_img,
                'coverImg' => $cover_img,
            );

      wp_send_json(
        [
          "status"  => true,
          "data"    => $response
        ],
        200
      );
      
    } catch( Exception $e ) {
        wp_send_json(
          [
            "status" => false,
            "message" => $e->getMessage()
          ],
          400
        );
    }

  }
  function smartnft_create_category () {
		if(! isset($_POST["name"]) || empty($_POST["name"] ) ) {
			wp_send_json( [ "message" => esc_html__("No category name is sent", WP_SMART_NFT) ], 400 );
        }

		if(! isset($_POST["accAdd"]) || empty($_POST["accAdd"] ) ){
			wp_send_json(["message" => esc_html__("No creator is sent", WP_SMART_NFT) ], 400 );
        }


    try{
        $description = "";
        $banner_img = "";
        $profile_img = "";


        $name = sanitize_text_field( $_POST["name"] );
        $creator = sanitize_text_field( $_POST["accAdd"] );

        if(!empty ( $_POST["description"] )) {
            $description = sanitize_text_field( $_POST["description"] );
        }

        if(!empty ( $_POST["bannerImg"] ) && !empty( $_POST["bannerMimeType"] ) ) {
            $banner_img = smartnft_upload_image_to_media_library( $_POST['bannerImg'], 'banner', $_POST["bannerMimeType"] );
        }

        if( !empty ( $_POST["profileImg"] ) && !empty ( $_POST["profileMimeType"] )  ) {
            $profile_img = smartnft_upload_image_to_media_library( $_POST['profileImg'], 'profile', $_POST["profileMimeType"] ); 
;
        }

        $exist = term_exists( trim( $name ), "smartnft_category" );
        
        if( $exist == 0 || $exist == null ) { 
            $collection = wp_insert_term( $name, 'smartnft_category');
            $term_id = $collection['term_id'];
            add_term_meta( $term_id, 'creator', strtolower( $creator ), true );
            add_term_meta( $term_id, 'description', $description, true );
            add_term_meta( $term_id, 'cover_image', $banner_img , true );
            add_term_meta( $term_id, 'profile_image', $profile_img , true );

            wp_send_json([ "status" => esc_html__("Category created"), "data" => true ], 200);
        }
        wp_send_json([ "status" => esc_html__("Category creation fail"), "data" => false ], 400);

    }catch(Exception $e) {
        wp_send_json( [ "status" => false, "message" => $e->getMessage() ], 400 );
    }

  }

	function smartnft_category_exist () {
		if(! isset($_POST["category_name"]) || empty($_POST["category_name"])){
			wp_send_json(
				[
					"message" => esc_html__("No category name is sent", WP_SMART_NFT),
				],
				400
			);
		}

    $name = sanitize_text_field( $_POST["category_name"] );

    $exist = term_exists( $name, "smartnft_category" );

    if( $exist == null || $exist == 0 ) {
        wp_send_json(
            [
					    "message" => esc_html__("Category does not exist", WP_SMART_NFT),
              "data"    => false
            ],
            200
        );
        die();
    }

    wp_send_json(
        [
          "message" => esc_html__("Category allready exist", WP_SMART_NFT),
          "data"    => true
        ],
        200
    );
		
	}

	function smartnft_delete_category () {
		if(! isset($_POST["taxID"]) || empty($_POST["taxID"])){
			wp_send_json(
				[
					"message" => esc_html__("No category is sent", WP_SMART_NFT),
				],
				400
			);
		}

    $tax_id = intval( $_POST["taxID"] );
    $delete = wp_delete_term( $tax_id, 'smartnft_category' );
    if( $delete ) {
        wp_send_json(
            [
					    "message" => esc_html__("Category deleted successfully", WP_SMART_NFT),
            ],
            200
        );
        die();
    }else{
      wp_send_json(
          [
            "message" => esc_html__("Category delete failed", WP_SMART_NFT),
          ],
          400
      );
    }
	}
  function smartnft_update_category(){
    if( !isset( $_POST[ "category_info" ] ) || empty( $_POST[ "category_info" ] ) ) {
      wp_send_json( false, 400 );
    }
        try{
            $collection = map_deep($_POST['category_info'], 'sanitize_text_field');
            $tax_id = intval($collection['taxID']);
            
            $update_args = [];

            if( !empty( $collection['categoryName'] ) ){
                $update_args['name'] = $collection['categoryName'];
            }
            if( !empty( $collection['categoryDesc'] ) ){
                $update_args['description'] = $collection['categoryDesc'];
                update_term_meta($tax_id, "description", $collection["categoryDesc"]);
            }

            if( !empty( $update_args ) ){
                $update = wp_update_term(
                    $tax_id,
                    'smartnft_category',
                    $update_args
                );
            }

            if(!empty($collection['profileImg']) && !empty( $collection['profileMimeType'] ) ) {
                $profile_url = smartnft_upload_image_to_media_library( $collection['profileImg'], 'profile', $collection["profileMimeType"] ); //fn define in backend/utils/utils.php
                update_term_meta( $tax_id, 'profile_image', $profile_url, false );
            }

            if( !empty( $collection['coverImg'] )  && !empty( $collection['bannerMimeType'] )) {
                $banner_url = smartnft_upload_image_to_media_library( $collection['coverImg'], 'banner', $collection["bannerMimeType"] ); //fn define in backend/utils/utils.php
                update_term_meta( $tax_id, 'cover_image',   $banner_url, false );
            }
        
            $term = get_term( $tax_id, 'smartnft_category' );
            $profile_img = get_term_meta( $tax_id, 'profile_image', $collection['profileImg'], false );
            $cover_img = get_term_meta( $tax_id, 'cover_image',   $collection['coverImg'], false );
            
            $res = array(
                'profileImg'=> $profile_img,
                'coverImg'  => $cover_img,
                'name'      => $term->name,
                'desc'      => $term->description,
            );

            wp_send_json( $res, 200 );
        }catch( Exception $e ){
            wp_send_json(
                [
                    "status" => false,
                    "message" => $e->getMessage()
                ],
                400
            );
        }
    }

  function smartnft_get_all_categories () {
    $limit = intval($_POST['limit']);
    $offset = intval($_POST['offset']);
    $search = sanitize_text_field($_POST['search']);

    $query = array(
      'taxonomy'   => 'smartnft_category',
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
    
    $terms = get_terms( $query );

    $total_terms = wp_count_terms( array(
      'taxonomy'   => 'smartnft_category',
      'hide_empty' => false,
      'search'	=> $search
    ));

    $categories = array();

    if( is_wp_error($terms) ) {
        wp_send_json([ "data" => [ "collections" => [] ] ], 200 );
    }

    foreach( $terms as $term ) {
        $data = array(
            "term_data" => $term,
            "term_meta" => get_term_meta( $term->term_id ),
            "term_link" => get_term_link(  $term->term_id  )
        );
        $categories[] =  $data;
    }


		wp_send_json( [ "data" => [ "categories" => $categories, "total_categories" => $total_terms ] ], 200 );
  }
  
  function smartnft_get_all_categories_name_only () {
		$terms = get_terms( 
			array(
				'taxonomy'   => 'smartnft_category',
				'parent'     => 0,
				'hide_empty' => false,
      )
    );

    $categories = array();

    if( is_wp_error($terms) ) {
        wp_send_json([ "data" => [ "collections" => [] ] ], 200 );
    }

    foreach( $terms as $term ) {
        $data = array(
            "term_data" => $term,
            "term_link" => get_term_link(  $term->term_id  )
        );
        $categories[] =  $data;
    }


		wp_send_json( [ "data" => [ "categories" => $categories ] ], 200 );
  }


}

new Smartnft_Category();
