<?php

/*
Plugin Name: Vsespecshini YML Modifier
Description: Плагин для модификации выходных данных YML for Yandex Market.
Version: 1.0
Author: Евгений
Author URI: https://t.me/evsch999
*/


add_filter('yfym_variable_change_name', 'custom_yfym_change_name', 10, 5);

function custom_yfym_change_name($result_yml_name, $product_id, $product, $offer = null, $feed_id = null) {
    $desc = get_post_meta( $product_id, '_variation_description', true );

    if(empty($desc) && $offer !== null) {
        // Получаем содержимое из поля description объекта $offer
        $offer_description = $offer->get_description();

        // Если содержимое поля description есть, добавляем его к названию
        if (!empty($offer_description)) {
            $desc = $offer_description;
        } else {
            $desc = $result_yml_name;
        }
    }

    return $desc;
}

add_filter('y4ym_f_variable_tag_value_url', 'custom_y4ym_modify_url', 10, 3);

function custom_y4ym_modify_url($tag_value, $data, $feed_id) {
    $offer = $data['offer'];
    if(!empty($offer) && !empty($offer->get_id())){
        // Используем вашу новую функцию get_custom_product_url
        $new_url = get_custom_product_url($offer->get_id());

        // Возвращаем новый URL, закодированный для XML (поэтому используется &amp; вместо &)
        return htmlspecialchars($new_url, ENT_XML1, 'UTF-8');
    }
    return $tag_value;
}

function append_variable_tag_name_url_for_variations($result_xml, $data, $feed_id) {
    // Получаем продукт
    $product = $data['product'];
    $offer = $data['offer'];


    $variation_id = $product->get_id();
    $brands = implode(', ', wp_get_post_terms($variation_id, 'product_brand', ['fields' => 'names']));

    // Если бренды найдены, добавляем их в XML
    if (!empty($brands)) {
        $result_xml .= esc_html($brands) ;
    }else{
        $brand = get_the_terms($variation_id, 'product_brand');
        $result_xml .= esc_html($brand);
    }

    return $result_xml;
}
add_filter('y4ym_f_variable_tag_value_vendor', 'append_variable_tag_name_url_for_variations', 10, 3);

