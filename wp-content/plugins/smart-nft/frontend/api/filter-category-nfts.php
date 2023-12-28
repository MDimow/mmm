<?php
class SmartNft_Filter_Category_Nfts 
{
	function __construct() {
		// Get nfts by collection
		add_action("wp_ajax_smartnft_get_category_nfts", array($this,"smartnft_get_nfts_by_category"),10);
		add_action("wp_ajax_nopriv_smartnft_get_category_nfts", array($this,"smartnft_get_nfts_by_category"),10);

		add_action("wp_ajax_smartnft_get_single_category_info", array($this,"smartnft_get_single_category_info"),10);
		add_action("wp_ajax_nopriv_smartnft_get_single_category_info", array($this,"smartnft_get_single_category_info"),10);

		// Get single NFT category Info
		add_action("wp_ajax_get_category_info", array($this,"smartnft_get_single_nft_category_info"),10);
		add_action("wp_ajax_nopriv_get_category_info", array($this,"smartnft_get_single_nft_category_info"),10);
	}

	function smartnft_get_nfts_by_category () {
		if( !isset( $_POST["tax_id"] ) || empty( $_POST["tax_id"] ) ) {
			wp_send_json (
				[
					"message" => esc_html__("Send category id", WP_SMART_NFT)
				],
				400
			);
		}

		if( !isset( $_POST["contract_add"] ) || empty( $_POST["contract_add"] ) ) {
			wp_send_json (
				[
					"status" 	=> esc_html__("fail",WP_SMART_NFT ),
				],
				400
			);
		}

		$tax_id 			 = sanitize_text_field( $_POST["tax_id"] );
		$offset 			 = sanitize_text_field( $_POST["offset"] );	
		$limit  			 = sanitize_text_field( $_POST["limit"] );	
		$contract_add = sanitize_text_field( $_POST["contract_add"] );	
		$price_range   = map_deep($_POST['priceRange'], 'sanitize_text_field');
		$status        	= sanitize_text_field( $_POST['status'] );
		$price_order   = sanitize_text_field( $_POST['priceOrder'] );
    $search_text   = sanitize_text_field( $_POST['search'] );

		try{
      //get category nft form db
			$meta_query = array(
							array(
									'key'     => 'contract_addr', //key is saved as contract_addr (remember) in post meta
									'value'   => strtolower($contract_add),
									'compare' => '=',
							),
					);

      $tax_query = array(
              array(
                'taxonomy'	=> 'smartnft_category',
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
					"message" => $e->getMessage()
				],
				400
			);
		}
	}

	public function smartnft_get_single_category_info(){
		if(! isset($_POST["tax_id"]) || empty($_POST["tax_id"])){
			wp_send_json(
				[
					"message" => esc_html__("No tax id is sent", WP_SMART_NFT),
				],
				400
			);
		}

		try{
			$id = intval($_POST['tax_id']);
			$category_info = array();
			$term = get_term($id,'smartnft_category' );

			if( !is_wp_error( $term  ) ){
          $category_info['data']    =   $term;
          $category_info['term_url']     =   get_term_link( $term->slug, "smartnft_category" );
          $category_info['meta']    =   get_term_meta( $term->term_id );

          wp_send_json (
            [
              "data"  => $category_info
            ],
            200
          );
			}else {
        wp_send_json (
          [
            "data"  => "hola"
          ],
          400
        );
			}
		}catch( Exception $e ) {
			wp_send_json(
				[
					"message" => $e->getMessage()
				],
				400
			);
		}

	}
	public function smartnft_get_single_nft_category_info(){
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
			$category_info = array();
			$terms = get_the_terms($id,'smartnft_category' );

			if( $terms ){
				$category_info['name']    = $terms[0]->name;
				$category_info['url']     = get_term_link($terms[0]->slug,"smartnft_category");
				$category_info['status']  = true;
			}else{
				$category_info['status'] = false;
			}
			wp_send_json(
				[
					"data"  => $category_info
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
}

new SmartNft_Filter_Category_Nfts();



