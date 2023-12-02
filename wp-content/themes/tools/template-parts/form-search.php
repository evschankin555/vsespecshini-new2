<?php
$selected = '';
if( isset( $_GET['product_cat']) && $_GET['product_cat'] ){
    $selected = $_GET['product_cat'];
}
$args = array(
    'show_option_none' => esc_html__( 'Все категории', 'tools' ),
    'taxonomy'          => 'product_cat',
    'class'             => 'categori-search-option',
    'hide_empty'        => 1,
    'orderby'           => 'name',
    'order'             => "asc",
    'tab_index'         => true,
    'hierarchical'      => true,
    'id'                => rand(),
    'name'              => 'product_cat',
    'value_field'       => 'slug',
    'selected'          => $selected,
    'option_none_value' => '0',
);
?>
<form method="get" action="<?php echo esc_url( home_url( '/' ) ) ?>" class="form-search ovic-live-search-form">

    <div class="serach-box results-search">
        <input autocomplete="off" type="text" class="serchfield txt-livesearch"  name="s" value ="<?php echo esc_attr( get_search_query() );?>"  placeholder="<?php esc_html_e('Поиск: 385 65 22,5 ','tools');?>">
        <?php if( class_exists( 'WooCommerce' ) ): ?>
            <input type="hidden" name="post_type" value="product" />
            <input type="hidden" name="taxonomy" value="product_cat">
            <div class="categories">
                <?php wp_dropdown_categories( $args ); ?>
            </div>
        <?php endif; ?>
        <button class="button"><?php esc_html_e('Найти','tools');?></button>
    </div>

</form>