<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package skolka
 */

get_header();

$error_page_image = ( skolka_get_option( 'error_page_image' ) ) ? skolka_get_option( 'error_page_image' ) : get_template_directory_uri() . '/images/error-404.svg';
$error_page_text  = skolka_get_option( 'error_page_text' );
if ( !$error_page_text ) {
  $error_page_text = esc_html__( 'It looks like nothing was found at this location. Maybe the link is broken or the page has been removed. Please return home.', 'skolka' );
}

skolka_render_page_header( '404' );

?>
<section class="content-section error-404 not-found">
  <div class="container">
	<div class="content-wrapper">
	<img src="<?php echo esc_url( $error_page_image ); ?>" alt="<?php the_title_attribute(); ?>" />
	<h6><?php echo esc_html( $error_page_text ); ?></h6>
	<a href="/" class="404-home-button"><?php echo esc_html('Home'); ?></a>
	<?php
  if ( skolka_get_option('enable_search_form') ) {
  get_search_form();
  }
  ?>
	</div>
  </div>
</section>
<?php
get_footer();
