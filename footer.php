<?php
$footer_bg_color      = skolka_get_option( 'footer_bg_color' );
$footer_content_color = skolka_get_option( 'footer_content_color' );


$copyright = skolka_get_option( 'footer_copyright_text' );
if ( !$copyright ) {
  $copyright = esc_html( 'Skolka | A Contemporary E-Commerce Theme', 'skolka' );
}

$creation = skolka_get_option( 'footer_creation' );
if ( !$creation ) {
  $creation = esc_html( 'Site created by', 'skolka' );
}

$creation_link_label = skolka_get_option( 'footer_creation_link_label' );
if ( !$creation_link_label ) {
  $creation_link_label = esc_html( 'themezinho', 'skolka' );
}

$creation_link_url = skolka_get_option( 'footer_creation_link_url' );
if ( !$creation_link_url ) {
  $creation_link_url = esc_html( 'https://themezinho.net', 'skolka' );
}

$enable_footer_icon_bar = skolka_get_option( 'enable_footer_icon_bar' );


?>


<?php if ( class_exists( 'WooCommerce' ) && ( is_checkout() || is_404() || is_account_page() && ! is_user_logged_in() ) ) { ?>
<footer class="simple-footer">
  <div class="container">
  <span class="copyright"> <?php echo esc_html( $copyright ); ?></span>
  <?php if( $creation_link_label ) {?>
  <span class="creation"><?php echo esc_html( $creation ); ?> <a href="<?php echo esc_html( $creation_link_url ); ?>"><?php echo esc_html( $creation_link_label ); ?></a></span>
  <?php } ?>
  </div>
</footer>
  <?php } else { ?>
<?php if ( $enable_footer_icon_bar ) : ?>
<div class="footer-icon-bar">
  <div class="container">
					<?php

					$icon_box = skolka_get_option( 'icon_box' );
					if ( $icon_box ) :
						foreach ( $icon_box as $box ) {
							?>
		  <div class="col">
			<div class="footer-icon-box">
			<img src="<?php echo esc_url( $box['footer_bar_icon'] ); ?>" alt="<?php echo esc_attr( $box['footer_bar_text'] ); ?>">
			<h6><?php echo esc_html( $box['footer_bar_text'] ); ?></h6>
			  </div>
			  <!-- end footer-icon-box -->
		  </div>
		  <!-- end col -->
					  <?php } endif; ?>
  </div>
  <!-- end container -->
</div>
<!-- end footer-icon-bar -->
							<?php endif; ?>
<footer class="footer">
  <?php if ( is_active_sidebar( 'footer-widget-1' ) || is_active_sidebar( 'footer-widget-2' ) || is_active_sidebar( 'footer-widget-3' ) || is_active_sidebar( 'footer-widget-4' )  ) { ?>
  <div class="container">
	  <?php if ( is_active_sidebar( 'footer-widget-1' ) ) : ?>
	  <div class="col">
		<?php dynamic_sidebar( 'footer-widget-1' ); ?>
	  </div>
	  <!-- end col -->
	  <?php endif; ?>
	<?php if ( is_active_sidebar( 'footer-widget-2' ) ) : ?>
	  <div class="col">
		<?php dynamic_sidebar( 'footer-widget-2' ); ?>
	  </div>
	  <!-- end col -->
	  <?php endif; ?>
	  <?php if ( is_active_sidebar( 'footer-widget-3' ) ) : ?>
	  <div class="col">
		<?php dynamic_sidebar( 'footer-widget-3' ); ?>
	  </div>
	  <!-- end col -->
	  <?php endif; ?>
	  <?php if ( is_active_sidebar( 'footer-widget-4' ) ) : ?>
	  <div class="col">
		<?php dynamic_sidebar( 'footer-widget-4' ); ?>
	  </div>
	  <!-- end col -->
	  <?php endif; ?>
  </div>
  <!-- end container -->
  <?php } ?>
  <div class="bottom-bar">
	<div class="container">



    <span class="copyright">&copy; <?php echo date_i18n( _x( 'Y', 'copyright date format', 'skolka' ) ) . ' ' . esc_html( $copyright  ) ; ?></span>
    <?php
    $bottom_bar_icons = skolka_get_option('bottom_bar_icons');
    if( $bottom_bar_icons ): ?>
        <ul class="bottom-bar-icons">
            <?php foreach( $bottom_bar_icons as $icon ): ?>
                <li>
                    <img src="<?php echo esc_url($icon['url']); ?>" alt="<?php echo esc_attr($icon['alt']); ?>" />
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    <?php if( $creation_link_label ) {?>
    <span class="creation"><?php echo esc_html( $creation ); ?> <a href="<?php echo esc_html( $creation_link_url ); ?>"><?php echo esc_html( $creation_link_label ); ?></a></span>
    <?php } ?>
   </div>
	<!-- end container -->
  </div>
  <!-- end bottom-bar -->
</footer>
<!-- end footer -->
<?php } ?>
<?php wp_footer(); ?>
</body></html>
