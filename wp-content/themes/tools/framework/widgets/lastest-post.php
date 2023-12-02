<?php

if ( !class_exists( 'Tools_Lastest_Post_Widget' ) ) {
    class Tools_Lastest_Post_Widget extends Tools_Widget
    {
        function __construct(){
            $this->widget_cssclass    = 'tool widget_lastest_post';
            $this->widget_description = __( "Display the Post.", 'tools' );
            $this->widget_id          = 'widget_lastest_post';
            $this->widget_name        = __( 'Ovic: Lastest Post', 'tools' );
            $all_categories = array(
                'all' => __('All Category','tools')
            );
            $categories = get_categories('hide_empty=0&depth=1&type=post');
            foreach ( $categories as $category){
                $all_categories[$category->term_id] = $category->cat_name;
            }
            $this->settings           = array(
                'title'  => array(
                    'type'  => 'text',
                    'std'   => __( 'Latest Posts', 'tools' ),
                    'label' => __( 'Title', 'tools' ),
                ),
                'categories'  => array(
                    'type'  => 'select',
                    'std'   => 'all',
                    'label' => __( 'Categories', 'tools' ),
                    'options' => $all_categories
                ),
                'number'  => array(
                    'type'  => 'number',
                    'std'   => 3,
                    'label' => __( 'Number of posts to show', 'tools' ),
                ),

            );

            parent::__construct();
        }

        function widget( $args, $instance ){
            $this->widget_start( $args, $instance );
            $query        = array('showposts' => $instance['number'], 'nopaging' => 0, 'post_status' => 'publish', 'ignore_sticky_posts' => 1, 'cat' => $instance['categories']);
            $loop         = new WP_Query($query);
            $width = 100;
            $height = 100;
            $crop = true;
            if( $loop->have_posts()){
                while ($loop->have_posts()){
                    $thumb  ='';
                    $loop->the_post();
                    if( has_filter('ovic_resize_image')){
                        $image_thumb = apply_filters( 'ovic_resize_image', get_post_thumbnail_id(), $width, $height, $crop );
                        if( isset($image_thumb['img']) && $image_thumb['img'] !=""){
                            $thumb = $image_thumb['img'];
                        }
                    }else{
                        if(has_post_thumbnail()){
                            $thumb = get_the_post_thumbnail();
                        }
                    }
                    ?>
                    <div class="post">
                        <?php if( $thumb !=''):?>
                        <div class="thumb">
                            <?php echo $thumb;?>
                        </div>
                        <?php endif;?>
                        <div class="info">
                            <h3 class="post-title"><a href="<?php the_permalink();?>"><?php the_title();?></a></h3>
                            <div class="metas">
                                <span class="time">
                                    <?php echo get_the_date();?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            }
            wp_reset_postdata();
            $this->widget_end( $args );
        }
    }
}

add_action( 'widgets_init', 'Tools_Lastest_Post_Widget' );
if( !function_exists('Tools_Lastest_Post_Widget')){
    function Tools_Lastest_Post_Widget() {
        register_widget( 'Tools_Lastest_Post_Widget' );
    }
}
