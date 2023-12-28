<?php

class Smartnft_Corn_Generate_Coll_Stats 
{
	private $coll_tax_name = "smartnft_collection";

	function __construct() {
		register_activation_hook( PLUGIN_ROOT . "smart-nft.php", [ $this, "schedules_event" ] );
		register_deactivation_hook( PLUGIN_ROOT . "smart-nft.php", [ $this, "unschedules_event" ] );
		add_action( "smartnft_corn_generate_coll_stats", array( $this, "update_coll_stats" ) );
		add_filter( "cron_schedules", array( $this, "register_new_corn_schedule_interval_time" ) );

		//ajax
		add_action("wp_ajax_nopriv_smartnft_update_coll_stats", array( $this, "update_single_coll_stats" ));
		add_action("wp_ajax_smartnft_update_coll_stats", array( $this, "update_single_coll_stats" ));

	}

	function register_new_corn_schedule_interval_time( $prev_schedules ) {
		$THREE_HOUR_IN_SECONDS = 3 * 60 * 60; 	
		$prev_schedules["three_hours"] = array( "interval" => $THREE_HOUR_IN_SECONDS, "display" => esc_html__( "Three hour", WP_SMART_NFT ) );
		return $prev_schedules;
	}

	function schedules_event() {
		if ( ! wp_next_scheduled( "smartnft_corn_generate_coll_stats" ) ) {
			    wp_schedule_event( time(), "three_hours", "smartnft_corn_generate_coll_stats" );
		   }
	}

	function unschedules_event() {
		$time_stamp = wp_next_scheduled( "smartnft_corn_generate_coll_stats" );
		if( $time_stamp ) wp_unschedule_event( $time_stamp, "smartnft_corn_generate_coll_stats" );
	}

	function update_single_coll_stats() {
		if( !isset( $_POST["collId"] )  && empty( $_POST["collId"] ) ) {
			wp_send_json("fail", 200);
		}

		$coll_id =  sanitize_text_field( $_POST["collId"] ); 

		$term = get_term( (int)$coll_id, $this->coll_tax_name );

		if( is_wp_error( $term ) ) wp_send_json( "fail", 200 );

		$this->generate_coll_stats( (int)$coll_id );
		$this->generate_coll_stats_by_time_range( (int)$coll_id );

		wp_send_json("success", 200);
	}

	function update_coll_stats() {
		//get all the collection id	
		$coll_ids = get_terms( array(
			"taxonomy" => $this->coll_tax_name,
			"fields"   => "ids",
			"hide_empty" => false,
		) );

		//if error then break
		if( is_wp_error( $coll_ids ) ) return;

		//loop through coll_ids and generate and save their status
		foreach( $coll_ids as $id ) {
			$this->generate_coll_stats( $id );
			$this->generate_coll_stats_by_time_range( $id );
		}
	}

	function generate_coll_stats( $coll_id ) {
		//post ids that use this term
		$post_ids = get_posts(
			array(
				'post_type' => 'smartnft',
				'tax_query' => array( array( 'taxonomy' => $this->coll_tax_name, 'field' => 'term_id','terms'=> $coll_id ) ),
				'fields'	=> 'ids'
			)
		);
		
		//default value
		$flour_price = 0;
		$total_volume = 0;
		$total_owners = [];
		$listed_amount = 0;
		$unlisted_amount = 0;
		
		$term_meta = get_term_meta( $coll_id, "collection_meta", true );
		
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

		$term_meta["flour_price"] = $flour_price;
		$term_meta["total_volume"] = $total_volume;
		$term_meta["total_owners"] = $total_owners;
		$term_meta["listed_amount"] = $listed_amount;
		$term_meta["unlisted_amount"] = $unlisted_amount;

		update_term_meta( $coll_id, "collection_meta", $term_meta );

	}

	function generate_coll_stats_by_time_range( $coll_id ) {
		$time_unit = array( "HOUR", "HOUR", "DAY", "DAY", "DAY", "DAY" );
		$time_number = array( 1, 6, 1, 7, 30, 3650 );

        global $wpdb;
        $table = $wpdb->prefix.'smartnft_activity';

		$stats = array();

		//loop throw time range
		foreach( $time_unit as $index => $val ) {
			$query = " SELECT * FROM " . $table . " WHERE input_time BETWEEN DATE_SUB( NOW(), INTERVAL " . $time_number[ $index ] .  " " . $val . " ) AND NOW() " . " AND collection_id = " . $coll_id;
			$rows = $wpdb->get_results($query);

			//loop throw result and generate HOURLY or DAYS stat 
			$type = array("mint" => 0, "mint_count" => 0,  "buy" => 0, "buy_count" => 0, "list" => 0, "list_count" => 0, "floor" => false, "owners" => array() );

			foreach( $rows as $row ) {
				$type[ $row->activity_type ] += floatval( $row->price );
				$type[ $row->activity_type . "_count" ] += 1;
				//counting unique owners
				$type["owners"][ $row->addr_to ] = 1;
				//update floor
				if( ( $row->activity_type == "list" || $row->activity_type == "mint" ) && $type["floor"] != false && $type["floor"] > floatval( $row->price ) ) {
					$type["floor"] = floatval( $row->price );
				}
				if( $type["floor"] == false  && $row->activity_type != "buy" ) $type["floor"] = floatval( $row->price );
			}

			//save on stats
			$stats[$index] = $type;

		}

		//get coll meta
		$meta = get_term_meta( $coll_id, "collection_meta", true );

		if( isset( $meta["stats"] ) && !empty( $meta["stats"] ) ) {
			$meta["prev_stats"] = $meta["stats"];
		}else{
			$meta["prev_stats"] = $type = array("mint" => 0, "mint_count" => 0,  "buy" => 0, "buy_count" => 0, "list" => 0, "list_count" => 0, "floor" => 0, "owners" => array() );
		}

		$meta["stats"] = $stats;
		//update new meta
		update_term_meta( $coll_id, "collection_meta", $meta );

	}
}

new Smartnft_Corn_Generate_Coll_Stats();
