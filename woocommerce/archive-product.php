<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;
get_header();
?>
<?php
if ( skolka_get_option('display_page_header_product_archive') ) {
if ( class_exists( 'WooCommerce' ) ) { ?>
<header class="shop-page-header">
	<div class="container">
	<div class="left">
	<?php woocommerce_breadcrumb(); ?>
	<h2><?php single_term_title(); ?></h2>
	</div>
	<div class="right">
	<div class="listing">
	<span><?php echo esc_html('Listing'); ?> </span>
	<svg width="32" height="20" viewBox="0 0 32 20" fill="none" class="grid-view three-col active">
	<path d="M2 0.5H7C7.82843 0.5 8.5 1.17157 8.5 2V18C8.5 18.8284 7.82843 19.5 7 19.5H2C1.17157 19.5 0.5 18.8284 0.5 18V2C0.5 1.17157 1.17157 0.5 2 0.5ZM13 0.5H18C18.8284 0.5 19.5 1.17157 19.5 2V18C19.5 18.8284 18.8284 19.5 18 19.5H13C12.1716 19.5 11.5 18.8284 11.5 18V2C11.5 1.17157 12.1716 0.5 13 0.5ZM25 0.5H30C30.8284 0.5 31.5 1.17157 31.5 2V18C31.5 18.8284 30.8284 19.5 30 19.5H25C24.1716 19.5 23.5 18.8284 23.5 18V2C23.5 1.17157 24.1716 0.5 25 0.5Z" stroke="var(--color-dark)"/>
	</svg>
	<svg width="20" height="20" viewBox="0 0 20 20" fill="none" class="grid-view two-col">
	<path d="M2 0.5H7C7.82843 0.5 8.5 1.17157 8.5 2V18C8.5 18.8284 7.82843 19.5 7 19.5H2C1.17157 19.5 0.5 18.8284 0.5 18V2C0.5 1.17157 1.17157 0.5 2 0.5ZM13 0.5H18C18.8284 0.5 19.5 1.17157 19.5 2V18C19.5 18.8284 18.8284 19.5 18 19.5H13C12.1716 19.5 11.5 18.8284 11.5 18V2C11.5 1.17157 12.1716 0.5 13 0.5Z" stroke="var(--color-dark)"/>
	</svg>
	</div>

<?php woocommerce_catalog_ordering(); ?>
	</div>

	<div class="line"></div>
	</div>
</header>
<?php } } ?>
<section class="content-section">
	<div class="container">
		<?php
			/**
			 * Woocommerce_before_main_content hook.
			 *
			 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
			 * @hooked woocommerce_breadcrumb - 20
			 *
			 * @since 3.4.0
			 *
			 *
			 */
			do_action( 'woocommerce_before_main_content' );
		?>
		<div class="row">
	<?php if ( is_active_sidebar( 'shop-filter' ) && skolka_get_option('display_product_sidebar') ) : ?>

<div class="col-lg-3">
	<button class="shop-filter-button"><?php echo esc_html('Sidebar', 'skolka'); ?>
		<svg width="27" height="14" viewBox="0 0 27 14" fill="none">
		<rect width="27" height="2" rx="1" fill="var(--color-dark)"/>
		<rect y="6" width="18" height="2" rx="1" fill="var(--color-dark)"/>
		<rect y="12" width="12" height="2" rx="1" fill="var(--color-dark)"/>
		</svg>
		</button>
	<aside class="sidebar shop-filter">
		<?php dynamic_sidebar( 'shop-filter' ); ?>
	</aside>
</div>
	<?php endif; ?>
<div class="
<?php if ( is_active_sidebar( 'shop-filter' ) && skolka_get_option('display_product_sidebar') ) : ?>
	col-lg-9
	<?php endif; ?> col-12">


<?php if (category_description()) { ?>
<div class="category-description">
	<?php echo category_description(); ?>
</div>
<?php } ?>

<?php
if ( is_product_category() ) {
$queriedObject =get_queried_object();
	if ( have_rows( 'category_slider', 'product_cat_' . $queriedObject->term_id) ) :
		?>
<div class="category-slider">
	<div class="swiper-wrapper">
	<?php
		while ( have_rows( 'category_slider', 'product_cat_' . $queriedObject->term_id) ) :
			the_row();
			$slide_image_id = get_sub_field('slide_image')['ID'];
			$slide_image_url = wp_get_attachment_image_src( $slide_image_id, 'category_image_size' )[0];

			?>
			<div class="swiper-slide">
				<img src="
				<?php
				echo esc_url( $slide_image_url );
				?>
			" alt="
				<?php
				echo esc_attr('Image');
				?>
			" /></a></div>
	<?php endwhile; ?>
	</div>
	<div class="swiper-pagination"></div>
</div>
<?php endif; } ?>


	<div class="product-listing-wrapper">
	<?php
	if ( woocommerce_product_loop() ) {

		/**
		 * Hook: woocommerce_before_shop_loop.
		 *
		 * @hooked woocommerce_output_all_notices - 10
		 * @hooked woocommerce_result_count - 20
		 * @hooked woocommerce_catalog_ordering - 30
		 *
		 * @since 3.4.0
		 *
		 */
		do_action( 'woocommerce_before_shop_loop' );

		woocommerce_product_loop_start();

		if ( wc_get_loop_prop( 'total' ) ) {
			while ( have_posts() ) {
				the_post();

				/**
				 * Hook: woocommerce_shop_loop.
				 *
				 * @since 3.4.0
				 *
				 */
				do_action( 'woocommerce_shop_loop' );

				wc_get_template_part( 'content', 'product' );
			}
		}

		woocommerce_product_loop_end();

		/**
		 * Hook: woocommerce_after_shop_loop.
		 *
		 * @hooked woocommerce_pagination - 10
		 *
		 * @since 3.4.0
		 *
		 */
		do_action( 'woocommerce_after_shop_loop' );
	} else {
		/**
		 * Hook: woocommerce_no_products_found.
		 *
		 * @hooked wc_no_products_found - 10
		 *
		 * @since 3.4.0
		 *
		 */
		do_action( 'woocommerce_no_products_found' );
	}

	/**
	 * Hook: woocommerce_after_main_content.
	 *
	 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
	 *
	 * @since 3.4.0
	 *
	 */
	do_action( 'woocommerce_after_main_content' );



	?>
</div>
</div>
</div>
</div>
</section>
<?php

get_footer( 'shop' );
