<?php
if ( !class_exists( 'Sample_Settings' ) ) {
	class Sample_Settings
	{
		public function __construct()
		{
			// Filter Sample Data Menu
			add_filter( 'import_sample_data_menu_args', array( $this, 'import_sample_data_menu_args' ) );
			add_filter( 'import_sample_data_packages', array( $this, 'import_sample_data_packages' ) );
			add_filter( 'import_sample_data_required_plugins', array( $this, 'import_sample_data_required_plugins' ) );
			add_filter( 'import_sample_data_theme_option_key', array( $this, 'import_sample_data_theme_option_key' ) );
			add_action( 'import_sample_data_after_install_sample_data', array( $this, 'import_sample_data_after_install_sample_data' ), 10, 1 );
		}

		public function import_sample_data_theme_option_key( $theme_option_key )
		{
			return '_ovic_customize_options';
		}

		public function import_sample_data_required_plugins( $plugins )
		{
			$plugins = array(
				array(
					'name'        => 'Ovic Toolkit', // The plugin name
					'slug'        => 'ovic-toolkit', // The plugin slug (typically the folder name)
					'source'      => esc_url( 'https://plugins.kutethemes.net/ovic-toolkit.zip' ), // The plugin source
					'source_type' => 'external',
					'file_path'   => 'ovic-toolkit/ovic-toolkit.php',
				),
				array(
					'name'        => 'WPBakery Visual Composer', // The plugin name
					'slug'        => 'js_composer', // The plugin slug (typically the folder name)
					'source'      => 'https://plugins.kutethemes.net/js_composer.zip', // The plugin source
					'source_type' => 'external',
					'file_path'   => 'js_composer/js_composer.php',
				),
				array(
					'name'        => 'Revolution Slider', // The plugin name
					'slug'        => 'revslider', // The plugin slug (typically the folder name)
					'source'      => esc_url( 'https://plugins.kutethemes.net/revslider.zip' ), // The plugin source
					'source_type' => 'external',
					'file_path'   => 'revslider/revslider.php',
				),
				array(
					'name'        => 'WooCommerce',
					'slug'        => 'woocommerce',
					'required'    => true,
					'file_path'   => 'woocommerce/woocommerce.php',
					'source_type' => 'repo', // Plugins On wordpress.org
				),
				array(
					'name'        => 'YITH WooCommerce Compare',
					'slug'        => 'yith-woocommerce-compare',
					'required'    => false,
					'file_path'   => 'yith-woocommerce-compare/init.php',
					'source_type' => 'repo', // Plugins On wordpress.org
				),
				array(
					'name'        => 'YITH WooCommerce Wishlist', // The plugin name
					'slug'        => 'yith-woocommerce-wishlist', // The plugin slug (typically the folder name)
					'required'    => false, // If false, the plugin is only 'recommended' instead of required
					'source_type' => 'repo', // Plugins On wordpress.org
					'file_path'   => 'yith-woocommerce-wishlist/init.php',
				),
				array(
					'name'        => 'YITH WooCommerce Quick View', // The plugin name
					'slug'        => 'yith-woocommerce-quick-view', // The plugin slug (typically the folder name)
					'required'    => false, // If false, the plugin is only 'recommended' instead of required
					'source_type' => 'repo', // Plugins On wordpress.org
					'file_path'   => 'yith-woocommerce-quick-view/init.php',
				),
				array(
					'name'        => 'Contact Form 7',
					'slug'        => 'contact-form-7',
					'required'    => false,
					'source_type' => 'repo', // Plugins On wordpress.org
					'file_path'   => 'contact-form-7/wp-contact-form-7.php',
				),
			);

			return $plugins;
		}

		/**
		 * Change Menu Sample dataß.
		 *
		 * @param array $uri Remote URI for fetching content.
		 *
		 * @return  array
		 */
		public function import_sample_data_menu_args( $args )
		{
			$args = array(
				'parent_slug' => 'ovic-dashboard',
				'page_title'  => esc_html__( 'Import Sample Datas', 'supermarket' ),
				'menu_title'  => esc_html__( 'Import Sample Datas', 'supermarket' ),
				'capability'  => 'manage_options',
				'menu_slug'   => 'sample-data',
				'function'    => 'Import_Sample_Data_Dashboard::dashboard',
			);

			return $args;
		}

		public function import_sample_data_packages( $packages )
		{
			return array(
				'home'     => array(
					'id'       => 'home',
					'name'     => 'Home',
					'thumbail' => get_theme_file_uri( 'screenshot.jpg' ),
					'demo'     => get_home_url( '/' ),
					'download' => get_theme_file_uri( 'importer/data/sample-data.zip' ),
					'tags'     => array( 'all', 'simple' ),
					'main'     => true,
				),
				'home_rtl' => array(
					'id'       => 'home_rtl',
					'name'     => 'Home RTL',
					'thumbail' => get_theme_file_uri( 'screenshot.jpg' ),
					'demo'     => get_home_url( '/' ),
					'download' => get_theme_file_uri( 'importer/data/sample-data-rtl.zip' ),
					'tags'     => array( 'all', 'simple' ),
				),
			);
		}

		public function import_sample_data_after_install_sample_data( $package )
		{
			// Do something here!
			$menus    = get_terms( 'nav_menu', array( 'hide_empty' => true ) );
			$home_url = get_home_url();
			if ( !empty( $menus ) ) {
				foreach ( $menus as $menu ) {
					$items = wp_get_nav_menu_items( $menu->term_id );
					if ( !empty( $items ) ) {
						foreach ( $items as $item ) {
							$_menu_item_url = get_post_meta( $item->ID, '_menu_item_url', true );
							if ( !empty( $_menu_item_url ) ) {
								$_menu_item_url = str_replace( 'https://tools.kute-themes.com', $home_url, $_menu_item_url );
								$_menu_item_url = str_replace( 'http://tools.kute-themes.com', $home_url, $_menu_item_url );
								update_post_meta( $item->ID, '_menu_item_url', $_menu_item_url );
							}
						}
					}
				}
			}
		}
	}
}

new Sample_Settings();