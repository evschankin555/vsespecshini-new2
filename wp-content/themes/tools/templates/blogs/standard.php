<div class="blog-list standard">
    <?php if( have_posts()):?>
        <?php while ( have_posts()): the_post();?>
            <article <?php post_class('post-item');?>>
                <?php Tools_Theme_Functions::post_thumb();?>
                <div class="infos">
                    <div class="metas">
                        <?php
                        if ( is_sticky() && is_home() && ! is_paged() ) {
                            printf( '<span class="sticky-post"><i class="fa fa-flag" aria-hidden="true"></i> %s</span>', esc_html__( 'Sticky', 'tools' ) );
                        }
                        ?>
                        <span class="time">
                            <i class="fa fa-bookmark-o" aria-hidden="true"></i>
                            <?php echo get_the_date();?>
                        </span>
                        <span class="comment">
                            <i class="fa fa-comment-o" aria-hidden="true"></i>
                            <?php comments_number( '0 Comment', '1 Comment', '% Comments' ); ?>.
                        </span>
                    </div>
                    <h3 class="post-title"><a href="<?php the_permalink();?>"><?php the_title();?></a></h3>
                    <div class="post-excerpt"><?php echo wp_trim_words(apply_filters('the_excerpt', get_the_excerpt()), 50, esc_html__('...', 'tools')); ?></div>
                    <a href="<?php the_permalink();?>" class="readmore"><?php esc_html_e('Read more','tools');?></a>
                </div>
            </article>
        <?php endwhile;?>
    <?php endif;?>
</div>