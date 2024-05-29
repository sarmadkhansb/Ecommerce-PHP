<?php
/**
 * The template for displaying comments
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package skolka
 */

/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if ( post_password_required() ) {
  return;
}

$post_title = get_the_title();
$post_link = get_permalink();
?>
<div class="post-comment" id="comments">
  <?php if ( have_comments() ) : ?>
  <h6 class="comments-title">
    <?php
    $skolka_comment_count = get_comments_number();
    if ( '1' === $skolka_comment_count ) {
      ?>
    <?php echo esc_html('One thought on'); ?> "<?php echo esc_html($post_title); ?>"
    <?php } else { ?>
    <?php echo esc_html($skolka_comment_count); ?> <?php echo esc_html('thoughts on'); ?> "<?php echo esc_html($post_title); ?>"
    <?php } ?>
  </h6>
  <?php
  if ( !comments_open() ):
    ?>
  <p class="no-comments">
    <?php esc_html_e( 'Comments are closed.', 'skolka' ); ?>
  </p>
  <?php endif; ?>
  <ol class="comments comment-list">
    <?php wp_list_comments( array( 'callback' => 'skolka_bootstrap_comment' ) ); ?>
  </ol>
  <?php the_comments_navigation(); ?>
  <?php endif; ?>
  <?php


  if ( comments_open() || pings_open() ) {

    echo '<div class="post-comment">';
    echo '<div class="comment-form">';
    comment_form(
      array(
        'class_form' => 'row',
        'title_reply_before' => '<h6 id="reply-title" class="comment-reply-title">',
        'title_reply_after' => '</h6>',
        'id_form' => 'commentform',
      )
    );

    echo '</div>';
    echo '</div>';


  }


  ?>
</div>
