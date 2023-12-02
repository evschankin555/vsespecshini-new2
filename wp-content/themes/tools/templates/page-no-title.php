<?php
/**
 * Template Name: Page - No Title
 *
 * @package WordPress
 * @subpackage Tools
 * @since Tools 1.0
 */
?>
<?php get_header();?>

<?php
/*Default  page layout*/

$tools_page_layout = Tools_Functions::get_post_meta(get_the_ID(),'ovic_page_layout','left');
/*Main container class*/
$tools_main_container_class = array();
$tools_main_container_class[] = 'main-container';
if( $tools_page_layout == 'full'){
    $tools_main_container_class[] = 'no-sidebar';
}else{
    $tools_main_container_class[] = $tools_page_layout.'-slidebar';
}
$tools_main_content_class = array();
$tools_main_content_class[] = 'main-content';
if( $tools_page_layout == 'full' ){
    $tools_main_content_class[] ='col-sm-12';
}else{
    $tools_main_content_class[] = 'col-md-9 col-sm-8';
}
$tools_slidebar_class = array();
$tools_slidebar_class[] = 'sidebar';
if( $tools_page_layout != 'full'){
    $tools_slidebar_class[] = 'col-md-3 col-sm-4';
}
?>
<?php do_action('tools_before_content_wappper');?>
    <main class="site-main <?php echo esc_attr( implode(' ', $tools_main_container_class) );?>">
        <div class="container">
            <?php do_action('tools_before_content_inner');?>
            <?php do_action('ovic_breadcrumb');?>
            <div class="row">
                <div class="<?php echo esc_attr( implode(' ', $tools_main_content_class) );?>">
                    <?php
                    if( have_posts()){
                        while( have_posts()){
                            the_post();
                            ?>
                            <div class="page-main-content">
                                <?php
                                the_content();
                                wp_link_pages( array(
                                    'before'      => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'tools' ) . '</span>',
                                    'after'       => '</div>',
                                    'link_before' => '<span>',
                                    'link_after'  => '</span>',
                                    'pagelink'    => '<span class="screen-reader-text">' . esc_html__( 'Page', 'tools' ) . ' </span>%',
                                    'separator'   => '<span class="screen-reader-text">, </span>',
                                ) );
                                ?>
                            </div>
                            <?php
                            // If comments are open or we have at least one comment, load up the comment template.
                            if ( comments_open() || get_comments_number() ) :
                                comments_template();
                            endif;
                            ?>
                            <?php
                        }
                    }
                    ?>
                </div>
                <?php if( $tools_page_layout != "full" ):?>
                    <div class="<?php echo esc_attr( implode(' ', $tools_slidebar_class) );?>">
                        <?php get_sidebar('page');?>
                    </div>
                <?php endif;?>
            </div>
            <?php do_action('tools_after_content_inner');?>
        </div>
    </main>
<?php do_action('tools_after_content_wappper');?>
<?php get_footer();?>