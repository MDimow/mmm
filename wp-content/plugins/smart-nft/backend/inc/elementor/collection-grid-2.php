<?php

class SMARTNFT_COLLECTION_GRID_Two extends \Elementor\Widget_Base 
{
	public function get_name() {
		return 'smartnft_collection_grid_two';
	}

	public function get_title() {
		return esc_html__( 'Collections grid 2', WP_SMART_NFT );
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
				$term_meta = get_term_meta( $term->term_id, "collection_meta", true );
                $response[] = array(
                    'total_vol'        => $term_meta["total_volume"],
                    'floor_price'      => $term_meta["flour_price"],
                    'owners'           => count( $term_meta["total_owners"] ),
                    'creator'          => $term_meta['creator'],
                    'name'             => $term->name,
                    'permalink'        => get_term_link($term_id),
                    'collectionImg'    => $term_meta['profileImg'],
                    'collectionBanner' => $term_meta['bannerImg'],
                    'contract'         => $term_meta['contractAddress'],
                    'count'            => $term->count,
					'currencySymbol'   => $term_meta['network']['currencySymbol'],
					'verified'   	   => !empty( $term_meta['verified'] ) ? $term_meta["verified"] : false,
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
			'rows',
			[
				'label' => esc_html__( 'Rows', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 100,
				'step' => 100,
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
					'size' => 15,
				],
				'selectors' => [
					'{{WRAPPER}} .smartnft-coll-grid-two' => 'column-gap: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}}  .smartnft-coll-grid-two' => 'row-gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'coll_bottom_border_color',
			[
				'label' => esc_html__( 'Border bottom color', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .smartnft-coll-grid-two div.grid-con' => 'border-bottom: 1px solid {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'coll_bottom_border_padding',
			[
				'label' => esc_html__( 'Border bottom padding', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .smartnft-coll-grid-two div.grid-con' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);


   		$this->end_controls_section();

		$this->start_controls_section(
			'counter_style_section',
			[
				'label' => esc_html__( 'Counter style', WP_SMART_NFT ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'label' => esc_html__( 'Counter Typography', WP_SMART_NFT ),
				'name' => 'couner_typography',
				'selector' => '{{WRAPPER}} .smartnft-coll-grid-two div.grid-con span.number',
			]
		);

		$this->add_control(
			'counter_color',
			[
				'label' => esc_html__( 'Counter Color', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .smartnft-coll-grid-two div.grid-con span.number' => 'color: {{VALUE}}',
				],
			]
		);
   		$this->end_controls_section();

		$this->start_controls_section(
			'coll_img_style_section',
			[
				'label' => esc_html__( 'Collection profile style', WP_SMART_NFT ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'coll_img_width',
			[
				'label' => esc_html__( 'Width', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 60,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 40,
				],
				'selectors' => [
					'{{WRAPPER}} .smartnft-coll-grid-two div.grid-con img.collimg' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .smartnft-coll-grid-two div.grid-con span.noimg' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'coll_img_height',
			[
				'label' => esc_html__( 'Height', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 70,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 40,
				],
				'selectors' => [
					'{{WRAPPER}} .smartnft-coll-grid-two div.grid-con img.collimg' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .smartnft-coll-grid-two div.grid-con span.noimg' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'coll_img_border_radius',
			[
				'label' => esc_html__( 'Border radius', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .smartnft-coll-grid-two div.grid-con img.collimg' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .smartnft-coll-grid-two div.grid-con span.noimg' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

   		$this->end_controls_section();


		$this->start_controls_section(
			'coll_name_style_section',
			[
				'label' => esc_html__( 'Collection name', WP_SMART_NFT ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'label' => esc_html__( 'Typography', WP_SMART_NFT ),
				'name' => 'coll_name_typography',
				'selector' => '{{WRAPPER}} .smartnft-coll-grid-two div.grid-con h3',
			]
		);
		$this->add_control(
			'coll_name_color',
			[
				'label' => esc_html__( 'Color', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .smartnft-coll-grid-two div.grid-con h3' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'coll_name_margin',
			[
				'label' => esc_html__( 'Margin', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .smartnft-coll-grid-two div.grid-con h3' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} 0px {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .smartnft-coll-grid-two div.grid-con span.floor' => 'margin-top: {{BOTTOM}}{{UNIT}};',
					'{{WRAPPER}} .smartnft-coll-grid-two div.grid-con span.count' => 'margin-top: {{BOTTOM}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'coll_verified_tick_width',
			[
				'label' => esc_html__( 'Verified tick size', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 30,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 12,
				],
				'selectors' => [
					'{{WRAPPER}} .smartnft-coll-grid-two div.grid-con h3 img' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'coll_verified_tick_margin',
			[
				'label' => esc_html__( 'Verified tick margin', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .smartnft-coll-grid-two div.grid-con h3 img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

   		$this->end_controls_section();
		
		$this->start_controls_section(
			'coll_meta_style_section',
			[
				'label' => esc_html__( 'Collection meta', WP_SMART_NFT ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'label' => esc_html__( 'Meta Name Typography', WP_SMART_NFT ),
				'name' => 'meta_name_typography',
				'selector' => '{{WRAPPER}} .smartnft-coll-grid-two div.grid-con span.count,.smartnft-coll-grid-two div.grid-con span.floor',
			]
		);

		$this->add_control(
			'meta_name_color',
			[
				'label' => esc_html__( 'Meta Name Color', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .smartnft-coll-grid-two div.grid-con span.count' => 'color: {{VALUE}}',
					'{{WRAPPER}} .smartnft-coll-grid-two div.grid-con span.floor' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'label' => esc_html__( 'Meta Value Typography', WP_SMART_NFT ),
				'name' => 'meta_value_typography',
				'selector' => '{{WRAPPER}} .smartnft-coll-grid-two div.grid-con span.count span,.smartnft-coll-grid-two div.grid-con span.floor span,.smartnft-coll-grid-two div.grid-con span.volume',
			]
		);

		$this->add_control(
			'meta_value_color',
			[
				'label' => esc_html__( 'Meta Value Color', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .smartnft-coll-grid-two div.grid-con span.count span' => 'color: {{VALUE}}',
					'{{WRAPPER}} .smartnft-coll-grid-two div.grid-con span.floor span' => 'color: {{VALUE}}',
					'{{WRAPPER}} .smartnft-coll-grid-two div.grid-con span.volume' => 'color: {{VALUE}}',
				],
			]
		);

   		$this->end_controls_section();


	}

	protected function render(){
		$translation_class = new Smartnft_Translation_Manager();
		$translation = $translation_class->get_translated_array();

	  $settings = $this->get_settings_for_display();
      $collections = $this->get_collections(!empty($settings['limit'] )? $settings['limit'] : 12);
	  $id = uniqid("smartnft");
	  $repeat_colms = $settings["columns"];
	  $repeat_rows =  $settings["rows"];
?>

		<style>
		<?php echo "#".$id ?>.smartnft-coll-grid-two{
 				display: grid;
				grid-template-columns:repeat( <?php echo $repeat_colms; ?>, 1fr );
 				grid-auto-rows: minmax(70px, auto);
 				grid-auto-flow: column;
				grid-template-rows:repeat( <?php echo $repeat_rows; ?>, 70px);
			}	
		</style>

		<div class="smartnft-coll-grid-two" id="<?php echo $id; ?>">

			<?php foreach( $collections as $index => $collec ): ?>
				<a href=<?php echo $collec["permalink"] ?>>
				  <div class="grid-con">

				  	<span class="number"><?php esc_html_e($index + 1, WP_SMART_NFT) ;?></span>

				  	<?php if( !empty( $collec["collectionImg"] ) ): ?>
				  		<img class="collimg"src=<?php echo  $collec["collectionImg"]; ?>  alt=<?php echo $collec["name"]; ?>>
				  	<?php else: ?>
				  		<span class="noimg"></span>
				  	<?php endif;?>

				  	<h3>
				  		<?php echo $collec["name"]; ?>
	  			  		<?php if( $collec["verified"] ) : ?>
				  			<img src=<?php echo FRONTEND_MEDIA_URL . "verified.svg" ?> alt="verified">
				  		<?php endif; ?>
				  		<span class="floor"> 
				  			<?php echo $translation['floor_price']; ?>
				  			<span>
				  			   <?php echo $collec["floor_price"] . " " . $collec["currencySymbol"]; ?>
				  			</span>
				  		</span>
				  	</h3>

				  	<span class="volume">
				  		<?php echo
	  			  	   		$collec["total_vol"] > 999 ?
				  		   	$collec["total_vol"] / 1000 . "k " . $collec["currencySymbol"] :
				  			$collec["total_vol"] . " " . $collec["currencySymbol"]; 
				  		?>
				  		<span class="count">
				  			<?php echo $translation['items']; ?>
	  			  			<span>
				  				<?php echo
	  			  					$collec["count"] > 999 ?
				  				   	$collec["count"] / 1000 . "k" :
				  					$collec["count"]; 
				  				?>
 				  			</span>
				  		</span>
				  	</span>

				  </div>
				</a>
			<?php endforeach; ?>

		</div>

<?php }


}

