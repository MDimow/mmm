<?php

class SmartNft_Erc1155_Owners_Handler 
{
	private $meta_key = "smartnft_erc1155_token_owners";

	function __construct () {
		//store or update
		add_action("wp_ajax_smartnft_erc1155_token_update_owners", array( $this, "smartnft_update_owners" ) );
		add_action("wp_ajax_nopriv_smartnft_erc1155_token_update_owners", array( $this, "smartnft_update_owners" ) );
		//delete
		add_action("wp_ajax_smartnft_erc1155_token_delete_owner", array( $this, "smartnft_delete_owner" ) );
		add_action("wp_ajax_nopriv_smartnft_erc1155_token_delete_owner", array( $this, "smartnft_delete_owner" ) );
		//get
		add_action("wp_ajax_smartnft_erc1155_token_get_owners", array( $this, "smartnft_get_erc1155_owners" ) );
		add_action("wp_ajax_nopriv_smartnft_erc1155_token_get_owners", array( $this, "smartnft_get_erc1155_owners" ) );
	}

	function smartnft_update_owners () {
		if( 
			!isset( $_POST['postId'] ) ||
			empty( $_POST['postId'] )  ||
			!isset( $_POST['ownerAddress'] ) ||
			empty( $_POST['ownerAddress'] )  ||
			!isset( $_POST['ownerData'] ) ||
			empty( $_POST['ownerData'] )
		)
	   	{
			wp_send_json( array( "status" => esc_html__("fail") ), 400 );
		}

		$post_id = intval( $_POST['postId'] );
		$owner = strtolower( $_POST['ownerAddress'] );
		$owners = get_post_meta( $post_id, $this->meta_key, true );

		if( empty( $owners ) ) { $owners = array(); }

		$data = $_POST['ownerData'];

		foreach( $data as $key => $value ) {
			$owners[ $owner ][$key] = $value;
		}

		//update the data
		update_post_meta( $post_id, $this->meta_key, $owners );

		wp_send_json( array( "status" => esc_html__("success"), 'data' => $owners ), 200 );
	}

	function smartnft_get_erc1155_owners () {
		if( !isset( $_POST['postId'] ) || empty( $_POST['postId'] ) ) 
		{
			wp_send_json( array( "status" => esc_html__("fail") ), 400 );
		}

		$post_id = intval( $_POST['postId'] );
		$owners = get_post_meta( $post_id, $this->meta_key, true );
		wp_send_json( array( "status" => esc_html__("success"), 'data' => $owners ), 200 );
	}

	function smartnft_delete_owner () {
		if( 
			!isset( $_POST['postId'] ) || 
			empty( $_POST['postId'] ) ||
			!isset( $_POST['ownerAddress'] ) ||
			empty( $_POST['ownerAddress'] )
		)

		{
			wp_send_json( array( "status" => esc_html__("fail") ), 400 );
		}

		$post_id = intval( $_POST['postId'] );
		$owner = strtolower( $_POST['ownerAddress'] );

		$owners = get_post_meta( $post_id, $this->meta_key, true );

		if(empty( $owners )) { wp_send_json( array( "status" => esc_html__("fail") ), 400 ); }

		//DELETE THE OWNER
		unset( $owners[$owner] );

		//update the data
		update_post_meta( $post_id, $this->meta_key, $owners );

		wp_send_json( array( "status" => esc_html__("success"), 'data' => $owners ), 200 );
	}
}

new SmartNft_Erc1155_Owners_Handler();
