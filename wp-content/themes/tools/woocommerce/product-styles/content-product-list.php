<?php
/*
Name: Product Style List
Slug: content-product-style-list
Allow for Shop: true
*/
remove_action('woocommerce_after_shop_loop_item_title','woocommerce_template_loop_rating',20);
remove_action('woocommerce_after_shop_loop_item_title','woocommerce_template_loop_price',10);
add_action('woocommerce_shop_loop_item_title','woocommerce_template_loop_rating',20);
add_action('woocommerce_after_shop_loop_item','woocommerce_template_loop_price',1);
add_action('woocommerce_after_shop_loop_item',array('Tools_Woo_Functions','woo_get_stock_status'),2);
?>
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

        <div class="info-main">
            <div class="left">
                <?php
                /**
                 * woocommerce_after_shop_loop_item_title hook.
                 *
                 * @hooked woocommerce_template_loop_rating - 20
                 * @hooked woocommerce_template_loop_price - 10
                 */
                do_action( 'woocommerce_after_shop_loop_item_title' );
                /**
                 * woocommerce_shop_loop_item_title hook.
                 *
                 * @hooked woocommerce_template_loop_product_title - 10
                 */
                do_action( 'woocommerce_shop_loop_item_title' );

                ?>
                <div class="excerpt">
                    <?php the_excerpt();?>
                </div>
            </div>
            <div class="info-button">
                <div class="inner">
                    <?php

                    /**
                     * woocommerce_after_shop_loop_item hook.
                     *
                     * @hooked woocommerce_template_loop_product_link_close - 5
                     * @hooked woocommerce_template_loop_add_to_cart - 10
                     */
                    do_action( 'woocommerce_after_shop_loop_item' );

                    ?>
                    <div class="group-button">
                        <?php
                        do_action('ovic_function_shop_loop_item_quickview');
                        do_action('ovic_function_shop_loop_item_wishlist');
                        do_action('ovic_function_shop_loop_item_compare');
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
remove_action('woocommerce_after_shop_loop_item',array('Tools_Woo_Functions','woo_get_stock_status'),2);
?>
