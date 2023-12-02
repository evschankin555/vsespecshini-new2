<?php
/**
 * Related Products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/related.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @package 	WooCommerce/Templates
 * @version     3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
// ID текущего товара
$current_product_id = get_the_ID();

// Получаем текущий URL
$current_url = home_url( add_query_arg( null, null ) );

// Парсим URL для получения параметров
$url_parameters = wp_parse_url($current_url);
parse_str($url_parameters['query'], $params);
$isVariative = false;
// Проверяем, является ли страница вариативной
$request_uri = $_SERVER['REQUEST_URI'];

// Ищем PID в конце URL
if (preg_match('/-pid_([0-9]+)\/?$/', $request_uri, $matches)) {
    $isVariative = true;
// Получаем список категорий текущего товара
    $product_cats = wp_get_post_terms($current_product_id, 'product_cat');

    if ($product_cats) {
        $levels = array();
        foreach ($product_cats as $cat) {
            $ancestors = get_ancestors($cat->term_id, 'product_cat');
            $level = count($ancestors);
            if (!isset($levels[$level]) || $cat->term_id < $levels[$level]->term_id) {
                $levels[$level] = $cat;
            }
        }

        krsort($levels);
        $deepest_cat = reset($levels);

        $args = array(
            'post_type' => 'product',
            'posts_per_page' => -1,
            'post__not_in' => array($current_product_id),
            'tax_query' => array(
                array(
                    'taxonomy' => 'product_cat',
                    'field' => 'term_id',
                    'terms' => $deepest_cat->term_id,
                ),
            ),
        );
        $related_products = get_posts($args);
        $related_product_ids = wp_list_pluck($related_products, 'ID');
        $args = array(
            'post_type' => 'product_variation',
            'posts_per_page' => -1,
            'post_parent__in' => $related_product_ids,
        );

        $potential_related_variations = get_posts($args);
        $filtered_variation_ids = array();

        // Получаем размеры текущего продукта один раз
        $parent_id = wp_get_post_parent_id($current_product_id);
        $related_product_ids = wp_list_pluck($related_products, 'ID');

        $current_offer_id = intval($matches[1]);
        // Получаем размеры текущего продукта из мета-данных
        $current_product_tyre_width = get_post_meta($current_offer_id, 'attribute_pa_tyre_width', true);
        $current_product_tyre_profile = get_post_meta($current_offer_id, 'attribute_pa_tyre_profile', true);
        $current_product_tyre_rim = get_post_meta($current_offer_id, 'attribute_pa_tyre_rim', true);


        $filtered_variation_ids = array();

        foreach ($related_product_ids as $product_id) {
            $args = array(
                'post_type' => 'product_variation',
                'posts_per_page' => -1,
                'post_parent' => $product_id,
                'meta_query' => array(
                    'relation' => 'AND',
                    array(
                        'key' => 'attribute_pa_tyre_width',
                        'value' => $current_product_tyre_width,
                    ),
                    array(
                        'key' => 'attribute_pa_tyre_profile',
                        'value' => $current_product_tyre_profile,
                    ),
                    array(
                        'key' => 'attribute_pa_tyre_rim',
                        'value' => $current_product_tyre_rim,
                    ),
                ),
            );

            $variations = get_posts($args);
            if (!empty($variations)) {
                $filtered_variation_ids[] = $variations[0]->ID;
            }
        }

        $related_products2 = $filtered_variation_ids;

    }




    //старое решение
    // Да, это вариативная страница. Выполняем запрос для получения вариаций текущего продукта.

    /*$args = array(
        'post_type'     => 'product_variation',
        'post_status'   => array( 'private', 'publish' ),
        'numberposts'   => -1,
        'orderby'       => 'menu_order',
        'order'         => 'asc',
        'post_parent'   => $current_product_id // ID текущего продукта
    );

    // Получаем вариации текущего продукта
    $variations = get_posts($args);
    // Массив для хранения идентификаторов вариаций, которые подходят под условие
    $filtered_variation_ids = array();

    foreach ($variations as $variation) {
        // Здесь можно добавить дополнительные условия для фильтрации вариаций
        if($_GET['pid']  != $variation->ID){
            $filtered_variation_ids[] = $variation->ID;
        }
    }

    // Заменяем массив $related_products2 на массив идентификаторов подходящих вариаций
    $related_products2 = $filtered_variation_ids;*/
} else {
    // Это не вариативная страница, выполняем запрос для получения связанных продуктов как обычно


    // Получаем список категорий текущего товара
    $product_cats = wp_get_post_terms($current_product_id, 'product_cat');

    // Если товар принадлежит хотя бы одной категории
    if ($product_cats) {
        // Создаем массив где ключ - уровень вложенности, значение - категория
        $levels = array();
        foreach ($product_cats as $cat) {
            $ancestors = get_ancestors($cat->term_id, 'product_cat');
            $level = count($ancestors);
            // Проверяем, если на этом уровне уже есть категория и её ID больше, тогда заменяем
            if (!isset($levels[$level]) || $cat->term_id < $levels[$level]->term_id) {
                $levels[$level] = $cat;
            }
        }

        // Сортируем по ключам и берем последний элемент
        krsort($levels);
        $deepest_cat = reset($levels);

        // Теперь у нас есть категория с наименьшим ID на самом глубоком уровне, можно использовать ее в запросе
        $args = array(
            'post_type' => 'product',
            'posts_per_page' => 100, // Увеличиваем количество товаров в выборке
            'post__not_in' => array($current_product_id),
            'tax_query' => array(
                array(
                    'taxonomy' => 'product_cat',
                    'field' => 'term_id',
                    'terms' => $deepest_cat->term_id,
                ),
            ),
        );

        // Получаем товары
        $potential_related_products = get_posts($args);

        // Массив для хранения идентификаторов продуктов, которые подходят под условие
        $filtered_product_ids = array();

        foreach ($potential_related_products as $product) {
            // Получаем все категории для данного продукта
            $product_categories = wp_get_post_terms($product->ID, 'product_cat');

            // Выбираем категории, которые находятся на одном уровне с $deepest_cat
            $same_level_categories = array_filter($product_categories, function($cat) use ($deepest_cat) {
                return count(get_ancestors($cat->term_id, 'product_cat')) === count(get_ancestors($deepest_cat->term_id, 'product_cat'));
            });

            // Отсеиваем категории, чьи ID больше ID $deepest_cat
            $lower_id_categories = array_filter($same_level_categories, function($cat) use ($deepest_cat) {
                return $cat->term_id <= $deepest_cat->term_id;
            });

            // Если категория с наименьшим ID - это $deepest_cat, то продукт подходит
            if (!empty($lower_id_categories) && min(array_map(function($cat) { return $cat->term_id; }, $lower_id_categories)) === $deepest_cat->term_id) {
                $filtered_product_ids[] = $product->ID;
            }
        }

        // Теперь у нас есть массив идентификаторов продуктов, которые соответствуют условию
        // Можно использовать его для вывода продуктов на странице
        // Ограничиваем количество товаров до 9
        $related_products2 = $filtered_product_ids;




    }

}
function modifyProductHtml($output) {
    if (empty(trim($output))) {
        return $output; // Возвращаем без изменений, если строка пустая
    }
    $dom = new DOMDocument();
    libxml_use_internal_errors(true);
    $dom->loadHTML(mb_convert_encoding($output, 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
    libxml_clear_errors();

    // Удаление первого тега <a> внутри элемента .product-thumb
    $productThumbs = $dom->getElementsByTagName('div');
    foreach ($productThumbs as $thumb) {
        if ($thumb->hasAttribute('class') && strpos($thumb->getAttribute('class'), 'product-thumb') !== false) {
            $aTags = $thumb->getElementsByTagName('a');
            if ($aTags->length > 0) {
                $thumb->removeChild($aTags->item(0));
            }
        }
    }

    // Удаление первого тега <h3> внутри элемента .product-info
    $productInfos = $dom->getElementsByTagName('div');
    foreach ($productInfos as $info) {
        if ($info->hasAttribute('class') && strpos($info->getAttribute('class'), 'product-info') !== false) {
            $h3Tags = $info->getElementsByTagName('h3');
            if ($h3Tags->length > 0) {
                $info->removeChild($h3Tags->item(0));
            }
        }
    }

    // Возвращение обновленного HTML
    return $dom->saveHTML();
}


if ($related_products2) : ?>
    <section class="related products">
        <h2><?php esc_html_e('Related products', 'woocommerce'); ?></h2>

        <?php woocommerce_product_loop_start(); ?>

        <?php
        $count = 0;

        foreach ($related_products2 as $product_id) :
            if ($count >= 15) {
                // Прерываем цикл после вывода 15 продуктов
                break;
            }
            //$visibility = $count < 9 ? '' : ' style="display:none"';
            global $post; // Добавляем глобальную переменную $post
            $post = get_post($product_id); // Получаем объект товара по ID
            setup_postdata($post);

            // Получаем атрибуты
            $tyre_width = get_post_meta($product_id, 'attribute_pa_tyre_width', true);
            $tyre_profile = get_post_meta($product_id, 'attribute_pa_tyre_profile', true);
            $tyre_rim = get_post_meta($product_id, 'attribute_pa_tyre_rim', true);
            $load_index = get_post_meta($product_id, 'attribute_pa_load_index', true);
            $speed_index = get_post_meta($product_id, 'attribute_pa_speed_index', true);

            // Формируем строку с атрибутами
            $attributes_string = "$tyre_width/$tyre_profile $tyre_rim $load_index $speed_index";
            ob_start(); // Запускаем буферизацию вывода
            wc_get_template_part('content', 'product');
            $output = ob_get_contents(); // Получаем содержимое буфера
            ob_end_clean(); // Завершаем буферизацию и очищаем буфер
            if ($isVariative){
                $output = modifyProductHtml($output);
            }
            if (!empty(trim($output))) {
                // Если $output не пуст, выводим элемент
                if($isVariative){

                    $product_name = $post->post_title;
                    $new_product_name = $product_name ;//. " " . $attributes_string;


                    $desc = get_post_meta( $product_id, '_variation_description', true );

                    if(empty($desc) && $post !== null) {
                        // Получаем содержимое из поля description объекта $offer
                        $product = wc_get_product($product_id);
                        if ($product) {
                            $offer_description = $product->get_description();
                        }

                        // Если содержимое поля description есть, добавляем его к названию
                        if (!empty($offer_description)) {
                            $desc = $offer_description;
                        } else {
                            $desc = $product_name;
                        }
                    }
                   /*
echo '<!--';
echo '<pre>';print_r($product_name); echo '</pre>';
echo '<pre>';print_r($new_product_name); echo '</pre>';
echo '<pre>';print_r($output); echo '</pre>';
echo '/-->';*/
                    $output = str_replace($product_name, $new_product_name, $output);
                }

                echo $output;
                $count++;
            }
        endforeach;

        ?>

        <?php woocommerce_product_loop_end(); ?>

    </section>

    <?php
    wp_reset_postdata();
endif;
?>

<script>
    jQuery(document).ready(function($) {
        // Прячем лишние продукты при загрузке страницы
        $('.products.columns-6 li.product-item:gt(8)').hide();

        $('.products .columns-6').css('display', 'flex');
        $('.products .columns-6').css('flex-wrap', 'wrap');

        $(window).scroll(function() {
            if ($(window).scrollTop() >= $(document).height() - $(window).height() - 600) {
                // Показываем все скрытые продукты, если пользователь прокрутил страницу вниз
                $('.products.columns-6 li.product-item:hidden').show();
            }
        });
    });
</script>


