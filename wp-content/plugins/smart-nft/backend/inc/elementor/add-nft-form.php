<?php

class Create_NFT_Form extends \Elementor\Widget_Base
{
	public function get_name() {
		return 'smart_nft_create_nft_form';
	}

	public function get_title() {
		return esc_html__( 'Create Nft Form', WP_SMART_NFT );
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
				"settings"         => get_option("smartnft_settings",false),
				"custom_networks"  => get_option("smartnft_custom_networks", []),
				"translation"	   => $translation->get_translated_array()
			);

			wp_register_script(
				"smartnft_front_element_create_nft_form",
				FRONTEND_SCRIPT_URL . 'element-nft-form.bundle.js',
				array("wp-i18n","jquery"),
				false,
				true
			);
			wp_set_script_translations( 'smartnft_front_element_create_nft_form', WP_SMART_NFT );
			wp_localize_script(
				"smartnft_front_element_create_nft_form",
				"local",
				$local
			);

			return [
				'smartnft_front_element_create_nft_form',
			];
	}

	public function get_style_depends () {
		//style
		wp_register_style(
			"smartnft_backend_style",
			 BACKEND_STYLE_URL . 'style.css',
		);

		return ["smartnft_backend_style"];

	}

	protected function register_controls(){
		$this->start_controls_section(
			'create_nft_form_text_controll',
			[
				'label' => esc_html__( 'Create NFT text controll', WP_SMART_NFT  ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

				$this->add_group_control(
						\Elementor\Group_Control_Typography::get_type(),
						[
							'label'		  => 'Heading Typography',
							'name'		  => 'create_nft_form_heading_typograpy',
							'selector'	=> '{{WRAPPER}} .form-header__title,.create-nft-form p.form-wallet__address',
						]
				);	
				$this->add_control(
						'create_nft_form_heading_color',
						[
							'label' => esc_html__( 'Heading Color', WP_SMART_NFT),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .form-header__title' 													=> 'color: {{VALUE}}',
								'{{WRAPPER}} .form-wallet__address' 												=> 'color: {{VALUE}}',
							],
						]
				);

				$this->add_group_control(
						\Elementor\Group_Control_Typography::get_type(),
						[
							'label'		  => 'Label Typography',
							'name'		  => 'create_nft_form_label_typograpy',
							'selector'	=> '{{WRAPPER}} .header-two',
						]
				);	
				$this->add_control(
						'create_nft_form_label_color',
						[
							'label' => esc_html__( 'Label Color', WP_SMART_NFT),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => ['{{WRAPPER}} .header-two' => 'color: {{VALUE}}'],
						]
				);

				$this->add_group_control(
						\Elementor\Group_Control_Typography::get_type(),
						[
							'label'		  => 'Placeholder Typography',
							'name'		  => 'create_nft_form_placeholder_typograpy',
							'selector'	=> '{{WRAPPER}} .pra-one,.create-nft-form .form-img-upload input::placeholder',
						]
				);	
				$this->add_control(
						'create_nft_form_placeholder_color',
						[
							'label' => esc_html__( 'Placeholder Color', WP_SMART_NFT),
							'type' => \Elementor\Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .pra-one' 																=> 'color: {{VALUE}}',
								'{{WRAPPER}} div.create-nft-form div.form-img-upload input::placeholder' => 'color: {{VALUE}}',
								'{{WRAPPER}} div.create-nft-form div.form-img-upload textarea::placeholder' => 'color: {{VALUE}}'
							],
						]
				);
	
		$this->end_controls_section();

		$this->start_controls_section(
					'create_nft_form_border_controll',
					[
						'label' => esc_html__( 'Border Controll', WP_SMART_NFT ),
						'tab' => \Elementor\Controls_Manager::TAB_STYLE,
					]
		);

					$this->add_group_control(
								\Elementor\Group_Control_Border::get_type(),
								[
									'name' => 'create_nft_form_border',
									'label' => esc_html__( 'Box Border', WP_SMART_NFT ),
									'selector' => '{{WRAPPER}} div.create-nft-form,div.create-nft-form div.form-wallet__display,div.create-nft-form div.form-img-upload__preview-section,
																div.create-nft-form div.form-header',
								]
					);
					$this->add_control(
								'create_nft_form_border_radius',
								[
									'label' => esc_html__( 'Box Border Radius', WP_SMART_NFT ),
									'type' => \Elementor\Controls_Manager::DIMENSIONS,
									'size_units' => [ 'px', '%', 'em' ],
									'selectors' => [
										'{{WRAPPER}} div.create-nft-form' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
										'{{WRAPPER}} div.create-nft-form div.form-wallet__display' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
										'{{WRAPPER}} div.create-nft-form div.form-img-upload__preview-section' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
										'{{WRAPPER}} div.create-nft-form div.form-header' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} 0{{UNIT}} 0{{UNIT}};',
									],
								]
					);

					$this->add_group_control(
								\Elementor\Group_Control_Border::get_type(),
								[
									'name' => 'create_nft_form_upload_file_border',
									'label' => esc_html__( 'Upload File Border', WP_SMART_NFT ),
									'selector' => '{{WRAPPER}} div.create-nft-form div.form-img-upload__upload-box',
								]
					);
					$this->add_control(
								'create_nft_form_upload_file_border_radius',
								[
									'label' => esc_html__( 'Upload File Border Radius', WP_SMART_NFT ),
									'type' => \Elementor\Controls_Manager::DIMENSIONS,
									'size_units' => [ 'px', '%', 'em' ],
									'selectors' => [
										'{{WRAPPER}} div.create-nft-form div.form-img-upload__upload-box' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
									],
								]
					);


					$this->add_group_control(
								\Elementor\Group_Control_Border::get_type(),
								[
									'name' => 'create_nft_form_input_field_border',
									'label' => esc_html__( 'Input Field Border', WP_SMART_NFT ),
									'selector' => '{{WRAPPER}} div.create-nft-form div.form-img-upload input,div.create-nft-form div.form-img-upload textarea',
								]
					);

					$this->add_control(
								'create_nft_form_input_field_border_radius',
								[
									'label' => esc_html__( 'Input Field Border Radius', WP_SMART_NFT ),
									'type' => \Elementor\Controls_Manager::DIMENSIONS,
									'size_units' => [ 'px', '%', 'em' ],
									'selectors' => [
										'{{WRAPPER}} div.create-nft-form div.form-img-upload input' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
										'{{WRAPPER}} div.create-nft-form div.form-img-upload textarea' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
									],
								]
					);

		$this->end_controls_section();

		$this->start_controls_section(
			'create_nft_form_button_controll',
			[
				'label' => esc_html__( 'Create NFT button controll', WP_SMART_NFT  ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
					//connect btn
					$this->add_group_control(
							\Elementor\Group_Control_Typography::get_type(),
							[
								'label'		  => 'Connect Btn Typography',
								'name'		  => 'create_nft_form_connect_btn_typo',
								'selector' => '{{WRAPPER}} div.create-nft-form p.form-wallet__connect, div.create-nft-form p.form-wallet__connected',
							]
					);	
					$this->add_control(
								'create_nft_form_connect_btn_color',
								[
									'label' => esc_html__( 'Connect Btn Color', WP_SMART_NFT ),
									'type' => \Elementor\Controls_Manager::COLOR,
									'selectors' => [
										'{{WRAPPER}} div.create-nft-form p.form-wallet__connect'   => 'color:{{VALUE}}',
										'{{WRAPPER}} div.create-nft-form p.form-wallet__connected' => 'color:{{VALUE}}',
									],
								]
		   	  );
					$this->add_control(
								'create_nft_form_connect_btn_bg_color',
								[
									'label' => esc_html__( 'Connect Btn BG Color', WP_SMART_NFT ),
									'type' => \Elementor\Controls_Manager::COLOR,
									'selectors' => [
										'{{WRAPPER}} div.create-nft-form p.form-wallet__connect'   => 'background-color:{{VALUE}}',
										'{{WRAPPER}} div.create-nft-form p.form-wallet__connected' => 'background-color:{{VALUE}}',
									],
								]
		   	  );
					$this->add_group_control(
								\Elementor\Group_Control_Border::get_type(),
								[
									'name' => 'create_nft_form_connect_btn_border',
									'label' => esc_html__( 'Connect Btn Border', WP_SMART_NFT ),
									'selector' => '{{WRAPPER}} div.create-nft-form p.form-wallet__connect, div.create-nft-form p.form-wallet__connected',
								]
					);
					$this->add_control(
								'create_nft_form_connect_btn_border_radius',
								[
									'label' => esc_html__( 'Connect Btn Border Radius', WP_SMART_NFT ),
									'type' => \Elementor\Controls_Manager::DIMENSIONS,
									'size_units' => [ 'px', '%', 'em' ],
									'selectors' => [
										'{{WRAPPER}} div.create-nft-form p.form-wallet__connect' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
										'{{WRAPPER}} div.create-nft-form p.form-wallet__connected' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
									],
								]
					);


					//upload btn
					$this->add_group_control(
							\Elementor\Group_Control_Typography::get_type(),
							[
								'label'		  => 'Upload Btn Typography',
								'name'		  => 'create_nft_form_upload_btn_typo',
								'selector' => '{{WRAPPER}} div.create-nft-form div.form-img-upload__upload-box label',
							]
					);	
					$this->add_control(
								'create_nft_form_upload_btn_color',
								[
									'label' => esc_html__( 'Upload Btn Color', WP_SMART_NFT ),
									'type' => \Elementor\Controls_Manager::COLOR,
									'selectors' => [
										'{{WRAPPER}} div.create-nft-form div.form-img-upload__upload-box label'   => 'color:{{VALUE}}',
									],
								]
		   	  );
					$this->add_control(
								'create_nft_form_upload_btn_bg_color',
								[
									'label' => esc_html__( 'Upload Btn BG Color', WP_SMART_NFT ),
									'type' => \Elementor\Controls_Manager::COLOR,
									'selectors' => [
										'{{WRAPPER}} div.create-nft-form div.form-img-upload__upload-box label'   => 'background-color:{{VALUE}}',
									],
								]
		   	  );
					$this->add_group_control(
								\Elementor\Group_Control_Border::get_type(),
								[
									'name' => 'create_nft_form_upload_btn_border',
									'label' => esc_html__( 'Upload Btn Border', WP_SMART_NFT ),
									'selector' => '{{WRAPPER}} div.create-nft-form div.form-img-upload__upload-box label',
								]
					);
					$this->add_control(
								'create_nft_form_upload_btn_border_radius',
								[
									'label' => esc_html__( 'Upload Btn Border Radius', WP_SMART_NFT ),
									'type' => \Elementor\Controls_Manager::DIMENSIONS,
									'size_units' => [ 'px', '%', 'em' ],
									'selectors' => [
										'{{WRAPPER}} div.create-nft-form div.form-img-upload__upload-box label' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
									],
								]
					);


					//Advance property btn btn
					$this->add_group_control(
							\Elementor\Group_Control_Typography::get_type(),
							[
								'label'		  => 'Advance Option Btn Typography',
								'name'		  => 'create_nft_form_advance_option_btn_typo',
								'selector' => '{{WRAPPER}} div.create-nft-form div.form-img-upload__advanced-option span.toggle-btn',
							]
					);	
					$this->add_control(
								'create_nft_form_advance_option_btn_color',
								[
									'label' => esc_html__( 'Advance Option Btn Color', WP_SMART_NFT ),
									'type' => \Elementor\Controls_Manager::COLOR,
									'selectors' => [
										'{{WRAPPER}} div.create-nft-form div.form-img-upload__advanced-option span.toggle-btn'   => 'color:{{VALUE}}',
									],
								]
		   	  );
					$this->add_control(
								'create_nft_form_advance_option_btn_bg_color',
								[
									'label' => esc_html__( 'Advance Option Btn BG Color', WP_SMART_NFT ),
									'type' => \Elementor\Controls_Manager::COLOR,
									'selectors' => [
										'{{WRAPPER}} div.create-nft-form div.form-img-upload__advanced-option span.toggle-btn'   => 'background-color:{{VALUE}}',
									],
								]
		   	  );
					$this->add_group_control(
								\Elementor\Group_Control_Border::get_type(),
								[
									'name' => 'create_nft_form_advance_option_btn_border',
									'label' => esc_html__( 'Advance Option Btn Border', WP_SMART_NFT ),
									'selector' => '{{WRAPPER}} div.create-nft-form div.form-img-upload__advanced-option span.toggle-btn',
								]
					);
					$this->add_control(
								'create_nft_form_advance_option_btn_border_radius',
								[
									'label' => esc_html__( 'Advance Option Btn Border Radius', WP_SMART_NFT ),
									'type' => \Elementor\Controls_Manager::DIMENSIONS,
									'size_units' => [ 'px', '%', 'em' ],
									'selectors' => [
										'{{WRAPPER}} div.create-nft-form div.form-img-upload__advanced-option span.toggle-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
									],
								]
					);
				


					//Submit btn
					$this->add_group_control(
							\Elementor\Group_Control_Typography::get_type(),
							[
								'label'		  => 'Submit Btn Typography',
								'name'		  => 'create_nft_form_submit_btn_typo',
								'selector' => '{{WRAPPER}} div.create-nft-form button.form-img-upload__submit',
							]
					);	
					$this->add_control(
								'create_nft_form_submit_btn_color',
								[
									'label' => esc_html__( 'Submit Btn Color', WP_SMART_NFT ),
									'type' => \Elementor\Controls_Manager::COLOR,
									'selectors' => [
										'{{WRAPPER}} div.create-nft-form button.form-img-upload__submit'   => 'color:{{VALUE}}',
									],
								]
		   	  );
					$this->add_control(
								'create_nft_form_submit_btn_bg_color',
								[
									'label' => esc_html__( 'Submit Btn BG Color', WP_SMART_NFT ),
									'type' => \Elementor\Controls_Manager::COLOR,
									'selectors' => [
										'{{WRAPPER}} div.create-nft-form button.form-img-upload__submit'   => 'background-color:{{VALUE}}',
									],
								]
		   	  );
					$this->add_group_control(
								\Elementor\Group_Control_Border::get_type(),
								[
									'name' => 'create_nft_form_submit_btn_border',
									'label' => esc_html__( 'Submit Btn Border', WP_SMART_NFT ),
									'selector' => '{{WRAPPER}} div.create-nft-form button.form-img-upload__submit',
								]
					);
					$this->add_control(
								'create_nft_form_submit_btn_border_radius',
								[
									'label' => esc_html__( 'Submit Btn Border Radius', WP_SMART_NFT ),
									'type' => \Elementor\Controls_Manager::DIMENSIONS,
									'size_units' => [ 'px', '%', 'em' ],
									'selectors' => [
										'{{WRAPPER}} div.create-nft-form button.form-img-upload__submit' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
									],
								]
					);
					
		$this->end_controls_section();
	}

	protected function render(){
		echo '<div id="smartnft-root"></div>'; 
		?>

		<!-- this below script run only in builder mode -->
		<script>
			if("SMART_NFT_CREATE_NEW_NFT_FORM_RERUN_APP" in window){
				window.setTimeout(() => {
					window.SMART_NFT_CREATE_NEW_NFT_FORM_RERUN_APP();
				},1000);
			}
		</script>


<?php	}

}
