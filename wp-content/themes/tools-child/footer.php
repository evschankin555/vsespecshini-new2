<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WordPress
 * @subpackage Tools
 * @since 1.0
 * @version 1.0
 */
?>
<?php
$ovic_footer_coppyright = Tools_Functions::get_option('ovic_footer_coppyright','© Copyright 2018 Tools. All Rights Reserved.');
$ovic_enable_go_to_top_button = Tools_Functions::get_option('ovic_enable_go_to_top_button',1);
$ovic_footer_payment_logo = Tools_Functions::get_option('ovic_footer_payment_logo',0);
?>
<?php if( $ovic_enable_go_to_top_button == 1):?>
<a href="#" class="backtotop"><?php esc_html_e('Go To Top','tools');?></a>
<?php endif;?>
<footer id="footer" class="footer">
    <div class="container">
        <div class="footer-top">
            <div class="row">
                <div class="col-sm-6 col-md-4">
                    <?php if ( is_active_sidebar( 'footer-sidebar-1' ) ) : ?>
                        <div class="footer-sidebar">
                            <?php dynamic_sidebar( 'footer-sidebar-1' ); ?>
                        </div><!-- .widget-area -->
                    <?php endif; ?>
                </div>
                <div class="col-sm-6 col-md-2">
                    <?php if ( is_active_sidebar( 'footer-sidebar-2' ) ) : ?>
                        <div class="footer-sidebar">
                            <?php dynamic_sidebar( 'footer-sidebar-2' ); ?>
                        </div><!-- .widget-area -->
                    <?php endif; ?>
                </div>
                <div class="col-sm-6 col-md-3">
                    <?php if ( is_active_sidebar( 'footer-sidebar-3' ) ) : ?>
                        <div class="footer-sidebar">
                            <?php dynamic_sidebar( 'footer-sidebar-3' ); ?>
                        </div><!-- .widget-area -->
                    <?php endif; ?>
                </div>
                <div class="col-sm-6 col-md-3">
                    <?php if ( is_active_sidebar( 'footer-sidebar-4' ) ) : ?>
                        <div class="footer-sidebar">
                            <?php dynamic_sidebar( 'footer-sidebar-4' ); ?>
                        </div><!-- .widget-area -->
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <?php if( $ovic_footer_coppyright !=''):?>
                <div class="coppyright"><?php echo esc_html( $ovic_footer_coppyright );?></div>
            <?php endif;?>
            <?php if( $ovic_footer_payment_logo > 0): ?>
                <div class="payment-logo">
                    <?php echo wp_get_attachment_image($ovic_footer_payment_logo,'full');?>
                </div>
            <?php endif;?>
        </div>
    </div>
</footer>
<div class="footer-device-mobile">
    <div class="wapper">
        <div class="footer-device-mobile-item device-home">
            <a href="<?php echo esc_url(get_home_url('/'))?>">
                <span class="icon"><i class="fa fa-home" aria-hidden="true"></i></span>
                <?php esc_html_e('Home','tools');?>
            </a>
        </div>
        <?php if( class_exists('YITH_WCWL')):?>
        <?php
            $yith_wcwl_wishlist_page_id = get_option( 'yith_wcwl_wishlist_page_id' );
            $wishlist_url = get_page_link( $yith_wcwl_wishlist_page_id );
        ?>
        <div class="footer-device-mobile-item device-wishlist">
            <a href="<?php echo esc_url($wishlist_url)?>">
                <span class="icon"><i class="fa fa-heart" aria-hidden="true"></i></span>
                <?php esc_html_e('wishlist','tools');?>
            </a>
        </div>
        <?php endif;?>
        <?php if( class_exists('WooCommerce')): global $woocommerce;?>
        <div class="footer-device-mobile-item device-cart">
            <a class="link-dropdown cart-link" href="<?php echo wc_get_cart_url(); ?>">
                <span class="icon icon-cart fa fa-shopping-basket" aria-hidden="true"><span class="count-icon"><?php echo WC()->cart->cart_contents_count ?></span></span>
                <span class="count"><?php echo wp_specialchars_decode($woocommerce->cart->get_cart_subtotal());?></span>
            </a>
        </div>
        <?php endif;?>
        <?php
        $myaccount_link = get_permalink( get_option('woocommerce_myaccount_page_id') );
        ?>
        <div class="footer-device-mobile-item device-user">
            <a href="<?php echo esc_url($myaccount_link)?>">
                <span class="icon"><i class="fa fa-user" aria-hidden="true"></i></span>
                <?php esc_html_e('Account','tools');?>
            </a>
        </div>
    </div>
</div>
<?php wp_footer(); ?>
<style>

    .entry-summary h1.product_title.entry-title{
        font-size: 24px;
        font-weight: 700;
        color: #222;
    }
</style>
<?php

// Получаем текущий URL
$current_url = $_SERVER['REQUEST_URI'];

// Проверяем, содержится ли подстрока '/product-category/' или '/product_brand/' в начале URL
if (strpos($current_url, '/product-category/') === 0 ||
    strpos($current_url, '/product_brand/') === 0 ||
    strpos($current_url, '/shop/') === 0 ||
    strpos($current_url, '/product-tag/') === 0
) : ?>
<style>
    .irs--flat .irs-from:before, .irs--flat .irs-single:before, .irs--flat .irs-to:before{
        border-top-color: #f2c800!important;
    }
    .irs--flat .irs-from, .irs--flat .irs-single, .irs--flat .irs-to{
        background-color: #f2c800!important;
        font-size: 12px;
    }
    .irs--flat .irs-bar{
        background-color: #f2c800!important;
    }
    .irs--flat .irs-handle>i:first-child{
        background-color: #f2c800!important;
    }
    @media (max-width: 993px){
        .main-content .row.ovic-products{
            margin: 390px 0 0 0;
        }
        .main-container  .sidebar{
            position: absolute;
            left: 0px;
        <?php  if (strpos($current_url, '/shop/') === 0) : ?>
            top: 200px;
        <?php else: ?>
            top: 160px;
        <?php endif; ?>
            display: block!important;
            width: calc(100% - 0px);
            background-color: transparent;
            z-index: 1;
        }
        .main-container  .widget_product_categories{
            display: none!important;
        }
        .main-container  .widget_product_tag_cloud{
            display: none!important;
        }

        ul.products{
            padding-top: 30px;
        }
    }
    @media (min-width: 768px) and (max-width: 993px) {

        .main-container  .sidebar{
            top: 400px;
        }
        .main-container .shop-sidebar{
            width: 720px;
        }
        .main-container  .sidebar{
            display: flex!important;
            justify-content: center;
        }
        ul.products{
            padding-top: 20px;
        }
    }
</style>
<script>
    // Ждем, пока документ полностью загрузится
    jQuery(document).ready(function() {
        // Функция для изменения отступа
        function updateMargin() {
            // Проверяем ширину окна
            if (jQuery(window).width() <= 993) {
                // Получаем высоту элемента с id widget-area
                var widgetAreaHeight = jQuery('#widget-area').height();
                // Изменяем отступ у элемента .main-content .row.ovic-products
                jQuery('.main-content .row.ovic-products').css('margin-top', widgetAreaHeight + 'px');
            } else {
                // Если ширина окна больше 993px, сбрасываем отступ
                jQuery('.main-content .row.ovic-products').css('margin-top', '');
            }
        }

        // Вызываем функцию при загрузке документа и при изменении размеров окна
        jQuery(window).on('load resize', updateMargin);
    });
</script>
<?php endif; ?>
</body>
</html>
