<?php 

class SmartNft_Contract_Address 
{
	function __construct () {
		//store
		add_action("wp_ajax_smartnft_store_deployed_network_contract",array($this,"smartnft_store_deployed_network_contract"),10);
		add_action("wp_ajax_smartnft_active_deployed_contract",array($this,"smartnft_active_deployed_contract"),10);
		//get
		add_action("wp_ajax_smartnft_get_deployed_network_contracts",array($this,"smartnft_get_deployed_network_contracts"),10);
		add_action("wp_ajax_nopriv_smartnft_get_deployed_network_contracts",array($this,"smartnft_get_deployed_network_contracts"),10);

		//delete
		add_action("wp_ajax_smartnft_delete_network_contract",array($this,"smartnft_delete_network_contract"),10);

		add_action("wp_ajax_smartnft_store_market_address_on_proxy",array($this,"smartnft_store_market_address_on_proxy"),10);

		// Store Custom Networks
		add_action("wp_ajax_add_network",	array($this,	"smartnft_add_custom_network"));
		add_action("wp_ajax_get_custom_networks",	array($this,	"smartnft_get_custom_networks"));
		add_action("wp_ajax_remove_custom_network",	array($this,	"smartnft_remove_custom_network"));
		add_action("wp_ajax_update_custom_network",	array($this,	"smartnft_update_custom_network"));

		add_filter("SMARTNFT_FILTER_DEPLOYED_CONTRACT_BEFORE_ACTIVE", array($this, "smartnft_deactive_other_contract_before_activate_one"), 10, 2);
	}

