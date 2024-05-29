<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package skolka
 */

?>
<?php skolka_post_thumbnail(); ?>
		<?php
		the_content();

		wp_link_pages( array(
		  'before' => '<div class="page-links"><h6>' . esc_html__( 'Pages:', 'skolka' ) . '</h6>',
		  'after' => '</div>',
		  'link_before' => '<span>',
		  'link_after' => '</span>',
		) );
		?>
