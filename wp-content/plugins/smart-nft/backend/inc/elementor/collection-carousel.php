<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class SMARTNFT_COLLECTION_CAROUSEL extends \Elementor\Widget_Base {

	public function get_name() {
		return 'image-carousel';
	}
    public function get_categories() {
		return [ 'Smart_NFT' ];
	}
	public function get_title() {
		return esc_html__( 'Collection Carousel', WP_SMART_NFT );
	}
	public function get_icon() {
		return 'eicon-slider-push';
	}
	public function get_keywords() {
		return [ 'collection', 'carousel', 'slider' ];
	}
	protected function register_controls() {
		$this->start_controls_section(
			'section_image_carousel',
			[
				'label' => esc_html__( 'Collection Carousel', WP_SMART_NFT ),
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
		$slides_to_show = range( 1, 10 );
		$slides_to_show = array_combine( $slides_to_show, $slides_to_show );

		$this->add_responsive_control(
			'slides_to_show',
			[
				'label' => esc_html__( 'Slides to Show', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'' => esc_html__( 'Default', WP_SMART_NFT ),
				] + $slides_to_show,
				'default' => 4,
				'frontend_available' => true,
				'render_type' => 'template',
				'selectors' => [
					'{{WRAPPER}}' => '--e-image-carousel-slides-to-show: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'slides_to_scroll',
			[
				'label' => esc_html__( 'Slides to Scroll', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'description' => esc_html__( 'Set how many slides are scrolled per swipe.', WP_SMART_NFT ),
				'options' => [
					'' => esc_html__( 'Default', WP_SMART_NFT ),
				] + $slides_to_show,
				'condition' => [
					'slides_to_show!' => '1',
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'navigation',
			[
				'label' => esc_html__( 'Navigation', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'both',
				'options' => [
					'both' => esc_html__( 'Arrows and Dots', WP_SMART_NFT ),
					'arrows' => esc_html__( 'Arrows', WP_SMART_NFT ),
					'dots' => esc_html__( 'Dots', WP_SMART_NFT ),
					'none' => esc_html__( 'None', WP_SMART_NFT ),
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'navigation_previous_icon',
			[
				'label' => esc_html__( 'Previous Arrow Icon', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'skin' => 'inline',
				'label_block' => false,
				'skin_settings' => [
					'inline' => [
						'none' => [
							'label' => 'Default',
							'icon' => 'eicon-chevron-left',
						],
						'icon' => [
							'icon' => 'eicon-star',
						],
					],
				],
				'recommended' => [
					'fa-regular' => [
						'arrow-alt-circle-left',
						'caret-square-left',
					],
					'fa-solid' => [
						'angle-double-left',
						'angle-left',
						'arrow-alt-circle-left',
						'arrow-circle-left',
						'arrow-left',
						'caret-left',
						'caret-square-left',
						'chevron-circle-left',
						'chevron-left',
						'long-arrow-alt-left',
					],
				],
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'navigation',
							'operator' => '=',
							'value' => 'both',
						],
						[
							'name' => 'navigation',
							'operator' => '=',
							'value' => 'arrows',
						],
					],
				],
			]
		);

		$this->add_control(
			'navigation_next_icon',
			[
				'label' => esc_html__( 'Next Arrow Icon', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'skin' => 'inline',
				'label_block' => false,
				'skin_settings' => [
					'inline' => [
						'none' => [
							'label' => 'Default',
							'icon' => 'eicon-chevron-right',
						],
						'icon' => [
							'icon' => 'eicon-star',
						],
					],
				],
				'recommended' => [
					'fa-regular' => [
						'arrow-alt-circle-right',
						'caret-square-right',
					],
					'fa-solid' => [
						'angle-double-right',
						'angle-right',
						'arrow-alt-circle-right',
						'arrow-circle-right',
						'arrow-right',
						'caret-right',
						'caret-square-right',
						'chevron-circle-right',
						'chevron-right',
						'long-arrow-alt-right',
					],
				],
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'navigation',
							'operator' => '=',
							'value' => 'both',
						],
						[
							'name' => 'navigation',
							'operator' => '=',
							'value' => 'arrows',
						],
					],
				],
			]
		);

		$this->add_control(
			'image_spacing',
			[
				'label' => esc_html__( 'Spacing', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'' => esc_html__( 'Default', WP_SMART_NFT ),
					'custom' => esc_html__( 'Custom', WP_SMART_NFT ),
				],
				'default' => 'custom',
				'condition' => [
					'slides_to_show!' => '1',
				],
			]
		);
		$this->add_control(
			'image_spacing_custom',
			[
				'label' => esc_html__( 'Image Spacing', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 100,
					],
				],
				'default' => [
					'size' => 20,
				],
				'show_label' => false,
				'condition' => [
					'image_spacing' => 'custom',
					'slides_to_show!' => '1',
				],
				'frontend_available' => true,
				'render_type' => 'none',
			]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_additional_options',
			[
				'label' => esc_html__( 'Additional Options', WP_SMART_NFT ),
			]
		);

		$this->add_control(
			'autoplay',
			[
				'label' => esc_html__( 'Autoplay', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'yes',
				'options' => [
					'yes' => esc_html__( 'Yes', WP_SMART_NFT ),
					'no' => esc_html__( 'No', WP_SMART_NFT ),
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'pause_on_hover',
			[
				'label' => esc_html__( 'Pause on Hover', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'yes',
				'options' => [
					'yes' => esc_html__( 'Yes', WP_SMART_NFT ),
					'no' => esc_html__( 'No', WP_SMART_NFT ),
				],
				'condition' => [
					'autoplay' => 'yes',
				],
				'render_type' => 'none',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'pause_on_interaction',
			[
				'label' => esc_html__( 'Pause on Interaction', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'yes',
				'options' => [
					'yes' => esc_html__( 'Yes', WP_SMART_NFT ),
					'no' => esc_html__( 'No', WP_SMART_NFT ),
				],
				'condition' => [
					'autoplay' => 'yes',
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'autoplay_speed',
			[
				'label' => esc_html__( 'Autoplay Speed', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'default' => 5000,
				'condition' => [
					'autoplay' => 'yes',
				],
				'render_type' => 'none',
				'frontend_available' => true,
			]
		);

		// Loop requires a re-render so no 'render_type = none'
		$this->add_control(
			'infinite',
			[
				'label' => esc_html__( 'Infinite Loop', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'yes',
				'options' => [
					'yes' => esc_html__( 'Yes', WP_SMART_NFT ),
					'no' => esc_html__( 'No', WP_SMART_NFT ),
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'effect',
			[
				'label' => esc_html__( 'Effect', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'slide',
				'options' => [
					'slide' => esc_html__( 'Slide', WP_SMART_NFT ),
					'fade' => esc_html__( 'Fade', WP_SMART_NFT ),
				],
				'condition' => [
					'slides_to_show' => '1',
				],
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'speed',
			[
				'label' => esc_html__( 'Animation Speed', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'default' => 500,
				'render_type' => 'none',
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'direction',
			[
				'label' => esc_html__( 'Direction', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'ltr',
				'options' => [
					'ltr' => esc_html__( 'Left', WP_SMART_NFT ),
					'rtl' => esc_html__( 'Right', WP_SMART_NFT ),
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_navigation',
			[
				'label' => esc_html__( 'Navigation', WP_SMART_NFT ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition' => [
					'navigation' => [ 'arrows', 'dots', 'both' ],
				],
			]
		);

		$this->add_control(
			'heading_style_arrows',
			[
				'label' => esc_html__( 'Arrows', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'navigation' => [ 'arrows', 'both' ],
				],
			]
		);

		$this->add_control(
			'arrows_position',
			[
				'label' => esc_html__( 'Position', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'inside',
				'options' => [
					'inside' => esc_html__( 'Inside', WP_SMART_NFT ),
					'outside' => esc_html__( 'Outside', WP_SMART_NFT ),
				],
				'prefix_class' => 'elementor-arrows-position-',
				'condition' => [
					'navigation' => [ 'arrows', 'both' ],
				],
			]
		);

		$this->add_control(
			'arrows_size',
			[
				'label' => esc_html__( 'Size', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 20,
						'max' => 60,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-swiper-button.elementor-swiper-button-prev, {{WRAPPER}} .elementor-swiper-button.elementor-swiper-button-next' => 'font-size: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'navigation' => [ 'arrows', 'both' ],
				],
			]
		);
		$this->add_control(
			'arrows_position_vertical',
			[
				'label' => esc_html__( 'Vertical Position', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
					],
				],
				'default' => [
					'size' => -20,
					'unit'	=> 'px'
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-swiper-button.elementor-swiper-button-prev' => 'left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .elementor-swiper-button.elementor-swiper-button-next' => 'right: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'navigation' => [ 'arrows', 'both' ],
				],
			]
		);

		$this->add_control(
			'arrows_color',
			[
				'label' => esc_html__( 'Color', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-swiper-button.elementor-swiper-button-prev, {{WRAPPER}} .elementor-swiper-button.elementor-swiper-button-next' => 'color: {{VALUE}};',
					'{{WRAPPER}} .elementor-swiper-button.elementor-swiper-button-prev svg, {{WRAPPER}} .elementor-swiper-button.elementor-swiper-button-next svg' => 'fill: {{VALUE}};',
				],
				'condition' => [
					'navigation' => [ 'arrows', 'both' ],
				],
			]
		);

		$this->add_control(
			'arrows_bg_color',
			[
				'label' => esc_html__( 'Background Color', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .elementor-swiper-button.elementor-swiper-button-prev, {{WRAPPER}} .elementor-swiper-button.elementor-swiper-button-next' => 'background-color: {{VALUE}};'
				],
				'default' => '#656565',
				'condition' => [
					'navigation' => [ 'arrows', 'both' ],
				],
			]
		);
		$this->add_control(
			'arrows_padding',
			[
				'label' => esc_html__( 'Padding', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 60,
					],
				],
				'default' => [
					'size' => 10,
					'unit'	=> 'px'
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-swiper-button.elementor-swiper-button-prev, {{WRAPPER}} .elementor-swiper-button.elementor-swiper-button-next' => 'padding: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'navigation' => [ 'arrows', 'both' ],
				],
			]
		);
		$this->add_control(
			'arrows_radius',
			[
				'label' => esc_html__( 'Border Radius', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'default' => [
					'size' => 30,
					'unit'	=> 'px'
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-swiper-button.elementor-swiper-button-prev, {{WRAPPER}} .elementor-swiper-button.elementor-swiper-button-next' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'navigation' => [ 'arrows', 'both' ],
				],
			]
		);
		$this->add_control(
			'heading_shadow_arrows',
			[
				'label' => esc_html__( 'Box Shadow', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_control(
			'arrows_shadow',
			[
				'label' => esc_html__( 'Shadow', 'textdomain' ),
				'type' => \Elementor\Controls_Manager::BOX_SHADOW,
				'selectors' => [
					'{{WRAPPER}} .elementor-swiper-button.elementor-swiper-button-prev, {{WRAPPER}} .elementor-swiper-button.elementor-swiper-button-next' => 'box-shadow: {{HORIZONTAL}}px {{VERTICAL}}px {{BLUR}}px {{SPREAD}}px {{COLOR}};',
				],
			]
		);
		$this->add_control(
			'heading_style_dots',
			[
				'label' => esc_html__( 'Pagination', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'navigation' => [ 'dots', 'both' ],
				],
			]
		);

		$this->add_control(
			'dots_position',
			[
				'label' => esc_html__( 'Position', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'outside',
				'options' => [
					'outside' => esc_html__( 'Outside', WP_SMART_NFT ),
					'inside' => esc_html__( 'Inside', WP_SMART_NFT ),
				],
				'prefix_class' => 'elementor-pagination-position-',
				'condition' => [
					'navigation' => [ 'dots', 'both' ],
				],
			]
		);

		$this->add_control(
			'dots_size',
			[
				'label' => esc_html__( 'Size', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 5,
						'max' => 10,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination-bullet' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'navigation' => [ 'dots', 'both' ],
				],
			]
		);

		$this->add_control(
			'dots_hr_position',
			[
				'label' => esc_html__( 'Horizontal Position', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => -40,
						'max' => 10,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .swiper-horizontal>.swiper-pagination-bullets, 
					{{WRAPPER}} .swiper-pagination-bullets.swiper-pagination-horizontal' => 'bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'navigation' => [ 'dots', 'both' ],
				],
			]
		);

		$this->add_control(
			'dots_inactive_color',
			[
				'label' => esc_html__( 'Color', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					// The opacity property will override the default inactive dot color which is opacity 0.2.
					'{{WRAPPER}} .swiper-pagination-bullet:not(.swiper-pagination-bullet-active)' => 'background: {{VALUE}}; opacity: 1',
				],
				'condition' => [
					'navigation' => [ 'dots', 'both' ],
				],
			]
		);

		$this->add_control(
			'dots_color',
			[
				'label' => esc_html__( 'Active Color', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .swiper-pagination-bullet' => 'background: {{VALUE}};',
				],
				'condition' => [
					'navigation' => [ 'dots', 'both' ],
				],
			]
		);

		$this->end_controls_section();

		// Card Style for Modern Style

		$this->start_controls_section(
			'section_style_image',
			[
				'label' => esc_html__( 'Card Style', WP_SMART_NFT ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition'	=> [
					'carousel_style' => 'modern'
				]
			]
		);

		$this->add_control(
			'heading_style_card',
			[
				'label' => esc_html__( 'Card settings', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::HEADING
			]
		);
		$this->add_responsive_control(
			'gallery_vertical_align',
			[
				'label' => esc_html__( 'Text Align', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Start', WP_SMART_NFT ),
						'icon' => 'eicon-h-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', WP_SMART_NFT ),
						'icon' => 'eicon-h-align-center',
					],
					'right' => [
						'title' => esc_html__( 'End', WP_SMART_NFT ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'default' => 'left',
				'condition' => [
					'slides_to_show!' => '1',
				],
				'selectors' => [
					'{{WRAPPER}} .collection-card' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'card_bg_color',
			[
				'label' => esc_html__( 'Background', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .collections-grid .collection-card' => 'background-color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'card_border_radius',
			[
				'label' => esc_html__( 'Border Radius', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
						'step' => 1,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .collections-grid .collection-card' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'after',
			]
		);
		$this->add_control(
			'heading_style_cover',
			[
				'label' => esc_html__( 'Cover image', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::HEADING
			]
		);
		$this->add_control(
			'banner_height',
			[
				'label' => esc_html__( 'Cover height', WP_SMART_NFT ),
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
					'{{WRAPPER}} .collections-grid .collection-card figure.collection-cover' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'cover_bg',
			[
				'label' => esc_html__( 'Background', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .collections-grid .collection-card figure.collection-cover' => 'background-color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'heading_style_profile',
			[
				'label' => esc_html__( 'Profile Image', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_control(
			'profile_height',
			[
				'label' => esc_html__( 'Profile image size', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 20,
						'max' => 200,
						'step' => 1,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 70,
				],
				'selectors' => [
					'{{WRAPPER}} .collection-card__collection-photo__noimg, {{WRAPPER}} .collection-card__collection-photo' => 'width: {{size}}{{UNIT}}; height: {{size}}{{UNIT}}',
				]
			]
		);
		$this->add_control(
			'profile_border_radius',
			[
				'label' => esc_html__( 'Profile Border Radius', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .collection-card__collection-photo__noimg, {{WRAPPER}} .collection-card__collection-photo' => 'border-radius: {{size}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'profile_image_border_radius',
				'selector' => '{{WRAPPER}} .collection-card__collection-photo__noimg, {{WRAPPER}} .collection-card__collection-photo',
			]
		);
		$this->add_control(
			'heading_style_collection',
			[
				'label' => esc_html__( 'Collection Name', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'name_typography',
				'label' => esc_html__( 'Typography', WP_SMART_NFT),
				'selector' => '{{WRAPPER}} .collections-grid .collection-card__head p',
			]
		);

		$this->add_control(
			'name_color',
			[
				'label' => esc_html__( 'Color', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .collections-grid .collection-card__head p' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'name_spaceing',
			[
				'label' => esc_html__( 'Spacing', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .collections-grid .collection-card__head p' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'heading_style_meta',
			[
				'label' => esc_html__( 'Collection Meta', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_control(
			'show_meta',
			[
				'label' => esc_html__( 'Show Colleciton meta', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'yes'
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'meta_title_typography',
				'label' => esc_html__( 'Meta title typography', WP_SMART_NFT),
				'selector' => '{{WRAPPER}} .collections-grid .collection-card__info p:first-child',
				'condition' => [
					'show_meta' => 'yes'
				],
			]
		);

		$this->add_control(
			'meta_title_color',
			[
				'label' => esc_html__( 'Meta title color', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .collections-grid .collection-card__info p:first-child' => 'color: {{VALUE}}',
				],
				'condition' => [
					'show_meta' => 'yes'
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'meta_valur_typography',
				'label' => esc_html__( 'Meta value typography', WP_SMART_NFT),
				'selector' => '{{WRAPPER}} .collections-grid .collection-card__info__value',
				'condition' => [
					'show_meta' => 'yes'
				],
			]
		);

		$this->add_control(
			'meta_value_color',
			[
				'label' => esc_html__( 'Meta value color', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .collections-grid .collection-card__info__value' => 'color: {{VALUE}} !important',
				],
				'condition' => [
					'show_meta' => 'yes'
				],
			]
		);




		$this->end_controls_section();

		// Card Style For Classic styles
		$this->start_controls_section(
			'section_style_classic',
			[
				'label' => esc_html__( 'Card Style', WP_SMART_NFT ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
				'condition'	=> [
					'carousel_style' => 'classic'
				]
			]
		);

		$this->add_control(
			'heading_style_card_classic',
			[
				'label' => esc_html__( 'Card settings', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::HEADING
			]
		);
		$this->add_responsive_control(
			'gallery_vertical_align_classic',
			[
				'label' => esc_html__( 'Text Align', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Start', WP_SMART_NFT ),
						'icon' => 'eicon-h-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', WP_SMART_NFT ),
						'icon' => 'eicon-h-align-center',
					],
					'right' => [
						'title' => esc_html__( 'End', WP_SMART_NFT ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'default' => 'left',
				'condition' => [
					'slides_to_show!' => '1',
				],
				'selectors' => [
					'{{WRAPPER}} .collection-grid-classic' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'card_border_radius_classic',
			[
				'label' => esc_html__( 'Border Radius', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
						'step' => 1,
					]
				],
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .collection-grid-classic' => 'border-radius: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'after',
			]
		);
		$this->add_control(
			'heading_style_cover_classic',
			[
				'label' => esc_html__( 'Profile image', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::HEADING
			]
		);
		$this->add_control(
			'banner_height_classic',
			[
				'label' => esc_html__( 'Height', WP_SMART_NFT ),
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
					'size' => 220,
				],
				'selectors' => [
					'{{WRAPPER}} .collection-grid-classic' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'cover_bg_classic',
			[
				'label' => esc_html__( 'Background', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .collection-grid-classic .no-profile' => 'background: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'heading_style_collection_classic',
			[
				'label' => esc_html__( 'Collection Name', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'name_typography_classic',
				'label' => esc_html__( 'Typography', WP_SMART_NFT),
				'selector' => '{{WRAPPER}} .collection-grid-classic .info .name',
			]
		);

		$this->add_control(
			'name_color_classic',
			[
				'label' => esc_html__( 'Color', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .collection-grid-classic .info .name' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'heading_style_meta_classic',
			[
				'label' => esc_html__( 'Collection Meta', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_control(
			'show_meta_classic',
			[
				'label' => esc_html__( 'Show Colleciton meta', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'default' => 'yes'
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'meta_title_typography_classic',
				'label' => esc_html__( 'Meta typography', WP_SMART_NFT),
				'selector' => '{{WRAPPER}} .collection-grid-classic .info p',
				'condition' => [
					'show_meta_classic' => 'yes'
				],
			]
		);

		$this->add_control(
			'meta_title_color_classic',
			[
				'label' => esc_html__( 'Meta color', WP_SMART_NFT ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .collection-grid-classic .info p' => 'color: {{VALUE}}',
				],
				'condition' => [
					'show_meta_classic' => 'yes'
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Render image carousel widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
		$translation_class = new Smartnft_Translation_Manager();
		$translation = $translation_class->get_translated_array();
		
        $settings = $this->get_settings_for_display();
		//$active_contract = get_option("smartnft_active_contract_address",false);

		$collections = $this->get_collections($settings['limit']);
		$show_meta = 'yes' === $settings['show_meta'];
		$show_meta_classic = 'yes' === $settings['show_meta_classic'];

		$slides = [];

		foreach ( $collections as $collection ) {
			$slide_html = '<div class="swiper-slide">';
			$bannerImg =  !empty($collection['collectionThumb']) ? $collection['collectionThumb'] : $collection['collectionBanner'];
			if($settings['carousel_style'] === 'modern'){
				$slide_html .= '<div class="collection-card">
					<a href="' . $collection['permalink'] .'">
						<figure
							class="collection-cover"
							style="background-image: url('. $bannerImg .')"
						></figure>
					</a>
					<a href="' . $collection['permalink'] . '">
						<div>
							<div class="collection-card__head">';
								if( !empty ( $collection['collectionImg'] ) ){
									$slide_html .= '<img
										class="collection-card__collection-photo"
										src=" ' . $collection['collectionImg'].'"
									/>';
								}else{
									$slide_html .= '<span class="collection-card__collection-photo__noimg"></span>';
								}
								$slide_html .= '<p>' . $collection['name'] . '</p>
							</div>';
							if( $show_meta ){
								$slide_html .= '<div class="collection-card__info">
									<div class="floor">
										<p>'. $translation['floor'] .'</p>
										<p class="collection-card__info__value">
											<span>'. $collection['floor_price'] .'</span>
										</p>
									</div>
									<div class="total">
										<p>'. $translation['volume'] .'</p>
										<p class="collection-card__info__value">
											<span> '. $collection['total_vol'] .'</span>
										</p>
									</div>
									<div class="items">
										<p>'. $translation['owners'] .'</p>
										<p class="collection-card__info__value">'. $collection['owners'] .'</p>
									</div>
								</div>';
							}
					$slide_html .= '</div>
					</a>
				</div>';
			}else{
				$slide_html .= '<a href="'. $collection['permalink'].'">
					<div class="collection-grid-classic">';
							if( !empty ( $collection['collectionImg'] ) ){
								$slide_html .= '<img class="profile" src="'. $collection['collectionImg'] .'" alt="'. $collection['name'] .'">';
							}else{
								$slide_html .= '<figure class="no-profile"></figure>';
							}
							$slide_html .= '<div class="info">
								<h2 class="name">'. $collection['name'] .'</h2>';
								if($show_meta_classic){
									$slide_html .= '<p>'. $translation['floor'] . $collection['floor_price'] .' '. $collection['currencySymbol'] .'</p>';
								}
							$slide_html .= '</div>
					</div>
				</a>';
			}

			$slide_html .= '</div>';

			$slides[] = $slide_html;

		}

		if ( empty( $slides ) ) {
			return;
		}

		$this->add_render_attribute( [
			'carousel' => [
				'class' => 'collections-grid elementor-image-carousel swiper-wrapper',
			],
			'carousel-wrapper' => [
				'class' => 'elementor-image-carousel-wrapper swiper-container',
				'dir' => $settings['direction'],
			],
		] );

		$show_dots = ( in_array( $settings['navigation'], [ 'dots', 'both' ] ) );
		$show_arrows = ( in_array( $settings['navigation'], [ 'arrows', 'both' ] ) );

		$slides_count = count( $slides );

		?>
		<div <?php $this->print_render_attribute_string( 'carousel-wrapper' ); ?>>
			<div <?php $this->print_render_attribute_string( 'carousel' ); ?>>
				<?php // PHPCS - $slides contains the slides content, all the relevent content is escaped above. ?>
				<?php echo implode( '', $slides ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
			<?php if ( 1 < $slides_count ) : ?>
				<?php if ( $show_dots ) : ?>
					<div class="swiper-pagination"></div>
				<?php endif; ?>
				<?php if ( $show_arrows ) : ?>
					<div class="elementor-swiper-button elementor-swiper-button-prev">
						<?php $this->render_swiper_button( 'previous' ); ?>
						<span class="elementor-screen-only"><?php echo esc_html__( 'Previous', WP_SMART_NFT ); ?></span>
					</div>
					<div class="elementor-swiper-button elementor-swiper-button-next">
						<?php $this->render_swiper_button( 'next' ); ?>
						<span class="elementor-screen-only"><?php echo esc_html__( 'Next', WP_SMART_NFT ); ?></span>
					</div>
				<?php endif; ?>
			<?php endif; ?>
		</div>
		<?php

	}

	private function get_collections ($limit) {
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
                    'collectionThumb'  => $term_meta['thumbImg'],
                    'contract'         => $term_meta['contractAddress'],
                    'count'            => $term->count,
					'currencySymbol'   => $term_meta['network']['currencySymbol'],
					'currencyIcon'     => $term_meta['network']['icon'],
                );

            }

        return $response;
	}

	/**
	 * Retrieve image carousel link URL.
	 *
	 * @since 1.0.0
	 * @access private
	 *
	 * @param array $attachment
	 * @param object $instance
	 *
	 * @return array|string|false An array/string containing the attachment URL, or false if no link.
	 */
	private function get_link_url( $attachment, $instance ) {
		if ( 'none' === $instance['link_to'] ) {
			return false;
		}

		if ( 'custom' === $instance['link_to'] ) {
			if ( empty( $instance['link']['url'] ) ) {
				return false;
			}

			return $instance['link'];
		}

		return [
			'url' => wp_get_attachment_url( $attachment['id'] ),
		];
	}

	/**
	 * Retrieve image carousel caption.
	 *
	 * @since 1.2.0
	 * @access private
	 *
	 * @param array $attachment
	 *
	 * @return string The caption of the image.
	 */
	private function get_image_caption( $attachment ) {
		$caption_type = $this->get_settings_for_display( 'caption_type' );

		if ( empty( $caption_type ) ) {
			return '';
		}

		$attachment_post = get_post( $attachment['id'] );

		if ( 'caption' === $caption_type ) {
			return $attachment_post->post_excerpt;
		}

		if ( 'title' === $caption_type ) {
			return $attachment_post->post_title;
		}

		return $attachment_post->post_content;
	}

	private function render_swiper_button( $type ) {
		$direction = 'next' === $type ? 'right' : 'left';
		$icon_settings = $this->get_settings_for_display( 'navigation_' . $type . '_icon' );

		if ( empty( $icon_settings['value'] ) ) {
			$icon_settings = [
				'library' => 'eicons',
				'value' => 'eicon-chevron-' . $direction,
			];
		}

		\Elementor\Icons_Manager::render_icon( $icon_settings, [ 'aria-hidden' => 'true' ] );
	}
}
