<?php
if ( !class_exists( 'Tools_Custom_Css' ) ) {
	class  Tools_Custom_Css
	{
		public function __construct()
		{
			add_action( 'wp_enqueue_scripts', array( $this, 'add_inline_style' ), 999 );
		}

		public function add_inline_style()
		{
			$css = '';
			$css .= $this->theme_color();
			wp_enqueue_style(
				'tools-main',
				get_stylesheet_uri()
			);
			wp_add_inline_style( 'tools-main', $css );
		}

		public function theme_color()
		{
			$main_color           = Tools_Functions::get_option( 'ovic_main_color', '#f2c800' );
			$ovic_main_text_color = Tools_Functions::get_option( 'ovic_main_text_color', '#222222' );
			$css                  = '
            a:focus,
            a:active,
            a:hover,
            .post-item .readmore,
            .ovic-iconbox .icon,
            .page-404 a{
                color:' . $main_color . ';
            }
            button,
            .button,
            input[type="submit"],
            .block-nav-category .block-title,
            .pagination .page-numbers.current,
            .tagcloud a:hover,
            .backtotop,
            .added_to_cart,
            .ovic-tabs .tab-head .tab-link li a:before,
            .ovic-slide .slick-arrow:hover,
            .ovic-socials .socials-list li  a:hover,
            .widget_price_filter .ui-slider-range,
            .widget_price_filter .ui-slider-handle,
            .widget_product_categories .cat-item.current-cat > a:before,
            .widget_layered_nav li.chosen > a:before,
            .flex-control-nav .slick-arrow:hover,
            .wc-tabs li a:before,
            .owl-products .slick-arrow:hover,
            .product-list-owl .slick-arrow:hover,
            .ovic-tabs.layout1 .tab-link li.active>a,
            .ovic-tabs.layout1 .tab-link li:hover>a,
            .product-grid-title>span:after,
            .block-minicart .count-icon,
            .footer-device-mobile-item.device-cart .count-icon,
            .ovic-progress.vc_progress_bar .vc_single_bar .vc_bar.animated,
            .ovic-progress  .title span:after,
            .mfp-close-btn-in .mfp-close,
            #yith-quick-view-close,
            #yith-quick-view-content .slider-nav .slick-slide:after,
            .section-title span:after,
            .slick-dots li.slick-active,
            .blog-list-owl  .slick-arrow:hover,
            .ovic-mapper .ovic-pin .ovic-popup-footer a:hover{
                background-color:' . $main_color . ';
            }
            .pagination .page-numbers.current,
            .tagcloud a:hover,
            .wpb_single_image.border .vc_figure:hover,
            .ovic-slide .slick-arrow:hover,
            .widget_product_categories .cat-item.current-cat > a:before,
            .widget_layered_nav li.chosen > a:before,
            .flex-control-nav .slick-slide .flex-active,
            .flex-control-nav .slick-arrow:hover,
            .owl-products .slick-arrow:hover,
            .product-list-owl .slick-arrow:hover,
            .product-item.style-5  .product-inner,
            .blog-list-owl  .slick-arrow:hover,
            .ovic-mapper .ovic-pin .ovic-popup-footer a:hover,
            .ovic-tabs.layout1 .tab-link li.active>a,
            .ovic-tabs.layout1 .tab-link li:hover>a{
                border-color:' . $main_color . ';
            }
            .ovic-tabs.layout1 .tab-link li>a:after{
                  border-color: ' . $main_color . ' transparent transparent transparent;
            }
            .ovic-products.loading .content-product-append::after,
            .loading-lazy::after,
            .tab-container.loading::after{
                border-top-color:' . $main_color . '!important;
            }
            
            button, .button, input[type="submit"], .added_to_cart,
            .block-minicart .count-icon,
            .mfp-close-btn-in .mfp-close,
            .block-nav-category .block-title,
            .product-item .flash .onnew,
            .backtotop,
            .ovic-tabs.layout1 .tab-link li.active a, .ovic-tabs.layout1 .tab-link li:hover a,
            .ovic-slide .slick-arrow:hover, .product-list-owl .slick-arrow:hover,
            .tagcloud a:hover,
            #yith-quick-view-close,
            .blog-list-owl  .slick-arrow:hover,
            .ovic-mapper .ovic-pin .ovic-popup-footer a:hover,
            .ovic-tabs.layout1 .tab-link li:hover .vc_tta-icon,
            .ovic-tabs.layout1 .tab-link li.active .vc_tta-icon,
            .ovic-socials .socials-list li a:hover,
            .footer .tagcloud a:hover{
                color:' . $ovic_main_text_color . ';
            }
            .block-nav-category .block-title .before > span{
                background-color:' . $ovic_main_text_color . ';
            }
            .block-nav-category .block-title .before span{
                    border-color:' . $ovic_main_text_color . ';
            }
            ';

			return $css;
		}
	}

	new Tools_Custom_Css();
}