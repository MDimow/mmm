<?php

class SMARTNFT_COLLECTION_STATS extends \Elementor\Widget_Base
{
	public function get_name() {
		return 'smartnft_collection_stats';
	}

	public function get_title() {
		return esc_html__( 'Collection Statistics', WP_SMART_NFT );
	}

	public function get_icon() {
		return 'eicon-calendar';
	}

	public function get_categories() {
		return [ 'Smart_NFT' ];
	}

	public function get_script_depends() {
		$translation = new Smartnft_Translation_Manager();

		$local = array(
			"backendMediaUrl"  => BACKEND_MEDIA_URL,
			"frontendMediaUrl" => FRONTEND_MEDIA_URL,
			"backend_ajax_url" => admin_url("admin-ajax.php"),
			"site_root"		   => get_site_url(),
			"site_title"       => get_bloginfo("name") ,
			"active_contract"  => get_option("smartnft_active_contract_address",false),
			"settings"         => get_option("smartnft_settings",false),
			"custom_networks"  => get_option("smartnft_custom_networks", []),
			"translation"	   => $translation->get_translated_array()
		);
		wp_register_script(
			"smartnft_front_element_coll_stats",
			FRONTEND_SCRIPT_URL . 'collection-stats-element.bundle.js',
			array("wp-i18n","jquery"),
			false,
			true
		);

		wp_set_script_translations( 'smartnft_front_element_coll_stats', WP_SMART_NFT );
		wp_localize_script("smartnft_front_element_coll_stats","local",$local);
		return [ 'smartnft_front_element_coll_stats' ];
	}

	protected function register_controls(){
		$this->start_controls_section(
			'general_section',
			[
				'label' => esc_html__( 'General', WP_SMART_NFT ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'limit',
			[
				'label' => esc_html__( 'Limit', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 100,
				'step' => 100,
				'default' => 10,
			]
		);

		$this->end_controls_section();

	}

	protected function render() {
		$uniq_id = uniqid("smartnft");	
		$settings = $this->get_settings_for_display();
	?>
		<div id="<?php echo $uniq_id; ?>"></div>

		<script>
		(() => {
			window.SMART_NFT_STATS_LIMIT = <?php echo $settings['limit']; ?>;
			window.smnft_stats_con_id = <?php echo $uniq_id; ?>;
			window.SMART_NFT_STATS_RERUN_APP(window.smnft_stats_con_id, window.SMART_NFT_STATS_LIMIT);
		}) ();
		</script>
	<?php
   	}
}
