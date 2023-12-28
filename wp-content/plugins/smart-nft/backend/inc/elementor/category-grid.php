<?php

class SMARTNFT_CATEGORY_GRID extends \Elementor\Widget_Base 
{
	public function get_name() {
		return 'smartnft_category_grid';
	}

	public function get_title() {
		return esc_html__( 'Category grid', WP_SMART_NFT );
	}

	public function get_icon() {
		return 'eicon-calendar';
	}

	public function get_categories() {
		return [ 'Smart_NFT' ];
	}

	function get_categoriess ($limit) {
            //$active_contract = get_option("smartnft_active_contract_address",false);
            //$contract_addr = $active_contract['address'];
            $args = array (
                'taxonomy'      => 'smartnft_category',
                'number'        => intval( $limit ),
                'offset' 		=> 0,
                'hide_empty'    => false,
                'orderby'    => 'count',
                'order'      => 'DESC'
            );

            $terms = get_terms( $args );
            $response = [];
            
            foreach( $terms as $term ){
                $term_id = $term->term_id;
            
                // Category Data
                $profile_img = get_term_meta( $term_id, 'profile_image', true );
                $cover_img = get_term_meta( $term_id, 'cover_image', true );


                $response[] = array(
                    'name'             => $term->name,
                    'permalink'        => get_term_link($term_id),
                    'collectionImg'    => $profile_img,
                    'collectionBanner' => $cover_img,
                    'count'            => $term->count,
                );

            }

        return $response;
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
			'limit',
			[
				'label' => esc_html__( 'Limit', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 500,
				'step' => 500,
				'default' => 8,
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
					'{{WRAPPER}} .collections-grid' => 'column-gap: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .collections-grid ' => 'row-gap: {{SIZE}}{{UNIT}};',
				],
			]
		);
    $this->end_controls_section();
    
    $this->start_controls_section(
        'style_section',
        [
            'label' => esc_html__( 'Card Style', WP_SMART_NFT ),
            'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
        $this->add_control(
            'card_border_radius',
			[
                'label' => esc_html__( 'Card border radius', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'selectors' => [
                    '{{WRAPPER}} .collection-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
			]
        );
		$this->add_control(
            'banner_height',
			[
				'label' => esc_html__( 'Banner height', WP_SMART_NFT ),
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
					'size' => 190,
				],
				'selectors' => [
					'{{WRAPPER}} .collection-grid__top .collection-cover' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

    
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'name_typography',
				'label' => esc_html__( 'Name typography', WP_SMART_NFT),
				'selector' => '{{WRAPPER}} .collection-grid__top .category-name',
			]
		);
		$this->add_control(
			'name_text_align',
			[
				'label' => esc_html__( 'Alignment', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'textdomain' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'textdomain' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'textdomain' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'center',
				'toggle' => true,
				'selectors' => [
					'{{WRAPPER}} .collection-grid__top .category-name' => 'text-align: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'name_color',
			[
				'label' => esc_html__( 'Name Color', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .collection-grid__top .category-name' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'name_spaceing',
			[
				'label' => esc_html__( 'Name spaceing', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .collection-grid__top .category-name' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'bottom_part_background',
			[
				'label' => esc_html__( 'Content background', WP_SMART_NFT),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#F0F0F0',
				'selectors' => [
					'{{WRAPPER}} .collection-card' => 'background-color: {{VALUE}}',
				],
			]
		);

    $this->end_controls_section();
	}

	protected function render(){
		$translation_class = new Smartnft_Translation_Manager();
		$translation = $translation_class->get_translated_array();
		
			$settings = $this->get_settings_for_display();
            //$active_contract = get_option("smartnft_active_contract_address",false);
            $categories = $this->get_categoriess($settings['limit']);
        ?>

		<style>
                    .collection-card.has-background{
                        background: #fff;
                    }
                    .collection-card.has-background .category-name{
                        font-size: 15px;
                        margin-top: 0px;
                        padding: 20px;
                        position: relative;
                        margin: 0;
                        bottom: auto;
                        left: auto;
                    }
					.collections-grid{
							grid-template-columns: repeat(<?php echo $settings['columns'] ?>,1fr); 
					}
		</style>

<div class="collections-grid category-grid">
    <?php foreach( $categories as $categorie ): ?>

        <a href="<?php echo $categorie['permalink']; ?>">
            <div class="collection-card has-background">
                <div class="collection-grid__top">
                    <?php if( !empty ( $categorie['collectionBanner'] ) ){ ?>
                        <figure class="collection-cover" style="background-image: url(<?php echo $categorie['collectionImg']; ?>)"></figure>
					<?php }else{ ?>
                        <figure class="collection-cover" style="background-image: url()"></figure>
                    <?php } ?>
                    <h3 class="category-name "><?php echo $categorie['name']; ?></h3>
                </div>
            </div>
        </a>

    <?php endforeach; ?>
</div>        
<?php	}

}

