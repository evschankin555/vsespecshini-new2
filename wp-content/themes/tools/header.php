<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WordPress
 * @subpackage Ovicshop
 * @since 1.0
 * @version 1.0
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js no-svg">
<head>
	<meta name="8e2813b26e7f653d0790f9d75952b314" content="">
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php
$ovic_header_email = Tools_Functions::get_option( 'ovic_header_email', '' );
$ovic_header_phone = Tools_Functions::get_option( 'ovic_header_phone', '' );
?>
<header id="header" class="header">
    <div class="top-header">
        <div class="container">
			<?php
			if ( has_nav_menu( 'top_left_menu' ) ) {
				wp_nav_menu( array(
						'menu'            => 'top_left_menu',
						'theme_location'  => 'top_left_menu',
						'depth'           => 2,
						'container'       => '',
						'container_class' => '',
						'container_id'    => '',
						'menu_class'      => 'tools-nav top-bar-menu left header-message',
					)
				);
			}
			if ( has_nav_menu( 'top_right_menu' ) ) {
				wp_nav_menu( array(
						'menu'            => 'top_right_menu',
						'theme_location'  => 'top_right_menu',
						'depth'           => 2,
						'container'       => '',
						'container_class' => '',
						'container_id'    => '',
						'menu_class'      => 'tools-nav top-bar-menu right',
					)
				);
			}
			?>
        </div>
    </div>
    <div class="container">
        <div class="main-header">
            <div class="logo">
				<?php Tools_Theme_Functions::get_logo(); ?>
            </div>
			<?php get_template_part( 'template-parts/form', 'search' ); ?>
			<?php if ( $ovic_header_email != "" || $ovic_header_phone != "" ): ?>
                <div class="main-header-right">
					<?php if ( $ovic_header_phone != "" ): ?>
                        <div class="hotline">
                            <strong><?php esc_html_e( 'Москва:', 'tools' ); ?></strong> <?php echo esc_html( $ovic_header_phone ); ?>
                        </div>
					<?php endif; ?>
					<?php if ( $ovic_header_email != "" ): ?>
                        <div class="email">
                            <strong><?php esc_html_e( 'E-mail:', 'tools' ); ?></strong> <?php echo esc_html( $ovic_header_email ); ?>
                        </div>
					<?php endif; ?>
                </div>
			<?php endif; ?>
        </div>
    </div>
	<?php
	$ovic_enable_vertical_menu = Tools_Functions::get_option( 'ovic_enable_vertical_menu', '0' );
	$header_nav_class          = array( 'header-nav header-sticky' );
	if ( $ovic_enable_vertical_menu == 1 ) {
		$header_nav_class[] = 'has-vertical-menu';
	}
	?>
    <div class="<?php echo esc_attr( implode( ' ', $header_nav_class ) ); ?>">
        <div class="container">
            <div class="header-nav-inner">
				<?php do_action( 'ovic_header_vertical', 'vertical_menu', true ); ?>
                <div class="box-header-nav">
                    <a class="menu-toggle mobile-navigation" href="#">
                            <span class="icon">
                                <span></span>
                                <span></span>
                                <span></span>
                            </span>
                    </a>
					<?php
					if ( has_nav_menu( 'primary' ) ) {
						wp_nav_menu( array(
								'menu'            => 'primary',
								'theme_location'  => 'primary',
								'depth'           => 3,
								'container'       => '',
								'container_class' => '',
								'container_id'    => '',
								'menu_class'      => 'tools-nav main-menu',
								'megamenu'        => true,
								'mobile_enable'   => true,
							)
						);
					}
					?>

                    <div class="header-control">
						<?php do_action( 'tools_header_control' ); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<header class="header-device-mobile">
    <div class="wapper">
        <div class="item mobile-logo">
			<?php Tools_Theme_Functions::get_logo( 'mobile' ); ?>
        </div>
        <div class="item mobile-search-box has-sub">
            <a href="#">
                <span class="icon"><i class="fa fa-search" aria-hidden="true"></i></span>
            </a>
            <div class="block-sub">
                <a href="#" class="close"><i class="fa fa-times" aria-hidden="true"></i></a>
				<?php do_action( 'tools_header_mobile_search_block' ); ?>
            </div>
        </div>
        <div class="item mobile-settings-box has-sub">
            <a href="#">
                <span class="icon"><i class="fa fa-cog" aria-hidden="true"></i></span>
            </a>
            <div class="block-sub">
                <a href="#" class="close"><i class="fa fa-times" aria-hidden="true"></i></a>
				<?php do_action( 'tools_header_mobile_control_block' ); ?>
            </div>
        </div>
        <div class="item menu-bar">
            <a class="menu-toggle mobile-navigation" href="#">
                <span class="icon">
                    <span></span>
                    <span></span>
                    <span></span>
                </span>
            </a>
        </div>
    </div>
</header>
<?php if (is_front_page()) {
    //echo do_shortcode('[vsespecshini_search_tyre]');
}
?>