	function smartnft_add_custom_network () {
		//get all other contract address from database	
		$custom_networks = get_option("smartnft_custom_networks");
		if( empty($custom_networks) ){
			$custom_networks = [];
		}


		if(! isset($_POST["custom_network"]) || empty($_POST["custom_network"])){
			wp_send_json(
				[
					"status" => esc_html__("fail", WP_SMART_NFT),
				],
				400
			);
		}

		try{
				$new_network = sanitize_texts_field($_POST["custom_network"]) ;

				//add new contract
				array_push($custom_networks, $new_network);

				//save new contract to the database
				update_option("smartnft_custom_networks",$custom_networks);

				wp_send_json(
					[
						"status" => esc_html__("success", WP_SMART_NFT),
						"data"  => $custom_networks
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
	function smartnft_get_custom_networks () {
		try{
				//get all other contract addresses database	
			$custom_networks = get_option("smartnft_custom_networks",[]);
			if( empty( $custom_networks ) ) {
				$custom_networks = [];
			}

			wp_send_json(
				[
					"data"  => $custom_networks
				],
				200
			);

		}	catch (Exception $e) {
				wp_send_json(
					[
						"message" => $e->getMessage()
					],
					400
				);
		}
				
	}
	function smartnft_update_custom_network () {
		//get all other contract address from database	
		$custom_networks = get_option("smartnft_custom_networks");
		if( empty($custom_networks) ){
			$custom_networks = [];
		}

		if( empty( $custom_networks ) ) {
			wp_send_json(
				[
					"status" 	=> esc_html__("fail", WP_SMART_NFT),
				],
				400
			);
			die();
		}

		if(	!isset( $_POST["chainid"] ) || empty( $_POST["chainid"] ) ){
			wp_send_json(
				[
					"message" => esc_html__("No chain is sent.", WP_SMART_NFT),
				],
				400
			);
			die();
		}

		try{
			//contract address form user
			$chainid = (int) $_POST["chainid"];
			
			$index = null;
			//loop through all the contracts and find the contract that has the same key
			foreach($custom_networks as $index_no => $single_network) {
				if( (int) $single_network[ "chainId" ] === $chainid ) {
					$index = $index_no;
					break;
				}
			}
			
			if( $index < 0 ) {
				wp_send_json(
					[
						"message" => esc_html__("No Network found with that chainid", WP_SMART_NFT)
					],
					400
				);
				die();
			}
			$new_network = $_POST['custom_network'];
			$new_network['chainId'] = $chainid;
			if( empty($new_network) ) {
				wp_send_json(
					[
						"message" => esc_html__("No Network found", WP_SMART_NFT)
					],
					400
				);
				die();
			}
			//edit contract item
			$custom_networks[$index] = $new_network;
			//save new contract addresses to the database
			update_option( "smartnft_custom_networks", $custom_networks );

			wp_send_json(
				[
					"status" => esc_html__("success", WP_SMART_NFT),
					"data"  => $custom_networks
				],
				200
			);
			die();

		}catch( Exception $e ) {
			wp_send_json(
				[
					"status" => esc_html__("fail", WP_SMART_NFT),
					"message" => $e->getMessage()
				],
				400
			);
			die();
		}

	}
	function smartnft_remove_custom_network () {
		//get all other contract address from database	
		$custom_networks = get_option("smartnft_custom_networks");
		if( empty($custom_networks) ){
			$custom_networks = [];
		}

		if( empty( $custom_networks ) ) {
			wp_send_json(
				[
					"status" 	=> esc_html__("fail", WP_SMART_NFT),
				],
				400
			);
			die();
		}

		if(	!isset( $_POST["chainid"] ) || empty( $_POST["chainid"] ) ){
			wp_send_json(
				[
					"message" => esc_html__("No chain is sent.", WP_SMART_NFT),
				],
				400
			);
			die();
		}

		try{
			//contract address form user
			$chainid = sanitize_text_field( $_POST["chainid"] ) ;
			
			$index = null;
			//loop through all the contracts and find the contract that has the same key
			foreach($custom_networks as $index_no => $single_network) {
				if( $single_network[ "chainid" ] === $chainid ) {
					$index = $index_no;	
					break;
				}
			}
			
			if( $index < 0 ) {
				wp_send_json(
					[
						"message" => esc_html__("No Network found with that chainid", WP_SMART_NFT)
					],
					400
				);
				die();
			}
			//remove contract item
			array_splice( $custom_networks, $index , 1);

			//save new contract addresses to the database
			update_option( "smartnft_custom_networks", $custom_networks );

			wp_send_json(
				[
					"status" => esc_html__("success", WP_SMART_NFT),
					"data"  => $custom_networks
				],
				200
			);
			die();

		}catch( Exception $e ) {
			wp_send_json(
				[
					"status" => esc_html__("fail", WP_SMART_NFT),
					"message" => $e->getMessage()
				],
				400
			);
			die();
		}

	}

	function smartnft_store_deployed_network_contract () {
		//get all other contract address from database	
		$network_contracts = get_option("smartnft_deployed_network_contract", []);
		if( empty($network_contracts) ){
			$network_contracts = [];
		}


		if(! isset($_POST["contract_info"]) || empty($_POST["contract_info"])){
			wp_send_json([ "status" => esc_html__("fail", WP_SMART_NFT)],400 );
		}

		try{
			$chain_id = sanitize_text_field( $_POST["contract_info"]["chain_id"] );
			$standard = sanitize_text_field( $_POST["contract_info"]["standard"] );
			$address = sanitize_text_field( $_POST["contract_info"]["address"] );
			$name = sanitize_text_field( $_POST["contract_info"]["name"] );
			$symbol = sanitize_text_field( $_POST["contract_info"]["symbol"] );

			if($standard == "Erc20"){
				$data = array( "address" => $address, "name" => $name, "symbol" => $symbol );
				$network_contracts[ $chain_id ][ 'Erc20' ][$name] = $data;
			}else{
				$network_contracts[ $chain_id ][ $standard ][ "address" ] = $address;
				$network_contracts[ $chain_id ][ $standard ][ "name" ] = $name;
				$network_contracts[ $chain_id ][ $standard ][ "symbol" ] = $symbol;
			}

			//save new contract to the database
			update_option("smartnft_deployed_network_contract", $network_contracts);

			wp_send_json(
				[
					"status" => esc_html__("success", WP_SMART_NFT),
					"data"  => $network_contracts
				],
				200
			);

		}catch( Exception $e ) {
			wp_send_json(["message" => $e->getMessage()], 400);
		}
				
	}

	function smartnft_delete_network_contract () {
		$chain_id = sanitize_text_field( $_POST["chain_id"] );

		$network_contracts = get_option("smartnft_deployed_network_contract", []);
		if( empty($network_contracts) ){
			$network_contracts = [];
		}

		unset( $network_contracts[ $chain_id ] );

		//save new contract to the database
		update_option("smartnft_deployed_network_contract", $network_contracts);

		wp_send_json( array("data" => array("status" => "success","message" => "network contract deleted")), 200 );
	}

	function smartnft_deactive_other_contract_before_activate_one ($network_contracts, $original_contracts) {
			$removed_active_contracts = $network_contracts;	

			foreach( $removed_active_contracts as $key => $value ) {
				$removed_active_contracts[ $key ]['is_active'] = "false";
			}

			return $removed_active_contracts;
	}

	function smartnft_active_deployed_contract () {
		try{
			if(! isset($_POST["contract_info"]) || empty($_POST["contract_info"] ) ) {
				wp_send_json([ "status" => esc_html__("fail", WP_SMART_NFT)],400);
			}

			$chain_id = sanitize_text_field( $_POST["contract_info"]["chain_id"] );
			$is_active = sanitize_text_field( $_POST["contract_info"]["is_active"] );

			$network_contracts = get_option("smartnft_deployed_network_contract", []);

			$network_contracts = apply_filters( "SMARTNFT_FILTER_DEPLOYED_CONTRACT_BEFORE_ACTIVE", $network_contracts, $network_contracts );

			$network_contracts[ $chain_id ][ "is_active" ] = $is_active;


			//save new contract to the database
			update_option("smartnft_deployed_network_contract", $network_contracts);
			wp_send_json([ "data"  => $network_contracts], 200);

		}catch (Exception $e) {
			wp_send_json([ "message" => $e->getMessage()],400);
		}
	}

	function smartnft_get_deployed_network_contracts () {
		try{
			//get all other contract addresses database	
			$contract_addresses = get_option("smartnft_deployed_network_contract", []);
			wp_send_json([ "data"  => $contract_addresses], 200);
		}catch (Exception $e) {
			wp_send_json([ "message" => $e->getMessage()],400);
		}
	}

	function smartnft_store_market_address_on_proxy () {
		if( 
			!isset( $_POST['chainId'] ) ||
			empty($_POST['chainId'] ) || 
			!isset( $_POST['address'] ) ||
			empty( $_POST['address'] ) 
	  	  )  
	  	  {
		 	wp_send_json([ "message" => esc_html__("Send valid information.", WP_SMART_NFT)],400);
	  	  }	

		  $network_contracts = get_option("smartnft_deployed_network_contract", []);

		  $chain_id = sanitize_text_field( $_POST["chainId"] );
		  $address = sanitize_text_field( $_POST["address"] );
			
		  $network_contracts[ $chain_id ][ 'proxy' ][ 'marketOnProxy' ] = $address;
		  //save new contract to the database
		  update_option("smartnft_deployed_network_contract", $network_contracts);

		  wp_send_json([ "message" => esc_html__("Market set on proxy successfully", WP_SMART_NFT)],200);
	}

}

new SmartNft_Contract_Address();
