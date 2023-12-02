<div class="blog-single">
    <article <?php post_class('post-item');?>>
        <?php Tools_Theme_Functions::post_thumb();?>
        <div class="infos">
            <div class="metas">
                <span class="author">
                    <?php esc_html_e('By:','tools');?>
                    <span><?php the_author();?></span>
                </span>
                <span class="time">
                    <i class="fa fa-bookmark-o" aria-hidden="true"></i>
                    <?php echo get_the_date();?>
                </span>
                <span class="comment">
                    <i class="fa fa-comment-o" aria-hidden="true"></i>
                    <?php comments_number( '0 Comment', '1 Comment', '% Comments' ); ?>.
                </span>
            </div>
            <h3 class="post-title"><?php the_title();?></h3>
            <div class="post-content"><?php the_content();?></div>
        </div>
        <?php
        wp_link_pages( array(
            'before'      => '<div class="page-links">',
            'after'       => '</div>',
            'link_before' => '<span>',
            'link_after'  => '</span>',
            'pagelink'    => '%',
            'separator'   => '',
        ) );
        ?>
    </article>
</div>