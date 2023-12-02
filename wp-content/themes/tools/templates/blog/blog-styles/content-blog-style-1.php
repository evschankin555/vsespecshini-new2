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
</div>