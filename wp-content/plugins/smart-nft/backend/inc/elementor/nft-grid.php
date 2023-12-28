<?php

class SMARTNFT_NFT_GRID extends \Elementor\Widget_Base 
{
	public function get_name() {
		return 'smartnft_nft_grid';
	}

	public function get_title() {
		return esc_html__( 'NFT grid tabs', WP_SMART_NFT );
	}

	public function get_icon() {
		return 'eicon-calendar';
	}

	public function get_categories() {
		return [ 'Smart_NFT' ];
	}

	public function get_script_depends() {
		$translation = new Smartnft_Translation_Manager();

			$nft_grid_local = array(
				"BACKEND_AJAX_URL" => admin_url("admin-ajax.php"),
				"ACTIVE_CONTRACT" => get_option("smartnft_active_contract_address",false),
				"frontendMediaUrl" => FRONTEND_MEDIA_URL,
				"translation"	   => $translation->get_translated_array()
			);

			wp_register_script(
			  "smartnft_front_element_nft_grid",
				FRONTEND_SCRIPT_URL . 'element-nft-grid.bundle.js',
				array("wp-i18n","jquery"),
				false,
				true
			);

			wp_set_script_translations( 'smartnft_front_element_nft_grid', WP_SMART_NFT );
			wp_localize_script(
				"smartnft_front_element_nft_grid",
				"nft_grid_local",
				$nft_grid_local
			);

			return [
				'smartnft_front_element_nft_grid',
			];
	}

	function categories_options () {
		$categories = smartnft_get_categories();
		$options = array('all' => 'All');
		
		foreach( $categories as $category ) {
			$options[$category->slug] = $category->name;
		}

		return $options;
	}

	function collection_options() {
		$collections = smartnft_get_collections();
		$options = array();
		
		foreach( $collections as $collection ) {
			$options[$collection->slug] = $collection->name;
		}

		return $options;
	}

