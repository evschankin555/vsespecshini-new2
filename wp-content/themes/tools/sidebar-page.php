<?php
$tools_page_used_sidebar = Tools_Functions::get_post_meta(get_the_ID(),'ovic_page_used_sidebar','widget-area');
?>
<?php if ( is_active_sidebar( $tools_page_used_sidebar ) ) : ?>
    <div id="widget-area" class="widget-area">
        <?php dynamic_sidebar( $tools_page_used_sidebar ); ?>
    </div><!-- .widget-area -->
<?php endif; ?>
