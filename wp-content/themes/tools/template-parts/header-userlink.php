<?php
$myaccount_page_id = get_option( 'woocommerce_myaccount_page_id' );
$myaccount_link = get_permalink( get_option('woocommerce_myaccount_page_id') );
$woocommerce_enable_myaccount_registration = get_option('woocommerce_enable_myaccount_registration');
$login_text = esc_html__('Login','tools');
if( $woocommerce_enable_myaccount_registration == "yes" ){
    $login_text .= esc_html__(' or Register','tools');
}
$logout_url = "#";
if ( $myaccount_page_id ) {
    if( function_exists( 'wc_get_page_id' ) ){
        $logout_url = wp_logout_url( get_permalink( wc_get_page_id( 'shop' ) ) );
    }
    if ( get_option( 'woocommerce_force_ssl_checkout' ) == 'yes' ){
        $logout_url = str_replace( 'http:', 'https:', $logout_url );
    }
}
?>
<?php if( is_user_logged_in()): ?>
    <?php
    $currentUser = wp_get_current_user();
    ?>
    <li class="menu-item-has-children menu-item">
        <a data-toggle="dropdown" class="dropdown-toggle" href="<?php echo esc_url( $myaccount_link );?>">
            <span class="icon fa fa-user" aria-hidden="true"></span>
            <?php esc_html_e('Hi, ','tools'); echo esc_html($currentUser->display_name); ?>
        </a>
        <?php if( function_exists( 'wc_get_account_menu_items' )):?>
            <ul class="sub-menu">
                <?php foreach ( wc_get_account_menu_items() as $endpoint => $label ) : ?>
                    <li class="<?php echo wc_get_account_menu_item_classes( $endpoint ); ?>">
                        <a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>"><?php echo esc_html( $label ); ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif;?>
    </li>
<?php else:?>
    <li>
        <a href="<?php echo esc_url( $myaccount_link );?>">
            <span class="icon fa fa-user" aria-hidden="true"></span>
            <?php echo esc_html($login_text);?>
        </a>
    </li>
<?php endif;?>

