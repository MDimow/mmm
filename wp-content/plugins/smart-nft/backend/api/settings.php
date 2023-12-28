<?php

class SmartNft_Settings 
{
	public $option_key = "smartnft_settings";

	function __construct () {
		//store 
		add_action( "wp_ajax_smartnft_store_settings", array( $this, "smartnft_store_settings" ) );
		//get
		add_action( "wp_ajax_smartnft_get_settings", array( $this, "smartnft_get_settings" ) );
		add_action( "wp_ajax_smartnft_switch_plugin_state", array( $this, "smartnft_switch_plugin_state" ) );

	}
	function smartnft_switch_plugin_state(){
		if( !isset( $_POST["slug"] ) || empty( $_POST["slug"] ) )  {
			wp_send_json(
				[
					"status" => "fail",
					"message" => esc_html__( "No slug is send. Please send slug", WP_SMART_NFT ),
				],
				400
			);
		}
		$slug = $_POST['slug'];
		$realslug = $slug . '/'. $slug .'.php';
		if(in_array( $realslug, apply_filters('active_plugins', get_option('active_plugins')))){ 
			$result = deactivate_plugins( $realslug );
		}else{
			$result = activate_plugin( $realslug );
		}
		if ( is_wp_error( $result ) ) {
			wp_send_json(
				[
					"status" => "failed",
				],
				400
			);
		}else{
			if(in_array( $realslug, apply_filters('active_plugins', get_option('active_plugins')))){ 
				$status = true;
			}else{
				$status = false;
			}
			wp_send_json(
				[
					"status" => $status,
				],
				200
			);
		}
	}
	function smartnft_store_settings () {
		try{
				
				if( !isset( $_POST["settings"] ) || empty( $_POST["settings"] ) )  {
						wp_send_json(
							[
								"status" => "fail",
								"message" => esc_html__( "No settings is send. Please send settings", WP_SMART_NFT ),
							],
							400
						);
				}
				$new_settings = map_deep($_POST["settings"], 'sanitize_text_field');

				//get previews settings
				$settings = get_option($this->option_key,[]);

				//add new settings to the previews settings 
				//if same key over write them
				foreach( $new_settings as $key => $value ) {
						$settings[$key] = $value;
				}

				//save new settings
				update_option($this->option_key, $settings);

				wp_send_json(
					[
					 	 "status" => "success",
						 "message" => esc_html__("operation success full", WP_SMART_NFT),
						 "data"		=> $settings
					],
					200
				);

				die();

		}catch( Exception $e ) {

				wp_send_json(
					[
						"status" => "fail",
						"message" => $e->getMessage()
					],
					400
				);
				die();

		}
	}

	function smartnft_get_settings () {
			//get settings
			$settings = get_option( $this->option_key, false );

				wp_send_json(
					[
						"status"  => "success",
						"message" => esc_html__("operation success full", WP_SMART_NFT),
						"data"		=> $settings,
					],
					200
				);
				die();
	}
}

new SmartNft_Settings();
