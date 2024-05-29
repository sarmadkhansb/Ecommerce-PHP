<?php
/**
 * Template part for displaying a message that posts cannot be found
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package skolka
 */

?>
<section class="content-section">
<div class="container">
<?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>
<p><?php echo esc_html( 'Ready to publish your first post? ', 'skolka' ); ?><a href="<?php echo esc_url( admin_url( 'post-new.php' ) ); ?>"><?php echo esc_html('Get started here', 'skolka'); ?></a></p>
<?php else : ?>
<p><?php echo esc_html( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'skolka' ); ?></p>
<?php get_search_form(); ?>
<?php endif; ?>
</div>
</section>
