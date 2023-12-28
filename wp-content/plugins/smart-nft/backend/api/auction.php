<?php

class Smartnft_Auction_API {
	private $key = "smartnft_auction_benificiary_bid";

	function __construct() {
		add_action( "wp_ajax_smartnft_add_auction_bid", array( $this, "smartnft_add_auction_bid" ), 10 );	
		add_action( "wp_ajax_nopriv_smartnft_add_auction_bid", array( $this, "smartnft_add_auction_bid" ), 10 );	
		
		add_action( "wp_ajax_smartnft_get_auction_bid", array( $this, "smartnft_get_auction_bid" ), 10 );	
		add_action( "wp_ajax_nopriv_smartnft_get_auction_bid", array( $this, "smartnft_get_auction_bid" ), 10 );	

		add_action( "wp_ajax_smartnft_delete_auction_bid", array( $this, "smartnft_delete_auction_bid" ), 10 );	
		add_action( "wp_ajax_nopriv_smartnft_delete_auction_bid", array( $this, "smartnft_delete_auction_bid" ), 10 );	
	}

	private function must_fields_error( $must_fields ) {
		foreach( $must_fields as $field ) {
			if( !$_POST[ $field ] || empty( $_POST[ $field ] ) ) {
				wp_send_json( array( "data" => esc_html__("Send proper information.", WP_SMART_NFT) ), 400 );
			}
		}
	}


	function smartnft_add_auction_bid() {
		//ERROR CHECK FOR INVALID DATA
		$must_fields = array( 'post_id', 'signer', 'data_to_save', 'benificiary' );
		$this->must_fields_error( $must_fields );

		$post_id = sanitize_text_field( $_POST['post_id'] );
		$data_to_save = map_deep( $_POST['data_to_save'], "sanitize_text_field" );
		$benificiary = sanitize_text_field( $_POST['benificiary'] );

		$auction_data = get_post_meta( $post_id, $this->key, true );
		$updated_data = array();
		if( !$auction_data || empty( $auction_data ) ) {
			$auction_data = array();
		}else{
			foreach( $auction_data[ strtolower( $benificiary ) ] as $data  ) {
				$newVal = $data;
				$newVal['highestBid'] = false;
				array_push( $updated_data, $newVal  );
			}
		}

		$data_to_save['time'] = time();
		$data_to_save['highestBid'] = true;
		$auction_data[ strtolower( $benificiary ) ] = $updated_data;

		$auction_data[ strtolower( $benificiary ) ][] = $data_to_save;

		//SAVE THE NEW BID DATA	
		update_post_meta( $post_id, $this->key, $auction_data );

		wp_send_json( array( "data" => $auction_data ), 200 );

	}

	function smartnft_delete_auction_bid() {
		$this->must_fields_error( array( 'post_id', 'bid_hash', 'benificiary' ) );	

		$post_id = sanitize_text_field( $_POST['post_id'] );
		$benificiary = strtolower( sanitize_text_field( $_POST['benificiary'] ) );
		$bid_hash = sanitize_text_field( $_POST['bid_hash'] );

		$auction_data = get_post_meta( $post_id, $this->key, true );

		$benificiary_data = $auction_data[ $benificiary ];
		$updated_data = array();

		foreach( $benificiary_data as $value ) {
			if( $value['hash']  != $bid_hash ) {
				array_push( $updated_data, $value ); 
			}
		}

		$auction_data[  $benificiary  ] = $updated_data;

		update_post_meta( $post_id, $this->key, $auction_data );

		wp_send_json( array( "data" => $auction_data ), 200 );

	}

	function smartnft_get_auction_bid() {
		$this->must_fields_error( array( 'post_id' ) );	
		$post_id = sanitize_text_field( $_POST['post_id'] );
		$auction_data = get_post_meta( $post_id, $this->key, true );
		if( !$auction_data || empty( $auction_data ) ) { $auction_data = array(); }
		wp_send_json( array( "data" => $auction_data ), 200 );
	}

}

new Smartnft_Auction_API();
