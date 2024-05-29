<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package skolka
 */

get_header();
?>
<?php
skolka_render_page_header( 'frontpage' );
$show_sidebar  = ( skolka_get_option( 'archive_show_sidebar' ) ) ? skolka_get_option( 'archive_show_sidebar' ) : 'yes';
$wrapper_cols  = '10';
$section_class = 'blog';

if ( !is_active_sidebar( 'sidebar-1' ) ) {
  $show_sidebar = 'no';
}
if ('yes' == $show_sidebar) {
  $wrapper_cols = '8';
}
?>
<section class="content-section">
  <div class="container">
	<div class="row">
	  <?php
		if ( have_posts() ) :
			?>
	  <div class="col-lg-<?php echo esc_attr( $wrapper_cols ); ?>">
      <div class="ajax-listing-wrapper">
		<?php
			while ( have_posts() ) :
			  the_post();

			get_template_part( 'template-parts/listing' );

		  endwhile;


			?>
    </div>
    <?php skolka_pagination(); ?>
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
		<?php
	  else :
		get_template_part( 'template-parts/content', 'none' );

	  endif;
		?>
	</div>
  </div>
</section>
<?php
get_footer();
