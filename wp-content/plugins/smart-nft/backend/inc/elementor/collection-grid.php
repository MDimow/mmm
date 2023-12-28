<?php

class SMARTNFT_COLLECTION_GRID extends \Elementor\Widget_Base 
{
	public function get_name() {
		return 'smartnft_collection_grid';
	}

	public function get_title() {
		return esc_html__( 'Collections grid', WP_SMART_NFT );
	}

	public function get_icon() {
		return 'eicon-calendar';
	}

	public function get_categories() {
		return [ 'Smart_NFT' ];
	}

	function get_collections ($limit) {
            $args = array (
                'taxonomy'      => 'smartnft_collection',
                'number'        => intval( $limit ),
                'offset' 		=> 0,
                'hide_empty'    => false,
            );

            $terms = get_terms( $args );

            $response = [];
            
            foreach( $terms as $term ) {
                $term_id = $term->term_id;

				//post ids that use this term
				$post_ids = get_posts(
					array(
						'post_type' => 'smartnft',
						'tax_query' => array( array( 'taxonomy' => 'smartnft_collection', 'field' => 'term_id','terms'=> $term_id ) ),
						'fields'	=> 'ids'
					)
				);
                
				//default value
				$flour_price = 0;
				$total_volume = 0;
				$total_owners = [];
				$listed_amount = 0;
				$unlisted_amount = 0;
				
				$term_meta = get_term_meta( $term->term_id, "collection_meta", true );
				
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
				
							//update flour price if cur price is less then previous flour price 
							if( $flour_price > $price || $flour_price == 0 ) { $flour_price = $price; }
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
								//update flour price if cur price is less then previous flour price 
								if($flour_price > $price || $flour_price == 0 ) { $flour_price = $price; }
							}
						}	
				}

                $response[] = array(
                    'total_vol'        => $total_volume,
                    'floor_price'      => $flour_price,
                    'owners'           => count( $total_owners ),
                    'creator'          => $term_meta['creator'],
                    'name'             => $term->name,
                    'permalink'        => get_term_link($term_id),
                    'collectionImg'    => $term_meta['profileImg'],
                    'collectionBanner' => $term_meta['bannerImg'],
                    'contract'         => $term_meta['contractAddress'],
                    'count'            => $term->count,
					'currencySymbol'   => $term_meta['network']['currencySymbol'],
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
					'{{WRAPPER}} .collection-card figure.collection-cover' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'banner_border_radius',
			[
				'label' => esc_html__( 'Banner border radius', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .collection-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'profile_border_radius',
			[
				'label' => esc_html__( 'Profile border radius', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .collection-card__collection-photo' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'profile_spaceing',
			[
				'label' => esc_html__( 'Profile spacing', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .collection-card__collection-photo' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .collection-card__collection-photo__noimg' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
    
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'name_typography',
				'label' => esc_html__( 'Name typography', WP_SMART_NFT),
				'selector' => '{{WRAPPER}} .collection-card__head p',
			]
		);

		$this->add_control(
			'name_color',
			[
				'label' => esc_html__( 'Name Color', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .collection-card__head p' => 'color: {{VALUE}}',
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
					'{{WRAPPER}} .collection-card__head p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'bottom_part_padding',
			[
				'label' => esc_html__( 'Content padding', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .collection-card__info' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'bottom_part_background',
				'label' => esc_html__( 'Content background', WP_SMART_NFT),
				'types' => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} .collection-card',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'meta_title_typography',
				'label' => esc_html__( 'Meta title typography', WP_SMART_NFT),
				'selector' => '{{WRAPPER}} .collection-card__info p',
			]
		);

		$this->add_control(
			'meta_title_color',
			[
				'label' => esc_html__( 'Meta title Color', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .collection-card__info p' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'meta_value_typography',
				'label' => esc_html__( 'Meta value typography', WP_SMART_NFT),
				'selector' => '{{WRAPPER}} .collection-card__info p.collection-card__info__value',
			]
		);

		$this->add_control(
			'meta_value_color',
			[
				'label' => esc_html__( 'Meta value Color', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .collection-card__info p.collection-card__info__value' => 'color: {{VALUE}} !important',
				],
			]
		);

    $this->end_controls_section();
	}

	protected function render(){
		$translation_class = new Smartnft_Translation_Manager();
		$translation = $translation_class->get_translated_array();

	  $settings = $this->get_settings_for_display();
      $collections = $this->get_collections($settings['limit']);
?>

		<style>
			.collections-grid.collections-grid-main{
					grid-template-columns: repeat(<?php echo $settings['columns'] ?>,1fr); 
			}
		</style>

<div class="collections-grid collections-grid-main">
    <?php foreach( $collections as $collection ): ?>

        <a href="<?php echo $collection['permalink']; ?>">
            <div class="collection-card">
				<!-- <a href="#"> -->
					<figure
					class="collection-cover"
					style="background-image: url(<?php echo $collection['collectionBanner']; ?>)"
					></figure>
				<!-- </a> -->
				<!-- <a href="#"> -->
					<div>
						<div class="collection-card__head">
							<?php if( !empty ( $collection['collectionImg'][0] ) ): ?>
								<img class="collection-card__collection-photo" src="<?php echo   $collection['collectionImg']; ?>" alt="<?php echo $collection['name']; ?>">
							<?php endif; ?>
							<?php if( empty ( $collection['collectionImg'][0] ) ): ?>
								<span class="collection-card__collection-photo__noimg"></span>
							<?php endif; ?>
							<p><?php echo $collection['name']; ?></p>
						</div>
						<div class="collection-card__info">
							<div class="floor">
								<p><?php echo $translation['floor']; ?></p>
								<p class="collection-card__info__value">
									<?php echo $collection['floor_price']  ?> <?php echo $collection['currencySymbol'];?>
								</p>
							</div>
							<div class="total">
								<p><?php echo $translation['volume']; ?></p>
								<p class="collection-card__info__value">
									<?php echo $collection['total_vol']; ?> <?php echo $collection['currencySymbol']; ?>
								</p>
							</div>
							<div class="items">
								<p><?php echo $translation['owners']; ?></p>
								<p class="collection-card__info__value">
									<?php echo $collection['owners'] ?>
								</p>
							</div>
						</div>
					</div>
				<!-- </a> -->
            </div>
        </a>

    <?php endforeach; ?>
</div>        
<?php	}

}

