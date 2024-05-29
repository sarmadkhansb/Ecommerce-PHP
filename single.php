<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package skolka
 */

get_header();
?>
<?php
skolka_render_page_header( 'single' );

$show_sidebar = ( skolka_get_option( 'single_show_sidebar' ) ) ? skolka_get_option( 'single_show_sidebar' ) : 'no';
if ( !is_active_sidebar( 'sidebar-1' ) ) {
  $show_sidebar = 'no';
}
$wrapper_cols = '10';

if ('yes' === $show_sidebar) {
  $wrapper_cols = '8';
}

$post_class = array( 'single-post', 'blog-post' );
?>
<section class="content-section">
  <div class="container">
	<div class="row">
	  <div class="col-lg-<?php echo esc_attr( $wrapper_cols ); ?>">
		<div id="post-<?php the_ID(); ?>" <?php post_class( $post_class ); ?>>
		  <?php skolka_post_thumbnail(); ?>
		  <?php
			while ( have_posts() ) :
			  the_post();

			get_template_part( 'template-parts/content', get_post_type() );

				if ( comments_open() || get_comments_number() ) :
					comments_template();
			endif;
				?>
		  <div class="clearfix"></div>
      <?php
      $display_post_navigation = ( skolka_get_option( 'display_post_navigation' ) ) ? skolka_get_option( 'display_post_navigation' ) : 'yes';
      if ('yes' === $display_post_navigation) { ?>
		  <div class="post-navigation">
			  <?php the_post_navigation(); ?>
		  </div>
    <?php } ?>
			<?php
		  endwhile;
			?>
		</div>
	  </div>
	  <?php
		if ('yes' == $show_sidebar) {
			?>
	  <div class="col-lg-4">
		  <?php get_sidebar(); ?>
	  </div>
		<?php
		}
		?>
	</div>
  </div>
</section>
<?php
get_footer();
