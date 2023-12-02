<?php

if ( !class_exists( 'Tools_Products_Widget' ) ) {
    class Tools_Products_Widget extends Tools_Widget
    {
        function __construct(){
            $this->widget_cssclass    = 'tool widget_products';
            $this->widget_description = __( "Display the Product List.", 'tools' );
            $this->widget_id          = 'tools_widget_products';
            $this->widget_name        = __( 'Ovic: Products', 'tools' );
            $this->settings           = array(
                'title'  => array(
                    'type'  => 'text',
                    'std'   => __( 'Products', 'tools' ),
                    'label' => __( 'Title', 'tools' ),
                ),
                'number' => array(
                    'type'  => 'number',
                    'step'  => 1,
                    'min'   => 1,
                    'max'   => '',
                    'std'   => 5,
                    'label' => __( 'Number of products to show', 'tools' ),
                ),
                'show' => array(
                    'type'  => 'select',
                    'std'   => '',
                    'label' => __( 'Show', 'tools' ),
                    'options' => array(
                        ''         => __( 'All products', 'tools' ),
                        'featured' => __( 'Featured products', 'tools' ),
                        'onsale'   => __( 'On-sale products', 'tools' ),
                    ),
                ),
                'orderby' => array(
                    'type'  => 'select',
                    'std'   => 'date',
                    'label' => __( 'Order by', 'tools' ),
                    'options' => array(
                        'date'   => __( 'Date', 'tools' ),
                        'price'  => __( 'Price', 'tools' ),
                        'rand'   => __( 'Random', 'tools' ),
                        'sales'  => __( 'Sales', 'tools' ),
                    ),
                ),
                'order' => array(
                    'type'  => 'select',
                    'std'   => 'desc',
                    'label' => _x( 'Order', 'Sorting order', 'tools' ),
                    'options' => array(
                        'asc'  => __( 'ASC', 'tools' ),
                        'desc' => __( 'DESC', 'tools' ),
                    ),
                ),
                'hide_free' => array(
                    'type'  => 'checkbox',
                    'std'   => 0,
                    'label' => __( 'Hide free products', 'tools' ),
                ),
                'show_hidden' => array(
                    'type'  => 'checkbox',
                    'std'   => 0,
                    'label' => __( 'Show hidden products', 'tools' ),
                ),

            );

            parent::__construct();
        }
        /**
         * Query the products and return them.
         * @param  array $args
         * @param  array $instance
         * @return WP_Query
         */
        public function get_products( $args, $instance ) {
            $number                      = ! empty( $instance['number'] ) ? absint( $instance['number'] )           : $this->settings['number']['std'];
            $show                        = ! empty( $instance['show'] ) ? sanitize_title( $instance['show'] )       : $this->settings['show']['std'];
            $orderby                     = ! empty( $instance['orderby'] ) ? sanitize_title( $instance['orderby'] ) : $this->settings['orderby']['std'];
            $order                       = ! empty( $instance['order'] ) ? sanitize_title( $instance['order'] )     : $this->settings['order']['std'];
            $product_visibility_term_ids = wc_get_product_visibility_term_ids();

            $query_args = array(
                'posts_per_page' => $number,
                'post_status'    => 'publish',
                'post_type'      => 'product',
                'no_found_rows'  => 1,
                'order'          => $order,
                'meta_query'     => array(),
                'tax_query'      => array(
                    'relation' => 'AND',
                ),
            );

            if ( empty( $instance['show_hidden'] ) ) {
                $query_args['tax_query'][] = array(
                    'taxonomy' => 'product_visibility',
                    'field'    => 'term_taxonomy_id',
                    'terms'    => is_search() ? $product_visibility_term_ids['exclude-from-search'] : $product_visibility_term_ids['exclude-from-catalog'],
                    'operator' => 'NOT IN',
                );
                $query_args['post_parent']  = 0;
            }

            if ( ! empty( $instance['hide_free'] ) ) {
                $query_args['meta_query'][] = array(
                    'key'     => '_price',
                    'value'   => 0,
                    'compare' => '>',
                    'type'    => 'DECIMAL',
                );
            }

            if ( 'yes' === get_option( 'woocommerce_hide_out_of_stock_items' ) ) {
                $query_args['tax_query'] = array(
                    array(
                        'taxonomy' => 'product_visibility',
                        'field'    => 'term_taxonomy_id',
                        'terms'    => $product_visibility_term_ids['outofstock'],
                        'operator' => 'NOT IN',
                    ),
                );
            }

            switch ( $show ) {
                case 'featured' :
                    $query_args['tax_query'][] = array(
                        'taxonomy' => 'product_visibility',
                        'field'    => 'term_taxonomy_id',
                        'terms'    => $product_visibility_term_ids['featured'],
                    );
                    break;
                case 'onsale' :
                    $product_ids_on_sale    = wc_get_product_ids_on_sale();
                    $product_ids_on_sale[]  = 0;
                    $query_args['post__in'] = $product_ids_on_sale;
                    break;
            }

            switch ( $orderby ) {
                case 'price' :
                    $query_args['meta_key'] = '_price';
                    $query_args['orderby']  = 'meta_value_num';
                    break;
                case 'rand' :
                    $query_args['orderby']  = 'rand';
                    break;
                case 'sales' :
                    $query_args['meta_key'] = 'total_sales';
                    $query_args['orderby']  = 'meta_value_num';
                    break;
                default :
                    $query_args['orderby']  = 'date';
            }

            return new WP_Query( apply_filters( 'woocommerce_products_widget_query_args', $query_args ) );
        }

        function widget( $args, $instance ){
            if ( ( $products = $this->get_products( $args, $instance ) ) && $products->have_posts() ) {
                $this->widget_start( $args, $instance );
                $atts = array(
                    'owl_loop'         => false,
                    'owl_slide_margin' => 20,
                    'owl_focus_select' => true,
                    'owl_ts_items'     => 1,
                    'owl_xs_items'     => 2,
                    'owl_sm_items'     => 3,
                    'owl_md_items'     => 1,
                    'owl_lg_items'     => 1,
                    'owl_ls_items'     => 1,
                    'owl_dots'         => true,
                    'owl_navigation'       => false
                );
                $owl_settings      = apply_filters( 'ovic_carousel_data_attributes', 'owl_', $atts );

                echo '<div class="product-list-owl owl-slick"  '.$owl_settings.'>';

                while ( $products->have_posts() ) {
                    $products->the_post();
                    ?>
                    <div class="product-item style-3">
                        <div class="product-inner">
                            <div class="product-thumb">
                                <?php
                                /**
                                 * woocommerce_before_shop_loop_item_title hook.
                                 *
                                 * @hooked woocommerce_show_product_loop_sale_flash - 10
                                 * @hooked woocommerce_template_loop_product_thumbnail - 10
                                 */
                                do_action( 'woocommerce_before_shop_loop_item_title' );
                                ?>
                            </div>
                            <div class="product-info">
                                <?php
                                /**
                                 * woocommerce_after_shop_loop_item_title hook.
                                 *
                                 * @hooked woocommerce_template_loop_rating - 5
                                 * @hooked woocommerce_template_loop_price - 10
                                 */
                                do_action( 'woocommerce_after_shop_loop_item_title' );
                                /**
                                 * woocommerce_shop_loop_item_title hook.
                                 *
                                 * @hooked woocommerce_template_loop_product_title - 10
                                 */
                                do_action( 'woocommerce_shop_loop_item_title' );
                                do_action( 'woocommerce_after_shop_loop_item' );

                                ?>

                            </div>
                        </div>
                    </div>
                    <?php
                }

                echo '</div>';

                $this->widget_end( $args );
            }
            wp_reset_postdata();
        }
    }
}

add_action( 'widgets_init', 'Tools_Products_Widget' );
if( !function_exists('Tools_Products_Widget')){
    function Tools_Products_Widget() {
        register_widget( 'Tools_Products_Widget' );
    }
}