	protected function register_controls(){
		$this->start_controls_section(
			'content_section',
			[
				'label' => esc_html__( 'Content', WP_SMART_NFT ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'isTabOn',
			[
				'label' => esc_html__( 'Show Tab', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', WP_SMART_NFT ),
				'label_off' => esc_html__( 'Hide', WP_SMART_NFT ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);


		$this->add_control(
			'category_selector',
			[
				'label' => esc_html__( 'Categories',  WP_SMART_NFT),
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => true,
				'options' =>  $this->categories_options(),
				'default' => [],
			]
		);

		$this->add_control(
			'collection_selector',
			[
				'label' => esc_html__( 'Collections',  WP_SMART_NFT),
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => true,
				'options' =>  $this->collection_options(),
				'default' => [],
			]
		);

		$this->add_control(
			'limit',
			[
				'label' => esc_html__( 'Limit', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 500,
				'step' => 500,
				'default' => 12,
			]
		);
		
		$this->add_control(
			'columns',
			[
				'label' => esc_html__( 'Columns', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 10,
				'step' => 10,
				'default' => 4,
			]
		);

		$this->add_control(
			'columns_gap',
			[
				'label' => esc_html__( 'Columns gap', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .smartnft_nft_grid_nfts' => 'column-gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'row_gap',
			[
				'label' => esc_html__( 'Row gap', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .smartnft_nft_grid_nfts' => 'row-gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'tab_section',
			[
				'label' => esc_html__( 'Tab style', WP_SMART_NFT ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'tab_typography',
				'label' => esc_html__( 'Tab typography', WP_SMART_NFT),
				'selector' => '{{WRAPPER}} .smartnft_nft_grid_categories .nft-grid__category',
			]
		);

		$this->add_control(
			'tab_color',
			[
				'label' => esc_html__( 'Tab Color', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .smartnft_nft_grid_categories .nft-grid__category' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'tab_active_color',
			[
				'label' => esc_html__( 'Tab Active Color', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .smartnft_nft_grid_categories .nft-grid__category.active' => 'color: {{VALUE}};border-bottom: 2px solid {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'tab_border_color',
			[
				'label' => esc_html__( 'Tab Border Color', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .smartnft_nft_grid_categories' => 'border-bottom: 1px solid {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'tab_bottom_spacing',
			[
				'label' => esc_html__( 'Tab bottom spacing', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 500,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 20,
				],
				'selectors' => [
					'{{WRAPPER}} .smartnft_nft_grid_categories' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'tab_alignment',
			[
				'label' => esc_html__( 'Tab alignment', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'start' => [
						'title' => esc_html__( 'Left', WP_SMART_NFT ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', WP_SMART_NFT ),
						'icon' => 'eicon-text-align-center',
					],
					'end' => [
						'title' => esc_html__( 'Right', WP_SMART_NFT ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'center',
				'toggle' => true,
				'selectors' => [
					'{{WRAPPER}} .smartnft_nft_grid_categories' => 'justify-content: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();


		$this->start_controls_section(
			'card_section',
			[
				'label' => esc_html__( 'Card style', WP_SMART_NFT ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'img_height',
			[
				'label' => esc_html__( 'Image height', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 500,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 250,
				],
				'selectors' => [
					'{{WRAPPER}} .smartnft_nft_grid_nfts .card__img'   => 'height: 100%;',
					'{{WRAPPER}} .smartnft_nft_grid_nfts .card figure' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .smartnft_nft_grid_nfts .card > figure' => 'max-height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'img_border_radius',
			[
				'label' => esc_html__( 'Image border radius', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .smartnft_nft_grid_nfts .card__img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .smartnft_nft_grid_nfts .card figure > a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'img_padding',
			[
				'label' => esc_html__( 'Image padding', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .smartnft_nft_grid_nfts .card figure' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'img_background_color',
			[
				'label' => esc_html__( 'Image background color', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .smartnft_nft_grid_nfts .card figure' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'card_border_radius',
			[
				'label' => esc_html__( 'Card border radius', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .smartnft_nft_grid_nfts .card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'card_info_meta_typography',
				'label' => esc_html__( 'Meta typography', WP_SMART_NFT),
				'selector' => '{{WRAPPER}} .smartnft_nft_grid_nfts .card__creator,#smartnft_nft_grid_nfts .card__info span',
			]
		);

		$this->add_control(
			'card_info_meta_color',
			[
				'label' => esc_html__( 'Meta Color', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .smartnft_nft_grid_nfts .card__creator' => 'color: {{VALUE}}',
					'{{WRAPPER}} .smartnft_nft_grid_nfts .card__info span' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'name_typography',
				'label' => esc_html__( 'Name typography', WP_SMART_NFT),
				'selector' => '{{WRAPPER}} .smartnft_nft_grid_nfts .card__name',
			]
		);

		$this->add_control(
			'name_color',
			[
				'label' => esc_html__( 'Name Color', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .smartnft_nft_grid_nfts .card__name' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'price_typography',
				'label' => esc_html__( 'Price typography', WP_SMART_NFT),
				'selector' => '{{WRAPPER}} .smartnft_nft_grid_nfts .card__price',
			]
		);

		$this->add_control(
			'price_color',
			[
				'label' => esc_html__( 'Price Color', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .smartnft_nft_grid_nfts .card__price' => 'color: {{VALUE}}',
				],
			]
		);


		$this->add_control(
			'card_info_padding',
			[
				'label' => esc_html__( 'Card info padding', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .smartnft_nft_grid_nfts .card__info' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'card_info_border_radius',
			[
				'label' => esc_html__( 'Card info border radius', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .smartnft_nft_grid_nfts .card__info' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'card_info_background',
				'label' => esc_html__( 'Card info background', WP_SMART_NFT),
				'types' => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} .smartnft_nft_grid_nfts .card__info',
			]
		);


		$this->end_controls_section();
	}

	protected function render(){
			$settings = $this->get_settings_for_display();

      $unique_id = uniqid();

			$settings['unique_id'] = $unique_id;

			echo "<div class='smartnft_nft_grid_categories " . "smartnft_nft_grid_categories_".$unique_id . "'></div>";
			echo "<div class='smartnft_nft_grid_nfts " . "smartnft_nft_grid_nfts_".$unique_id . "'></div>";
?>

		<style>
			<?php echo ".smartnft_nft_grid_nfts_".$unique_id ?>{
				grid-template-columns: repeat(<?php echo $settings['columns'] ?>,1fr); 
			}
			<?php echo ".smartnft_nft_grid_categories_".$unique_id ?>{
				<?php if( $settings['isTabOn'] !== 'yes' ) { echo "display:none;"; }?>

			}
		</style>

		<!-- this below script run only in builder mode -->
		<script>
			if (typeof smartnftNftGridElementSettings == "undefined") {
						var smartnftNftGridElementSettings = ['<?php echo json_encode($settings) ?>'];
				 }else{
						smartnftNftGridElementSettings.push('<?php echo json_encode($settings) ?>')
				}

				if("SMARTNFT_NFT_GRID_RERUN_APP" in window){
					window.setTimeout(() => {
						window.SMARTNFT_NFT_GRID_RERUN_APP(smartnftNftGridElementSettings);
					},1000);
				}
		</script>
			
<?php	}

}
