<?php
/**
 * The template for displaying all single posts and attachments
 *
 * @package KuteTheme
 * @subpackage tools
 * @since tools 1.0
 */

get_header();
?>
<?php
/*Single post layout*/
$tools_blog_layout = Tools_Functions::get_option('ovic_sidebar_single_layout','left');
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
                <?php
                while (have_posts()): the_post();
                    get_template_part( 'templates/blogs/single');

                    /*If comments are open or we have at least one comment, load up the comment template.*/
                    if ( comments_open() || get_comments_number() ) :
                        comments_template();
                    endif;
                endwhile;
                ?>
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
<?php get_footer(); ?>