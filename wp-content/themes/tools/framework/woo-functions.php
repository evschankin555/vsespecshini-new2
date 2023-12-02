<?php
if( !class_exists('Tools_Woo_Functions')){
    class Tools_Woo_Functions{
        /**
         * @var Tools_Woo_Functions The one true Tools_Woo_Functions
         * @since 1.0
         */
        private static $instance;

        public static function instance(){

            if ( !isset( self::$instance ) && !( self::$instance instanceof Tools_Woo_Functions ) ) {
                self::$instance = new Tools_Woo_Functions;
            }
            self::$instance->hooks();

            return self::$instance;

        }
        public function __construct(){
            add_action( 'wp_ajax_yith_wcwl_update_wishlist_count',array($this,'yith_wcwl_ajax_update_count') );
            add_action( 'wp_ajax_nopriv_yith_wcwl_update_wishlist_count', array( $this,'yith_wcwl_ajax_update_count') );

            add_action( 'wp_ajax_yith_compare_update_count',array($this,'yith_compare_update_count') );
            add_action( 'wp_ajax_nopriv_yith_compare_update_count', array( $this,'yith_compare_update_count') );
        }

        public function hooks(){

            add_filter( 'woocommerce_breadcrumb_defaults', array($this,'woocommerce_breadcrumbs') );
            add_filter( 'woocommerce_pagination_args',  array( $this,'woocommerce_pagination_args') );
            add_action('ovic_group_flash_content',array($this,'out_of_stock_flash'),10);
            add_action('ovic_control_before_content','woocommerce_result_count',1);
            remove_action('ovic_control_after_content','woocommerce_result_count',10);

            add_filter('woocommerce_product_additional_information_heading',array( $this,'hiden_tab_content_title'));
            add_filter('woocommerce_product_description_heading',array( $this,'hiden_tab_content_title'));


            remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
            add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 20 );
            add_filter( 'woocommerce_product_get_rating_html', array( $this,'product_get_rating_html'), 15, 3 );

            add_filter('ovic_custom_html_countdown', array( $this,'ovic_custom_html_countdown'),10,2);

            add_action('tools_header_control', array( $this,'header_compare'));
            add_action('tools_header_control', array( $this,'header_wishlist'));
            add_action('tools_header_control', array( $this,'header_mini_cart'));
            add_filter( 'woocommerce_add_to_cart_fragments', array( $this, 'cart_link_fragment' ) );
            add_action('woocommerce_before_mini_cart', array( $this,'mini_cart_head'));
            remove_action( 'woocommerce_widget_shopping_cart_buttons', 'woocommerce_widget_shopping_cart_button_view_cart', 10 );

            if( class_exists('YITH_WCQV_Frontend')){
                remove_action( 'yith_wcwl_table_after_product_name', array( YITH_WCQV_Frontend::get_instance(), 'yith_add_quick_view_button' ), 15, 0 );
            }

            add_filter('ovic_class_sidebar_content_product',array($this,'ovic_class_sidebar_content_product'));
            add_filter('ovic_class_archive_content',array($this,'ovic_class_archive_content'));

            // Quick View
            remove_action( 'yith_wcqv_product_image', 'woocommerce_show_product_sale_flash', 10 );
            remove_action( 'yith_wcqv_product_summary', 'woocommerce_template_single_excerpt', 20 );
            remove_action( 'yith_wcqv_product_summary', 'woocommerce_template_single_meta', 30 );
            remove_action( 'yith_wcqv_product_image', 'woocommerce_show_product_images', 20 );


            add_action( 'yith_wcqv_product_summary', 'woocommerce_template_single_excerpt', 10 );

            add_action('yith_wcqv_product_image',array($this,'quick_view_thumb'));
            remove_action( 'woocommerce_single_product_summary', 'ovic_woocommerce_group_flash', 10 );

            add_filter('yith_add_quick_view_button_html',array($this,'yith_add_quick_view_button_html'),10,3);

            add_action('ovic_function_shop_loop_item_compare',array($this,'compare_button'));

            add_action( 'wp_loaded', array($this,'action_wp_loaded') );

        }


        public function yith_add_quick_view_button_html( $button, $label, $product){
            $html ='<div class="hint--left hint--bounce yith-wcqv-button-wapper" aria-label="'.$label.'">';
            $html .= $button;
            $html.='</div>';
            return  $html;
        }
        /**
         * Compare button
         */
        public static function compare_button() {

            ob_start();

            global $product;
            $id = $product->get_id();

            $button_text = get_option( 'yith_woocompare_button_text', esc_html__( 'Compare', 'tools' ) );

            if ( function_exists( 'yith_wpml_register_string' ) && function_exists( 'yit_wpml_string_translate' ) ) {
                yit_wpml_register_string( 'Plugins', 'plugin_yit_compare_button_text', $button_text );
                $button_text = yit_wpml_string_translate( 'Plugins', 'plugin_yit_compare_button_text', $button_text );
            }

            if ( class_exists( 'YITH_Woocompare' )  && ! wp_is_mobile() ) { ?>
                <div class="compare-button hint--bounce hint--top-left"
                     aria-label="<?php esc_html_e( 'Compare', 'tools' ); ?>">
                    <?php
                    printf( '<a href="%s" class="%s" data-product_id="%d" rel="nofollow">%s</a>',
                        self::get_compare_add_product_url( $id ),
                        'compare button',
                        $id,
                        $button_text );
                    ?>
                </div>
            <?php }

            echo ob_get_clean();
        }
        /**
         * Get compare URL
         */
        private static function get_compare_add_product_url( $product_id ) {

            $action_add = 'yith-woocompare-add-product';

            $url_args = array(
                'action' => $action_add,
                'id'     => $product_id,
            );

            return apply_filters( 'yith_woocompare_add_product_url',
                esc_url_raw( add_query_arg( $url_args ) ),
                $action_add );
        }

        public function quick_view_thumb(){
            echo  '<div class="images">';
            global $post, $product;
            $attachment_ids = $product->get_gallery_image_ids();


            if ( $attachment_ids && has_post_thumbnail() ) {
                $html_thumbnail = '';
                foreach ( $attachment_ids as $attachment_id ) {
                    $full_size_image = wp_get_attachment_image_src( $attachment_id, 'full' );
                    $thumbnail       = wp_get_attachment_image_src( $attachment_id, 'shop_thumbnail' );
                    $attributes      = array(
                        'title'                   => get_post_field( 'post_title', $attachment_id ),
                        'data-caption'            => get_post_field( 'post_excerpt', $attachment_id ),
                        'data-src'                => $full_size_image[0],
                        'data-large_image'        => $full_size_image[0],
                        'data-large_image_width'  => $full_size_image[1],
                        'data-large_image_height' => $full_size_image[2],
                    );
                    $html_thumbnail .='<div>';
                    $html_thumbnail .= wp_get_attachment_image( $attachment_id, 'shop_single', false, $attributes );
                    $html_thumbnail .='</div>';
                }
            }
            $html_main ='<div class="slider-for">';
            $html_main .='<div>';
            $html_main .= get_the_post_thumbnail( $post->ID, 'shop_single' );
            $html_main .='</div>';
            $html_main .=  $html_thumbnail;
            $html_main .='</div>';

            echo $html_main;
            echo '<div class="slider-nav">';
            echo '<div>';
            echo get_the_post_thumbnail( $post->ID, 'shop_single' );
            echo '</div>';
            echo $html_thumbnail;
            echo '</div>';

           echo  '</div>';
        }
        public function  ovic_class_archive_content($main_content_class){

            $shop_layout = apply_filters( 'ovic_get_option', 'ovic_sidebar_shop_layout', 'left' );
            if ( is_product() ) {
                $shop_layout = apply_filters( 'ovic_get_option', 'ovic_sidebar_single_product_layout', 'left' );
            }
            $main_content_class   = array();
            $main_content_class[] = 'main-content';
            if ( $shop_layout == 'full' ) {
                $main_content_class[] = 'col-sm-12';
            } else {
                $main_content_class[] = 'col-lg-9 col-md-9 col-sm-12 has-sidebar';
            }

            return $main_content_class;
        }

        public function ovic_class_sidebar_content_product( $sidebar_class ){
            $sidebar_class = array(
                'sidebar col-lg-3 col-md-3 col-sm-12'
            );
            return $sidebar_class;
        }
        function product_get_rating_html( $html, $rating, $count ){
            global $product;
            $rating_count = $product->get_rating_count();
            if ( 0 < $rating ) {
                $html = '<div class="star-rating">';
                $html .= wc_get_star_rating_html( $rating, $count );
                $html .= '</div>';
            } else {
                $html = '';
            }

            return $html;
        }

        public function  woocommerce_breadcrumbs( $defaults ){
            $defaults['delimiter'] = '';
            return $defaults;
        }
        public function out_of_stock_flash(){
            global  $product;
            if ( ! $product->is_in_stock() ) {
                ?>
                <span class="out-of-stock"><span class="text"><?php esc_html_e('Out of Stock','tools');?></span></span>
                <?php
            }
        }

        public function  woocommerce_pagination_args( $args ){

            $args['prev_text'] ='<i class="fa fa-angle-left" aria-hidden="true"></i>';
            $args['next_text'] ='<i class="fa fa-angle-right" aria-hidden="true"></i>';
            return $args;
        }

        public function hiden_tab_content_title( $title){
            return '';
        }

        public function  ovic_custom_html_countdown( $html,$date){
            ob_start();
            if ( $date > 0 ) {
                ?>
                <div class="product-countdown-wapper">
                    <div class="head">
                        <span class="title"><?php esc_html_e('Hurry up!','tools');?></span>
                        <span><?php esc_html_e('Offer ends in:','tools');?></span>
                    </div>
                    <div class="ovic-countdown" data-datetime="<?php echo date( 'm/j/Y g:i:s', $date ); ?>"></div>
                </div>
                <?php
            }
            $html = ob_get_clean();
            return $html;
        }

        public function  header_compare(){
            if( class_exists('YITH_Woocompare')){
                global $yith_woocompare;

                $count = count($yith_woocompare->obj->products_list);
                ?>
                <div class="block block-compare yith-woocompare-widget">
                    <a href="<?php echo add_query_arg( array( 'iframe' => 'true' ), $yith_woocompare->obj->view_table_url() ) ?>" class="compare added" rel="nofollow">
                        <span class="fa fa-bar-chart icon"></span>
                        <span class="count"><?php echo number_format($count);?></span>
                    </a>
                </div>
                <?php
            }
        }
        public function header_wishlist(){
            if( defined('YITH_WCWL')){
                $count = (int)yith_wcwl_count_all_products();
                $yith_wcwl_wishlist_page_id = get_option( 'yith_wcwl_wishlist_page_id' );
                $wishlist_url = get_page_link( $yith_wcwl_wishlist_page_id );
                if ( $wishlist_url != '' ) : ?>
                    <div class="block-wishlist block">
                        <a class="woo-wishlist-link" href="<?php echo esc_url( $wishlist_url ); ?>">
                            <span class="fa fa-heart icon"></span>
                            <span class="count"><?php echo number_format($count);?></span>
                        </a>
                    </div>
                <?php endif;
            }
        }

        public function  header_mini_cart(){
            ?>
            <div class="block-minicart block">
                <?php
                self::header_cart_link();
                the_widget( 'WC_Widget_Cart', 'title=' );
                ?>
            </div>
            <?php
        }
        function header_cart_link()
        {
            global $woocommerce;
            ?>
            <a class="link-dropdown cart-link" href="<?php echo wc_get_cart_url(); ?>">
                <span class="icon icon-cart fa fa-shopping-basket" aria-hidden="true"><span class="count-icon"><?php echo WC()->cart->cart_contents_count ?></span></span>
                <span class="count"><?php echo $woocommerce->cart->get_cart_subtotal();?></span>
                <span class="text"><?php esc_html_e('Cart','tools');?></span>
            </a>
            <?php
        }
        function cart_link_fragment( $fragments ){
            ob_start();
            self::header_cart_link();
            $fragments['a.cart-link'] = ob_get_clean();

            return $fragments;
        }

        public function  mini_cart_head(){
            global $woocommerce;
            ?>
            <?php if ( ! WC()->cart->is_empty() ) : ?>
            <div class="mini-cart-head">
                <span class="count"><?php echo WC()->cart->cart_contents_count ?><?php esc_html_e(' Items','tools');?></span>
                <?php echo '<a href="' . esc_url( wc_get_cart_url() ) . '" class="wc-forward">' . esc_html__( 'View cart', 'tools' ) . '</a>';?>
            </div>
            <?php endif;?>
            <?php
        }
        function yith_wcwl_ajax_update_count(){
            if( defined( 'YITH_WCWL' ) && ! function_exists( 'yith_wcwl_ajax_update_count' ) ) {
                wp_send_json(array(
                    'count' => yith_wcwl_count_all_products(),
                    'status' => true
                ));
            }else{
                wp_send_json(array(
                    'count' => 0,
                    'status' => false
                ));
            }

            wp_die();
        }
        public function  yith_compare_update_count(){
            if( class_exists('YITH_Woocompare_Frontend')){
                $yith_woocompare  = new YITH_Woocompare_Frontend();
                $ists = $yith_woocompare->get_products_list();

                $count = count($ists);
                wp_send_json(array(
                    'count' => $count,
                    'status' => true
                ));

            }
            wp_die();
        }
        public static function woo_get_stock_status(){
            global $product;
            ?>
            <div class="product-info-stock-sku">
                <div class="stock available">
                    <span class="label-text"><?php esc_html_e( 'Avaiability: ', 'tools' ); ?> </span><?php $product->is_in_stock() ? esc_html_e( 'In Stock', 'tools' ): esc_html_e( 'Out Of Stock', 'tools' ); ?>
                </div>
            </div>
            <?php
        }
        public function action_wp_loaded(){
            remove_action('ovic_function_shop_loop_item_compare','ovic_wc_loop_product_compare_btn',1);
        }
    }
}

if( !function_exists('Tools_Woo_Functions')){
    function Tools_Woo_Functions(){
        return Tools_Woo_Functions::instance();
    }
    Tools_Woo_Functions();
}

