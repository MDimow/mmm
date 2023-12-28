<?php
class SmartNft_Top_Collector_Element extends \Elementor\Widget_Base
{
	public function get_name() {
		return 'smart_nft_top_collector';
	}

	public function get_title() {
		return esc_html__( 'Top Collector', WP_SMART_NFT );
	}

	public function get_icon() {
		return 'eicon-calendar';
	}

	public function get_categories() {
		return [ 'Smart_NFT' ];
	}

	public function get_script_depends() {

			$local = array(
				"backendMediaUrl"  => BACKEND_MEDIA_URL,
				"frontendMediaUrl" => FRONTEND_MEDIA_URL,
				"backend_ajax_url" => admin_url("admin-ajax.php"),
				"site_root"		   => get_site_url(),
				"site_title"       => get_bloginfo("name") ,
				"settings"         => get_option("smartnft_settings",false),
				"custom_networks"  => get_option("smartnft_custom_networks", [])
			);

			wp_register_script(
				"smartnft_element_top_collector",
				FRONTEND_SCRIPT_URL . 'element-top-collector.bundle.js',
				array("wp-i18n","jquery"),
				false,
				true
			);
			wp_set_script_translations( 'smartnft_element_top_collector', WP_SMART_NFT );
			wp_localize_script(
				"smartnft_element_top_collector",
				"local",
				$local
			);

			return [
				'smartnft_element_top_collector',
			];
	}

	protected function register_controls () {
		$this->start_controls_section(
			'smart_nft_top_collector_grid_controll',
			[
				'label' => esc_html__( 'Top collector grid controll', WP_SMART_NFT  ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
					'smart_nft_top_collector_grid_number',
					[
						'label' => esc_html__( 'Column number', WP_SMART_NFT),
						'type' => \Elementor\Controls_Manager::NUMBER,
						'min' => 1,
						'max' => 10,
						'step' => 1,
						'default' => 3,
					]
		);		

		$this->end_controls_section();
			
	}

	protected function render () {
		echo '<div id="smartnft-top-collector-root"></div>'; 
		?>

		<style>
				.top-collectors {
						grid-template-columns: repeat(<?php echo $settings["smart_nft_top_collector_grid_number"] ?>, 1fr);
				}
		</style>


		<!-- this below script run only in builder mode -->
		 <script>
			if("SMART_NFT_TOP_COLLECTOR_RERUN_APP" in window){
				window.setTimeout(() => {
					window.SMART_NFT_TOP_COLLECTOR_RERUN_APP();
				},1000);
			}
		</script>

<?php	
	}
}
