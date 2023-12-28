<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class SMARTNFT_COLLECTION_CAROUSEL_TWO extends \Elementor\Widget_Base 
{
	public function get_name() {
		return 'smartnft_collection_carousel_2';
	}

	public function get_title() {
		return esc_html__( 'Collection carousel 2', WP_SMART_NFT );
	}

	public function get_icon() {
		return 'eicon-calendar';
	}

	public function get_categories() {
		return [ 'Smart_NFT' ];
	}

	public function get_script_depends() {

			wp_register_script(
			  "smartnft_front_owlcarousel",
				FRONTEND_SCRIPT_URL . 'owl.carousel.min.js',
				array("jquery"),
				false,
				true
			);

			return [
				'smartnft_front_owlcarousel',
			];
	}

	public function get_style_depends () {
		//style
		wp_register_style(
			"owlCarousel_main_style",
			 FRONTEND_STYLE_URL . 'owl.carousel.min.css',
		);

		wp_register_style(
			"owlCarousel_theme_style",
			 FRONTEND_STYLE_URL . 'owl.theme.default.min.css',
		);

		return ["owlCarousel_main_style","owlCarousel_theme_style"];

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
            
            foreach( $terms as $term ){
                $term_id = $term->term_id;
                // Collection Data
				$term_meta = get_term_meta( $term_id, "collection_meta", true );
				$term_meta["permalink"] = get_term_link( $term_id, "smartnft_collection" );
                $response[] = $term_meta;
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
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 100,
				'step' => 100,
				'default' => 20,
			]
		);

    $this->add_control(
			'carousel_style',
			[
				'label' => esc_html__( 'Carousel Style', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'modern',
				'options' => [
					'modern'  => esc_html__( 'Modern', WP_SMART_NFT ),
					'classic'  => esc_html__( 'Classic', WP_SMART_NFT ),
				],
			]
		);

    $this->add_control(
			'carousel_auto_play_timeout',
			[
				'label' => esc_html__( 'Autoplay timeout(MS)', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 1000,
				'max' => 10000,
				'step' => 500,
				'default' => 5000,
			]
		);

    $this->add_control(
			'carousel_auto_play',
			[
				'label' => esc_html__( 'Carousel autoplay', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', WP_SMART_NFT ),
				'label_off' => esc_html__( 'Hide', WP_SMART_NFT ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'carousel_border_radius',
			[
				'label' => esc_html__( 'Carousel full border radius', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .collections-grid .owl-stage-outer' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
    $this->end_controls_section();


		$this->start_controls_section(
			'style_section',
			[
				'label' => esc_html__( 'Modern card Style', WP_SMART_NFT ),
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
					'{{WRAPPER}} .collection-card figure.collection-cover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .collection-card__collection-photo__noimg' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .collection-card__collection-photo__noimg' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .collection-card__collection-photo' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
		$this->add_control(
			'card_border_radius',
			[
				'label' => esc_html__( 'Card border radius', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .collection-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				'name' => 'meta_valur_typography',
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

		$this->start_controls_section(
			'classic_style_section',
			[
				'label' => esc_html__( 'Classic card Style', WP_SMART_NFT ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'classic_profile_height',
			[
				'label' => esc_html__( 'Profile height', WP_SMART_NFT ),
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
					'{{WRAPPER}} .collections-grid .collection-grid-classic img.profile' => 'height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .collections-grid .collection-grid-classic .no-profile' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'classic_profile_border_radius',
			[
				'label' => esc_html__( 'Profile border radius', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .collections-grid .collection-grid-classic' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
    
		$this->add_control(
			'classic_profile_padding',
			[
				'label' => esc_html__( 'Profile padding', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .collections-grid .collection-grid-classic div.info' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'classic_name_typography',
				'label' => esc_html__( 'Name typography', WP_SMART_NFT),
				'selector' => '{{WRAPPER}} .collections-grid .collection-grid-classic .name',
			]
		);
    
		$this->add_control(
			'classic_name_color',
			[
				'label' => esc_html__( 'Name Color', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .collections-grid .collection-grid-classic .name' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'classic_name_spacing',
			[
				'label' => esc_html__( 'Name spacing', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .collections-grid .collection-grid-classic .name' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
    
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'classic_meta_typography',
				'label' => esc_html__( 'Meta typography', WP_SMART_NFT),
				'selector' => '{{WRAPPER}} .collections-grid .collection-grid-classic div.info div',
			]
		);
    
		$this->add_control(
			'classic_meta_color',
			[
				'label' => esc_html__( 'Meta Color', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .collections-grid .collection-grid-classic div.info div' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'classic_meta_spacing',
			[
				'label' => esc_html__( 'Meta spacing', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .collections-grid .collection-grid-classic div.info div' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

    $this->end_controls_section();

	$this->start_controls_section(
			'arrow_controll_section',
			[
				'label' => esc_html__( 'Arrow controll', WP_SMART_NFT ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'arrow_font_size',
			[
				'label' => esc_html__( 'Arrow font size', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .owl-carousel .owl-nav button.owl-next' => 'font-size: {{SIZE}}{{UNIT}} !important;',
					'{{WRAPPER}} .owl-carousel .owl-nav button.owl-prev' => 'font-size: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);
		$this->add_control(
			'arrow_height_size',
			[
				'label' => esc_html__( 'Arrow height', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .owl-carousel .owl-nav button.owl-next' => 'height: {{SIZE}}{{UNIT}} !important;',
					'{{WRAPPER}} .owl-carousel .owl-nav button.owl-prev' => 'height: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);
		$this->add_control(
			'arrow_width_size',
			[
				'label' => esc_html__( 'Arrow width', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .owl-carousel .owl-nav button.owl-next' => 'width: {{SIZE}}{{UNIT}} !important;',
					'{{WRAPPER}} .owl-carousel .owl-nav button.owl-prev' => 'width: {{SIZE}}{{UNIT}} !important;',
				],
			]
		);

		$this->add_control(
			'arrow_font_color',
			[
				'label' => esc_html__( 'Arrow font color', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .owl-carousel .owl-nav button.owl-next' => 'color: {{VALUE}} !important;',
					'{{WRAPPER}} .owl-carousel .owl-nav button.owl-prev' => 'color: {{VALUE}} !important;',
				],
			]
		);
		$this->add_control(
			'arrow_bg_color',
			[
				'label' => esc_html__( 'Arrow bg color', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .owl-carousel .owl-nav button.owl-next' => 'background: {{VALUE}} !important;',
					'{{WRAPPER}} .owl-carousel .owl-nav button.owl-prev' => 'background: {{VALUE}} !important;',
				],
			]
		);
		$this->add_control(
			'arrow_icon_margin',
			[
				'label' => esc_html__( 'Arrow icon margin', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .owl-carousel .owl-nav button.owl-next span' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .owl-carousel .owl-nav button.owl-prev span' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'arrow_right_margin',
			[
				'label' => esc_html__( 'Arrow right circle margin', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .owl-carousel .owl-nav button.owl-next' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'arrow_left_margin',
			[
				'label' => esc_html__( 'Arrow left circle margin', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .owl-carousel .owl-nav button.owl-prev' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'label' => esc_html__( 'Arrow box shadow', WP_SMART_NFT ),
				'name' => 'arrow_box_shadow',
				'selector' => '{{WRAPPER}} .owl-carousel .owl-nav button',
			]
		);

    $this->end_controls_section();



	}

	protected function render(){
		$translation_class = new Smartnft_Translation_Manager();
		$translation = $translation_class->get_translated_array();

		$settings = $this->get_settings_for_display();
		$collections = $this->get_collections($settings['limit']);
		$unique_id = "owl-carousel-" . uniqid();
?>

		<style>
			.collections-grid.<?php echo $unique_id; ?>{
					grid-template-columns: repeat(<?php echo $settings['columns'] ?>,1fr); 
					gap: <?php echo $settings['columns_gap'] . 'px'; ?>
			}
			.elementor-editor-active .collections-grid.owl-carousel{
				display:grid;
			}
		</style>

    <div class= "<?php echo  "collections-grid owl-theme owl-carousel " . $unique_id ?>" >
    <?php foreach( $collections as $collection ): ?>
        
        <?php if($settings['carousel_style'] === 'modern'): ?>

			<div class="collection-card">
				<a href="<?php echo $collection['permalink']; ?>">
					<figure
						class="collection-cover"
						style="background-image: url(<?php echo $collection['bannerImg']; ?>)"
					></figure>
				</a>
				<a href="<?php echo $collection['permalink']; ?>">
					<div>
						<div class="collection-card__head">
							<?php if( !empty ( $collection['profileImg'] ) ): ?>
								<img
									class="collection-card__collection-photo"
									src="<?php echo $collection['profileImg']; ?>"
								/>
							<?php else: ?>
								<span class="collection-card__collection-photo__noimg"></span>
							<?php endif; ?>
							<p><?php echo $collection['name']; ?></p>
						</div>
						<div class="collection-card__info">
							<div class="floor">
								<p><?php echo $translation['floor']; ?></p>
								<p class="collection-card__info__value"><?php echo $collection['flour_price']; ?></p>
							</div>
							<div class="total">
								<p><?php echo $translation['volume']; ?></p>
								<p class="collection-card__info__value"><?php echo $collection['total_volume']; ?></p>
							</div>
							<div class="items">
								<p><?php echo $translation['owners']; ?></p>
								<p class="collection-card__info__value"><?php echo count($collection['total_owners']); ?></p>
							</div>
						</div>
					</div>
				</a>
			</div>

        <?php else: ?>

            <a href="<?php echo $collection['permalink']; ?>">
                <div class="collection-grid-classic">
                        <?php if( !empty ( $collection['profileImg'] ) ): ?>
                            <img class="profile" src="<?php echo   $collection['profileImg'];?>" alt="<?php echo $collection['name']; ?>">
                        <?php else: ?>
                            <figure class="no-profile"></figure>
                        <?php endif; ?>
                        <div class="info">
                            <h2 class="name"><?php echo $collection['name']; ?></h2>
                            <div>
                                <p><?php echo $translation['floor']; ?></p>
                                <span><?php echo $collection['flour_price']; ?> <?php echo $collection['network']['currencySymbol']; ?></span>
                            </div>
                        </div>
                </div>
            </a>

        <?php endif; ?>
        

    <?php endforeach; ?>
</div>        

      <script>
		(()=>{
			function initSlider(){
           		const options = {
		   			margin:<?php echo intval( $settings['columns_gap'] ); ?>,
		   			autoplayTimeout:parseInt(<?php echo $settings["carousel_auto_play_timeout"]; ?>),
					smartSpeed:1000,
		   			responsive:{
		   				0:{
		   					nav:false,
		   					items:1,
		   					dots:true,
		   					autoplay:true,
		   				},
		   				700:{
		   					nav:false,
		   					items:2,
		   					dots:true,
		   					autoplay:true,
		   				},
		   				900:{
		   					items:2,
		   					nav:false,
		   					dots:true,
		   					autoplay:true,
		   				},
		   				1000:{
		   					items:3,
		   					autoplay:true,
		   				},
		   				1001:{
		   					items:<?php echo intval( $settings['columns'] ) ?>,
		   					loop:true,
		   					nav:true,
		   					dots:false,
		   					autoplay:<?php echo $settings['carousel_auto_play'] === "yes" ? 1 : 0 ?>,
		   				}
		   			}
		   		}
           		jQuery(".<?php echo $unique_id; ?>").owlCarousel(options);
			}

			if(typeof elementor !== "undefined"){
				elementor.hooks.addAction( 'panel/open_editor/widget/smartnft_collection_carousel_2', function() {
					initSlider();
				});
			}

			window.addEventListener('DOMContentLoaded', function(){
				initSlider();
    		});

		})();
      </script>
<?php	}

}

