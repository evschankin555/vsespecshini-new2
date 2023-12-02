<?php
/**
 * Template Name: Full Width Page - No Title
 *
 * @package WordPress
 * @subpackage Tools
 * @since Tools 1.0
 */
get_header();
?>
    <div class="fullwidth-template-no-title">
        <div class="container">
            <?php do_action('ovic_breadcrumb');?>
            <?php
            // Start the loop.
            while ( have_posts() ) : the_post();
                ?>
                <?php the_content( );?>
                <?php
                // End the loop.
            endwhile;
            ?>
        </div>
    </div>
<?php
get_footer();