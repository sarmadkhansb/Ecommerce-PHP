<?php
ob_start();

$read_more_label = skolka_get_option( 'read_more_label' ) ? skolka_get_option( 'read_more_label' ) : 'Continue reading';
?>
<div id="post-<?php the_ID(); ?>" <?php post_class( array( 'blog-post' ) ); ?>>
  <?php if ( skolka_get_post_thumbnail_url() ) { ?>
  <figure class="post-image"> <img src="<?php echo esc_url( skolka_get_post_thumbnail_url() ); ?>" alt="<?php the_title_attribute(); ?>"> </figure>
  <?php } ?>
  <div class="post-content">
    <?php
    if ( skolka_get_option('display_post_date') ) { ?>
    <small class="date">
	  <?php if ( ! empty( $post->post_title ) ) : ?>
	  <?php echo get_the_date(); ?>
	<?php else : ?>
	 <a href="<?php the_permalink(); ?>"> <?php echo get_the_date(); ?></a>
	  <?php endif; ?>
	  </small>
    <?php } ?>
	<?php if ( ! empty( $post->post_title ) ) : ?>
	<h3 class="post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3><?php endif; ?>
  <?php
  if ( skolka_get_option('display_post_author') ) { ?>
<div class="post-author"><img src="<?php echo esc_url( get_avatar_url( get_the_author_meta( 'user_email' ) ) ); ?>" alt="<?php echo esc_html( 'Author' ); ?>"><span><?php echo esc_html( 'by' ); ?></span> <?php echo esc_html( get_the_author_meta('display_name') ); ?></div>
<?php } ?>
<?php
if ( skolka_get_option('display_post_excerpt') ) {
if ( get_the_content() ) { ?>
<div class="excerpt">
<?php
	$content = get_the_content();
	$content = preg_replace( '~\[[^\]]+\]~', '', $content );
	$excerpt = wp_trim_words( $content, 50, ' ' );
	echo esc_html( $excerpt );
	?>
</div>
<?php } } ?>
<?php
if (isset($excerpt) && strlen($excerpt) >= 50) {
	?>
	<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" class="readmore-link"><?php echo esc_html( $read_more_label ); ?></a><?php	} ?>
</div>
</div>
