<?php get_header();?>
<?php
/* Get Blog Settings */
$tools_blog_layout = Tools_Functions::get_option('ovic_sidebar_blog_layout','left');
$tools_blog_list_style = Tools_Functions::get_option('ovic_blog_list_style','standard');

/*Main container class*/
$tools_main_container_class = array();
$tools_main_container_class[] = 'main-container';
if( $tools_blog_layout == 'full'){
    $tools_main_container_class[] = 'no-sidebar';
}else{
    $tools_main_container_class[] = $tools_blog_layout.'-slidebar';
}


$tools_main_content_class = array();
$tools_main_content_class[] = 'main-content';
if( $tools_blog_layout == 'full' ){
    $tools_main_content_class[] ='col-sm-12';
}else{
    $tools_main_content_class[] = 'col-lg-9 col-md-9 col-sm-8';
}

$tools_slidebar_class = array();
$tools_slidebar_class[] = 'sidebar';
if( $tools_blog_layout != 'full'){
    $tools_slidebar_class[] = 'col-lg-3 col-md-3 col-sm-4';
}
?>
<?php do_action('tools_before_content_wappper');?>
<div class="<?php echo esc_attr( implode(' ', $tools_main_container_class) );?>">
    <div class="container">
        <?php do_action('tools_before_content_inner');?>
        <?php do_action('ovic_breadcrumb');?>
        <div class="row">

            <div class="<?php echo esc_attr( implode(' ', $tools_main_content_class) );?>">
                <!-- Main content -->
                <?php
                get_template_part( 'templates/blogs/'.$tools_blog_list_style);
                ?>
                <!-- ./Main content -->
                <?php Tools_Theme_Functions::pagination();?>
            </div>
            <?php if( $tools_blog_layout != "full" ):?>
                <div class="<?php echo esc_attr( implode(' ', $tools_slidebar_class) );?>">
                    <?php get_sidebar();?>
                </div>
            <?php endif;?>
        </div>
        <?php do_action('tools_after_content_inner');?>
    </div>
</div>
<?php do_action('tools_after_content_wappper');?>
<?php get_footer();?>