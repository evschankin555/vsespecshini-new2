<?php
$tools_blog_used_sidebar = Tools_Functions::get_option( 'ovic_blog_used_sidebar', 'widget-area' );
if( is_single()){
    $tools_blog_used_sidebar = Tools_Functions::get_option( 'ovic_single_used_sidebar', 'widget-area' );
}
?>
<?php if ( is_active_sidebar( $tools_blog_used_sidebar ) ) : ?>
    <div id="widget-area" class="widget-area blog-sidebar">

        <?php dynamic_sidebar( $tools_blog_used_sidebar ); ?>
    </div><!-- .widget-area -->
<?php endif; ?>
