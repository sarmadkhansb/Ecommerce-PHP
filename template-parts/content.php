<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package skolka
 */

?>
<div class="post-content">

	<?php
	if ( 'post' === get_post_type() ) :
		?>
	<small class="date">
	<?php the_time('F d, Y'); ?>
	</small>
	  <div class="post-author">
		 <img src="<?php echo esc_url( get_avatar_url( get_the_author_meta( 'user_email' ) ) ); ?>" alt="<?php echo esc_html( 'Author' ); ?>">
		<span><?php echo esc_html( 'by' ); ?></span> <?php echo esc_html( get_the_author_meta('display_name') ); ?>
	  </div>
	<?php the_tags( '<ul class="post-tags"><li>', '</li><li>', '</li></ul>' ); ?>
	<?php
	endif;
	?>


  <?php
  the_content( sprintf(
	'%s %s',
	esc_html__( 'Continue reading', 'skolka' ),
	'<span class="screen-reader-text"> ' . get_the_title() . '</span>'
  ) );

  wp_link_pages( array(
	'before' => '<div class="page-links"><h6>' . esc_html__( 'Pages:', 'skolka' ) . '</h6>',
	'after' => '</div>',
	'link_before' => '<span>',
	'link_after' => '</span>',
  ) );
	?>

</div>
