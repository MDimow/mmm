<?php

class SmartNFTWidgets 
{
	
	function __construct() {
		if( did_action('elementor/loaded' ) ){
      // Registering Elementor category
			add_action( 'elementor/elements/categories_registered', array($this,'register_elementor_category'));
            
      // Load Elementor widgets
			add_action( 'elementor/widgets/register', array( $this, 'SmartNftElementorWidgetInit') );
		}
	}


	public function SmartNftElementorWidgetInit(){
		// Elementor Elemnts
		$this->loadElementorFiles();
		Elementor\Plugin::instance()->widgets_manager->register( new Create_NFT_Form() );
		Elementor\Plugin::instance()->widgets_manager->register( new SmartNft_Top_Collector_Element() );
		Elementor\Plugin::instance()->widgets_manager->register( new SMARTNFT_NFT_GRID() );
		Elementor\Plugin::instance()->widgets_manager->register( new SMARTNFT_COLLECTION_GRID() );
		Elementor\Plugin::instance()->widgets_manager->register( new SMARTNFT_COLLECTION_GRID_Two() );
		Elementor\Plugin::instance()->widgets_manager->register( new SMARTNFT_COLLECTION_CAROUSEL() );
		Elementor\Plugin::instance()->widgets_manager->register( new SMARTNFT_COLLECTION_CAROUSEL_TWO() );
		Elementor\Plugin::instance()->widgets_manager->register( new SMARTNFT_COLLECTION_BIG_SLIDER() );
		Elementor\Plugin::instance()->widgets_manager->register( new SMARTNFT_CATEGORY_CAROUSEL() );
		Elementor\Plugin::instance()->widgets_manager->register( new SMARTNFT_CATEGORY_GRID() );
		Elementor\Plugin::instance()->widgets_manager->register( new SMARTNFT_COLLECTION_STATS() );
	}
	

  // Loading elementor element files
	public function loadElementorFiles(){
		include PLUGIN_ROOT . "/backend/inc/elementor/add-nft-form.php";
		include PLUGIN_ROOT . "/backend/inc/elementor/top-collector.php";
		include PLUGIN_ROOT . "/backend/inc/elementor/nft-grid.php";
		include PLUGIN_ROOT . "/backend/inc/elementor/collection-grid.php";
		include PLUGIN_ROOT . "/backend/inc/elementor/collection-grid-2.php";
		include PLUGIN_ROOT . "/backend/inc/elementor/collection-carousel.php";
		include PLUGIN_ROOT . "/backend/inc/elementor/coll-carousel-2.php";
		include PLUGIN_ROOT . "/backend/inc/elementor/coll-big-slider.php";
		include PLUGIN_ROOT . "/backend/inc/elementor/category-carousel.php";
		include PLUGIN_ROOT . "/backend/inc/elementor/category-grid.php";
		include PLUGIN_ROOT . "/backend/inc/elementor/coll-stats.php";
	}


	public function register_elementor_category( $elements_manager ){
		$elements_manager->add_category(
			'Smart_NFT',
				[
					'title' => esc_html__( 'Smart NFT',  WP_SMART_NFT),
					'icon' => 'fa fa-plug',
				]
		);
	}
}

new SmartNFTWidgets();
