<?php
if ( !class_exists( 'Tools_Theme_Options' ) ) {
	class Tools_Theme_Options
	{
		public function __construct()
		{
			add_filter( 'ovic_config_customize_sections', array( $this, 'set_options' ) );
		}

		public function set_options( $sections )
		{
			// Header Settings
			$header_settings              = array(
				'top_header_settings' => array(
					'id'                => 'top_header_settings',
					'type'              => 'heading',
					'content'           => esc_html__( 'Top Header Settings', 'tools' ),
					'selective_refresh' => array(
						'selector' => '.top-header',
					),
				),
				'ovic_header_email'   => array(
					'id'                => 'ovic_header_email',
					'type'              => 'text',
					'title'             => esc_html__( 'Header Email', 'tools' ),
					'multilang'         => true,
					'selective_refresh' => array(
						'selector' => '.main-header-right',
					),
				),
				'ovic_header_phone'   => array(
					'id'                => 'ovic_header_phone',
					'type'              => 'text',
					'title'             => esc_html__( 'Header Phone', 'tools' ),
					'multilang'         => true,
					'selective_refresh' => array(
						'selector' => '.main-header-right',
					),
				),
			);
			$sections['header']['fields'] = array_merge( $sections['header']['fields'], $header_settings );
			// Logo Settings
			$general_settings              = array(
				'ovic_logo_mobile'     => array(
					'id'    => 'ovic_logo_mobile',
					'type'  => 'image',
					'title' => esc_html__( 'Logo for Mobile', 'tools' ),
					'desc'  => esc_html__( 'Setting Logo For Mobile', 'tools' ),
				),
				'ovic_main_text_color' => array(
					'id'    => 'ovic_main_text_color',
					'type'  => 'color_picker',
					'title' => esc_html__( 'Main Text Color', 'tools' ),
				),
			);
			$sections['general']['fields'] = array_merge( $sections['general']['fields'], $general_settings );
			/* Footer Settings*/
			$footer_settings              = array(
				'ovic_footer_coppyright'       => array(
					'id'                => 'ovic_footer_coppyright',
					'type'              => 'text',
					'title'             => esc_html__( 'Coppyright', 'tools' ),
					'default'           => __( '© Copyright 2018 Tools. All Rights Reserved.', 'tools' ),
					'selective_refresh' => array(
						'selector' => '.coppyright',
					),
					'multilang'         => true,
				),
				'ovic_footer_payment_logo'     => array(
					'id'    => 'ovic_footer_payment_logo',
					'type'  => 'image',
					'title' => esc_html__( 'Payment Logo', 'tools' ),
				),
				'ovic_enable_go_to_top_button' => array(
					'id'      => 'ovic_enable_go_to_top_button',
					'type'    => 'switcher',
					'title'   => esc_html__( 'Enable Go to top Button', 'tools' ),
					'default' => 1,
				),
			);
			$sections['footer']['fields'] = array_merge( $sections['footer']['fields'], $footer_settings );
			// Single settings
			if ( class_exists( 'WooCommerce' ) ) {
				$sections['single_product']['fields']['ovic_position_summary_product'] = array(
					'id'                => 'ovic_position_summary_product',
					'type'              => 'sorter',
					'title'             => esc_html__( 'Sorter Summary Single Product', 'tools' ),
					'selective_refresh' => array(
						'selector' => '.entry-summary',
					),
					'transport'         => 'postMessage',
					'options'           => array(
						'enabled'  => array(
							'woocommerce_template_single_title'       => esc_html__( 'Single Title', 'tools' ),
							'woocommerce_template_single_rating'      => esc_html__( 'Single Rating', 'tools' ),
							'woocommerce_template_single_price'       => esc_html__( 'Single Price', 'tools' ),
							'woocommerce_template_single_excerpt'     => esc_html__( 'Single Excerpt', 'tools' ),
							'woocommerce_template_single_add_to_cart' => esc_html__( 'Single Add To Cart', 'tools' ),
						),
						'disabled' => array(
							'woocommerce_template_single_meta' => esc_html__( 'Single Meta', 'tools' ),
						),
					),
					'enabled_title'     => esc_html__( 'Active', 'tools' ),
					'disabled_title'    => '<p>' . esc_html__( 'Deactive', 'tools' ) . '</p>',
				);
			}

			return $sections;
		}
	}

	new Tools_Theme_Options();
}
