<?php
/**
 * Class CustomWooCommerceHooks
 *
 * Класс для кастомизации WooCommerce хуков.
 */
class CustomWooCommerceHooks {

    /**
     * Инициализация класса и добавление WordPress хуков.
     */
    public function __construct() {
        add_action('wp_loaded', [$this, 'customWooHooks']);
        add_action('pre_get_posts', [$this, 'customLastPreGetPosts'], 9999);
    }

    /**
     * Удаляет стандартные WooCommerce хуки и добавляет свои.
     */
    public function customWooHooks() {
        remove_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
        remove_action('woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10);

        $current_url = $_SERVER['REQUEST_URI'];

        if (strpos($current_url, '/gruzovye-diski') !== false || strpos($current_url, '/gruzovye-diski-na-pricep') !== false) {

            add_action('woocommerce_before_shop_loop_item_title', [$this, 'newMethodForSpecialCategoriesThumbnail'], 10);
            add_action('woocommerce_shop_loop_item_title', [$this, 'newMethodForSpecialCategoriesTitle'], 10);
        } else {
            add_action('woocommerce_before_shop_loop_item_title', [$this, 'vsespecshiniTemplateLoopProductThumbnail'], 10);
            add_action('woocommerce_shop_loop_item_title', [$this, 'vsespecshiniTemplateLoopProductTitle'], 10);
        }
    }


    /**
     * Удаляет WooCommerce хуки, если они добавлены экземпляром класса iduidOneItemFilter.
     *
     * @param WP_Query $query Объект WP_Query.
     */
    public function customLastPreGetPosts($query) {
        $plugin_instance = iduidOneItemFilter::get_instance();

        remove_action('woocommerce_before_shop_loop_item_title', [$plugin_instance, 'ovic_template_loop_product_thumbnail'], 10);
        remove_action('woocommerce_shop_loop_item_title', [$plugin_instance, 'woocommerce_template_loop_product_title'], 10);
    }

    /**
     * Выводит заголовок товара с кастомным URL.
     */
    public function vsespecshiniTemplateLoopProductTitle() {
        global $post;

        if (strpos(get_permalink($post->ID), '?') === false) {
            return;
        }


        $desc = get_post_meta( $post->ID, '_variation_description', true );
        if(empty($desc)){
           // $product
            $desc = get_post_meta( $post->ID, '_variation_description', true );
            //$desc = $product->get_description();
            //$desc = $post->get_description();
        }
        if(empty($desc)){
            $desc = get_the_title();
        }

        echo '<h3 class="woocommerce-loop-product__title" style="font-size: 18px;"><a href="' .get_custom_product_url($post->ID)
            . '" title="' . $desc . '">' .$desc . '</a></h3>';
        //echo '<h3 class="product-name product_title"><a href="' . get_custom_product_url($post->ID) . '">' . $desc . '</a></h3>';
    }

    public function getTitle($post){
        $title = get_post_meta( $post->ID, '_variation_description', true );

        if(empty($title))
        {
            $title = get_the_title();
        }
        return $title;
    }
    /**
     * Выводит изображение товара с кастомным URL.
     */
    public function vsespecshiniTemplateLoopProductThumbnail() {
        global $post, $product;

        if (!$post || !$product) {
            return;
        }

        if (strpos(get_permalink($post->ID), '?') === false) {
            return;
        }

        $image_html = woocommerce_get_product_thumbnail();
        $title = $this->getTitle($post);
        $custom_title = $title;
        $custom_alt = $title;

        // Изменяем HTML изображения, добавляя кастомные title и alt
        $image_html = preg_replace('/(alt="[^"]*")/', 'alt="' . esc_attr($custom_alt) . '"', $image_html);
        $image_html = preg_replace('/(title="[^"]*")/', 'title="' . esc_attr($custom_title) . '"', $image_html);

        $uri = get_custom_product_url($post->ID);

        $result = '<a class="thumb-link woocommerce-product-gallery__image" href="' . esc_url($uri) . '" title="' . esc_attr($custom_title) . '">';
        $result .= '<figure>';
        $result .= $image_html;
        $result .= '</figure>';
        $result .= '</a>';

        echo $result;

        //echo '<a href="' . get_custom_product_url($post->ID) . '" title="' . $post->post_title . '">' . woocommerce_get_product_thumbnail() . '</a>';
    }


    /**
     * Выводит заголовок товара с кастомным URL для специальных категорий.
     */
    public function newMethodForSpecialCategoriesTitle() {
        global $post;

        $desc = get_post_meta( $post->ID, '_variation_description', true );

        if(empty($desc))
        {
            $desc = get_the_title();
        }

        $uri = get_permalink () ;

        echo '<h3 class="woocommerce-loop-product__title"><a href="'.$uri.'" title="'.$desc
            .'">'.$desc.'</a></h3>';    }

    /**
     * Выводит изображение товара с кастомным URL для специальных категорий.
     */
    public function newMethodForSpecialCategoriesThumbnail() {
        global $product, $post;

        $width  = 300;
        $height = 300;
        $crop   = true;

        $size = wc_get_image_size('shop_catalog');
        if ($size) {
            $width  = $size['width'];
            $height = $size['height'];
            if (!$size['crop']) {
                $crop = false;
            }
        }

        $lazy_load          = true;
        $thumbnail_id       = $product->get_image_id();
        $default_attributes = $product->get_default_attributes();

        $width  = apply_filters('ovic_shop_product_thumb_width', $width);
        $height = apply_filters('ovic_shop_product_thumb_height', $height);

        if (!empty($default_attributes)) {
            $lazy_load = false;
        }

        $image_thumb = apply_filters('ovic_resize_image', $thumbnail_id, $width, $height, $crop, $lazy_load);

        // Используем get_permalink() без дополнительных параметров
        $uri = get_permalink();
        $desc = get_post_meta( $post->ID, '_variation_description', true );

        if(empty($desc))
        {
            $desc = get_the_title();
        }
        echo '<a class="thumb-link woocommerce-product-gallery__image" href="' . esc_url($uri)
            . '" title="'.$desc
            .'">';
        echo '<figure>';
        echo wp_specialchars_decode($image_thumb['img']);
        echo '</figure>';
        echo '</a>';
    }

}

// Инициализация класса
new CustomWooCommerceHooks();
