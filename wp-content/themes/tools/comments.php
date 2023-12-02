<?php
/**
 * The template for displaying comments
 *
 * The area of the page that contains both current comments
 * and the comment form.
 *
 * @package WordPress
 * @subpackage Tools
 * @since bouttique 1.0
 */
/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
    return;
}

$comment_field  = '<p class="comment-form-comment"><label>'.esc_html__( 'Comment', 'tools' ).' </label><textarea class="input-form" id="comment" name="comment" cols="45" rows="8" aria-required="true">' .
    '</textarea></p>';
$fields = array(
    'author' => '<div class="row"><div class="col-xs-12 col-sm-6"><p><label>'.esc_html__( 'Name', 'tools' ).' <span class="required">*</span></label><input type="text" name="author" id="name" class="input-form" /></p></div>',
    'email'  => '<div class="col-xs-12 col-sm-6"><p><label>'.esc_html__( 'Email', 'tools' ).' <span class="required">*</span></label><input type="text" name="email" id="email" class="input-form" /></p></div></div><!-- /.row -->',
);


$comment_form_args = array(
    'class_submit' =>'button',
    'comment_field'=> $comment_field,
    'fields'       => $fields
);
?>
<div id="comments" class="post-comments">
    <?php if ( have_comments() ) : ?>
        <h4 class="block-title"> <?php esc_html_e('Comments','tools')?> <span class="count">(<?php echo get_comments_number();?>)</span></h4>
        <?php Tools_Theme_Functions::comment_pagination(); ?>
        <ol class="comments">
            <?php
            wp_list_comments( array(
                'style'       => 'ol',
                'short_ping'  => true,
                'avatar_size' => 95,
                'callback'	  => 'Tools_Theme_Functions::custom_comment'
            ) );
            ?>
        </ol><!-- .comment-list -->
        <?php Tools_Theme_Functions::comment_pagination(); ?>
    <?php endif; // have_comments() ?>
    <?php
    // If comments are closed and there are comments, let's leave a little note, shall we?
    if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
        ?>
        <p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'tools' ); ?></p>
    <?php endif; ?>
    <?php comment_form($comment_form_args);?>
</div><!-- .comments-area -->