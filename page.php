<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package skolka
 */

get_header();
?>
<?php
skolka_render_page_header( 'page' );
?>

<section class="content-section">
  <div class="container">
<?php while ( have_posts() ) :
  the_post();
	?>

  <?php
  get_template_part( 'template-parts/content', 'page' );
	?>
  <?php

	if ( comments_open() || get_comments_number() ) :
		?>
		<?php comments_template(); ?>
	<?php endif; ?>

<?php
endwhile;
?>
</div>
</section>
<?php
get_footer();
