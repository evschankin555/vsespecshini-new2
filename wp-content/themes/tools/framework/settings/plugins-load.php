<?php

if ( !class_exists( 'Tools_PluginLoad' ) ) {
	class Tools_PluginLoad
	{
		public $plugins = array();
		public $config  = array();

		public function __construct()
		{
			$this->plugins();
			$this->config();
			if ( function_exists( 'tgmpa' ) ) {
				tgmpa( $this->plugins, $this->config );
			}
		}

		public function plugins()
		{
			$this->plugins = array(
				array(
					'name'               => 'Ovic Toolkit',
					'slug'               => 'ovic-toolkit',
					'source'             => esc_url( 'http://plugins.kutethemes.net/ovic-toolkit.zip' ),
					'version'            => '',
					'force_activation'   => false,
					'force_deactivation' => false,
					'external_url'       => '',
					'image'              => '',
				),
				array(
					'name'               => 'Ovic Import',
					'slug'               => 'ovic-import',
					'source'             => esc_url( 'https://plugins.kutethemes.net/ovic-import.zip' ),
					'version'            => '',
					'force_activation'   => false,
					'force_deactivation' => false,
					'external_url'       => '',
					'image'              => '',
				),
				array(
					'name'               => 'Revolution Slider',
					'slug'               => 'revslider',
					'source'             => esc_url( 'http://plugins.kutethemes.net/revslider.zip' ),
					'required'           => true,
					'version'            => '5.4.8.3',
					'force_activation'   => false,
					'force_deactivation' => false,
					'external_url'       => '',
				),
				array(
					'name'               => 'WPBakery Visual Composer',
					'slug'               => 'js_composer',
					'source'             => esc_url( 'https://plugins.kutethemes.net/js_composer.zip' ),
					'required'           => true,
					'force_activation'   => false,
					'force_deactivation' => false,
					'external_url'       => '',
				),
				array(
					'name'     => 'WooCommerce',
					'slug'     => 'woocommerce',
					'required' => true,
					'image'    => esc_url( 'http://kutethemes.net/wordpress/demos/images/images-import/woocommerce.png' ),
				),
				array(
					'name'     => 'YITH WooCommerce Compare',
					'slug'     => 'yith-woocommerce-compare',
					'required' => false,
					'image'    => esc_url( 'http://kutethemes.net/wordpress/demos/images/images-import/compare.jpg' ),
				),
				array(
					'name'     => 'YITH WooCommerce Wishlist', // The plugin name
					'slug'     => 'yith-woocommerce-wishlist', // The plugin slug (typically the folder name)
					'required' => false, // If false, the plugin is only 'recommended' instead of required
					'image'    => esc_url( 'http://kutethemes.net/wordpress/demos/images/images-import/wishlist.jpg' ),
				),
				array(
					'name'     => 'YITH WooCommerce Quick View', // The plugin name
					'slug'     => 'yith-woocommerce-quick-view', // The plugin slug (typically the folder name)
					'required' => false, // If false, the plugin is only 'recommended' instead of required
					'image'    => esc_url( 'http://kutethemes.net/wordpress/demos/images/images-import/quickview.jpg' ),
				),
				array(
					'name'     => 'Contact Form 7',
					'slug'     => 'contact-form-7',
					'required' => false,
					'image'    => esc_url( 'http://kutethemes.net/wordpress/demos/images/images-import/contactform7.png' ),
				),
			);
		}

		public function config()
		{
			$this->config = array(
				'id'           => 'tools',                 // Unique ID for hashing notices for multiple instances of TGMPA.
				'default_path' => '',                      // Default absolute path to bundled plugins.
				'menu'         => 'tools-install-plugins', // Menu slug.
				'parent_slug'  => 'themes.php',            // Parent menu slug.
				'capability'   => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
				'has_notices'  => true,                    // Show admin notices or not.
				'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
				'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
				'is_automatic' => true,                   // Automatically activate plugins after installation or not.
				'message'      => '',                      // Message to output right before the plugins table.
			);
		}
	}
}
if ( !function_exists( 'Tools_PluginLoad' ) ) {
	function Tools_PluginLoad()
	{
		new  Tools_PluginLoad();
	}
}
add_action( 'tgmpa_register', 'Tools_PluginLoad' );