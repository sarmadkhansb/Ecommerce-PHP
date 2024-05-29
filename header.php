<!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="https://gmpg.org/xfn/11">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<?php
$site_logo = skolka_get_option('site_logo');
$desktop_logo = isset($site_logo['desktop_logo']) && !empty($site_logo['desktop_logo']) ? $site_logo['desktop_logo'] : get_template_directory_uri() . '/images/logo.svg';
$mobile_logo = isset($site_logo['mobile_logo']) && !empty($site_logo['mobile_logo']) ? $site_logo['mobile_logo'] : get_template_directory_uri() . '/images/logo-mobile.svg';


$topbar_contact_label             = skolka_get_option( 'topbar_contact_label' );
$topbar_contact_link              = skolka_get_option( 'topbar_contact_link' );
$notification_content             = skolka_get_option( 'notification_content' );

?>
<div class="ajax-preloader">
  <div class="spinner"></div>
</div>
<?php if ( skolka_get_option('enable_gotop') ) { ?>
  <div class="goto-top">
    <button id="go-top-btn">
    <i class="fa-solid fa-arrow-up"></i>
    </button>
    <div class="progress-wrapper">
      <div id="progress-bar"></div>
    </div>
  </div>
<?php } ?>
<div class="overlay-layer"></div>


<div class="mobile-menu">
  <div class="mobile-menu-wrapper">
    <div class="topbar-text">
      <?php
      $topbar_text = skolka_get_option('topbar_text');
      echo wp_kses_post($topbar_text);
      ?>
  	</div>
    	<?php skolka_get_topbar_menu(); ?>
    <?php
		if ( has_nav_menu( 'categories' ) ) {
		  wp_nav_menu( array(
			'theme_location' => 'categories',
			'menu_class' => 'categories-menu',
			'container' => false,
			'walker' => new WP_skolka_Navwalker(),
		  ) );
			?>
		<?php } ?>
    <?php if ( has_nav_menu( 'categories' ) && has_nav_menu( 'menu' ) ) : ?>
      <div class="separator"></div>
    <?php endif; ?>
	  <?php
		if ( has_nav_menu( 'menu' ) ) {
		  wp_nav_menu( array(
			'theme_location' => 'menu',
			'menu_class' => 'site-menu',
			'container' => false,
			'walker' => new WP_skolka_Navwalker(),
		  ) );
			?>
		  <?php } ?>
  </div>
  <!-- end menu-wrapper -->
</div>
<!-- end sidebar-menu -->

<?php if ( class_exists( 'WooCommerce' ) && ( is_checkout() || is_404() || is_account_page() && ! is_user_logged_in() ) ) { ?>
<nav class="navbar-simple">
  <div class="container">
	<div class="logo">
    <a href="<?php echo esc_url(home_url('/')); ?>" class="desktop-logo"><img src="<?php echo esc_url($desktop_logo); ?>" alt="<?php bloginfo('name'); ?>"></a>
    <a href="<?php echo esc_url(home_url('/')); ?>" class="mobile-logo"><img src="<?php echo esc_url($mobile_logo); ?>" alt="<?php bloginfo('name'); ?>"></a>
  </div>
	<!-- end logo -->
  <div class="secure-shopping">
    <svg width="22" height="22" viewBox="0 0 32 32"><title/><g id="Fill"><path d="M16,2,3,6.28V12C3,26.61,15.63,29.94,15.76,30L16,30l.24-.06c.13,0,12.76-3.36,12.76-18V6.28ZM27,12c0,12.1-9.31,15.45-11,16C14.31,27.45,5,24.1,5,12V7.72L16,4.05,27,7.72Z"/><path d="M11.59,15l-1.42,1.41,2.29,2.29a2,2,0,0,0,1.42.59,2,2,0,0,0,1.41-.59l6.54-6.53-1.42-1.42-6.53,6.54Z"/></g></svg>
    <?php echo esc_html('Secure Payment'); ?>
  </div>
  </div>
  <!-- end container -->
</nav>
<?php } else { ?>

<?php
	if ( skolka_get_option('display_topbar') ) {
		?>
<div class="topbar">
  <div class="container">
	<div class="hamburger-menu">
	  <span></span>
	</div>
	<!-- end hamburger-menu -->
		<?php skolka_get_topbar_menu(); ?>
	<div class="topbar-text">
    <?php
    $topbar_text = skolka_get_option('topbar_text');
    echo wp_kses_post($topbar_text);
    ?>
	</div>
	<!-- end topbar-text -->
  </div>
</div>
	<?php } ?>
<nav class="navbar">
  <div class="container">
	<div class="logo">
    <a href="<?php echo esc_url(home_url('/')); ?>" class="desktop-logo"><img src="<?php echo esc_url($desktop_logo); ?>" alt="<?php bloginfo('name'); ?>"></a>
    <a href="<?php echo esc_url(home_url('/')); ?>" class="mobile-logo"><img src="<?php echo esc_url($mobile_logo); ?>" alt="<?php bloginfo('name'); ?>"></a>
	</div>
	<?php skolka_get_wpml_langs(); ?>
	<?php if ( class_exists( 'WooCommerce' ) ) { ?>
	<?php skolka_navbar_search(); ?>
	<?php skolka_navbar_wishlist(); ?>
	<?php skolka_navbar_account(); ?>
	<?php skolka_navbar_cart(); ?>
	<?php } ?>
  <?php if ( has_nav_menu( 'categories' ) && has_nav_menu( 'menu' ) ) : ?>
  <div class="hamburger-menu">
    <span><?php echo esc_html('Menu', 'skolka'); ?></span>
    <svg width="27" height="21" viewBox="0 0 27 21" fill="none" xmlns="http://www.w3.org/2000/svg">
    <rect class="top" y="3" width="27" height="2" rx="1" fill="var(--color-dark)"/>
    <rect class="middle-left" y="9" width="7" height="2" rx="1" fill="var(--color-dark)"/>
    <rect class="middle-right" x="11" y="9" width="16" height="2" rx="1" fill="var(--color-dark)"/>
    <rect class="bottom" y="15" width="27" height="2" rx="1" fill="var(--color-dark)"/>
    </svg>
	</div>
  <?php endif; ?>
  <?php
if ( has_nav_menu( 'categories' ) || has_nav_menu( 'menu' ) ) {
?>
	<div class="navbar-menu">

	  <?php
		if ( has_nav_menu( 'categories' ) ) {
		  wp_nav_menu( array(
			'theme_location' => 'categories',
			'menu_class' => 'categories-menu',
			'container' => false,
			'walker' => new WP_skolka_Navwalker(),
		  ) );
			?>
		<?php } ?>
    <?php if ( has_nav_menu( 'categories' ) && has_nav_menu( 'menu' ) ) : ?>
      <div class="separator"></div>
    <?php endif; ?>
	  <?php
		if ( has_nav_menu( 'menu' ) ) {
		  wp_nav_menu( array(
			'theme_location' => 'menu',
			'menu_class' => 'site-menu',
			'container' => false,
			'walker' => new WP_skolka_Navwalker(),
		  ) );
			?>
		  <?php } ?>

	</div>
  <?php
}
?>
  </div>
</nav>

<?php if ( class_exists( 'WooCommerce' ) ) { ?>
<nav class="shopping-navbar">
  <div class="container">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
      <svg width="17" height="18" viewBox="0 0 17 18" fill="none">
      <path d="M10.0383 1.39883C9.28808 0.766369 8.19105 0.767138 7.44177 1.40065L1.63818 6.30724C1.18589 6.68963 0.925 7.25182 0.925 7.84409V16.2829C0.925 17.1566 1.63326 17.8648 2.50694 17.8648H14.9931C15.8667 17.8648 16.575 17.1566 16.575 16.2829V7.8452C16.575 7.25193 16.3132 6.6889 15.8596 6.30652L10.0383 1.39883ZM2.56895 7.40817L8.37251 2.50158C8.5851 2.32189 8.89624 2.32168 9.10905 2.50107L14.9304 7.40876C15.0591 7.51722 15.1333 7.67693 15.1333 7.8452V16.2829C15.1333 16.3603 15.0705 16.4231 14.9931 16.4231H2.50694C2.42949 16.4231 2.36667 16.3603 2.36667 16.2829V7.84409C2.36667 7.6761 2.44067 7.51663 2.56895 7.40817Z" fill="white" stroke="white" stroke-width="0.15"/>
      </svg>
      <span><?php echo esc_html('Home', 'skolka'); ?></span>
    </a>
    <a href="<?php echo esc_url( home_url( '/wishlist/' ) ); ?>"><svg width="20" height="18" viewBox="0 0 20 18" fill="none">
    <path d="M17.5916 2.33333C17.0679 1.81219 16.4445 1.40193 15.7587 1.12703C15.0729 0.852129 14.3387 0.71823 13.6 0.733326C12.256 0.741018 10.9667 1.2663 9.99998 2.19999C8.96283 1.2364 7.59275 0.71201 6.17727 0.73687C4.7618 0.76173 3.41098 1.33391 2.40831 2.33333C0.0749766 4.56666 0.366643 8.41666 3.14164 11.3L8.84164 16.825C9.14595 17.1401 9.56207 17.3227 9.99998 17.3333C10.4239 17.334 10.8322 17.1731 11.1416 16.8833L11.3083 16.725L11.8166 16.25C12.3166 15.775 13 15.1083 13.8916 14.2417L15.925 12.25L16.8333 11.3583C19.65 8.39999 20 4.60833 17.5916 2.33333ZM15.6333 10.1667L15.425 10.3667L14.7583 11.025L12.725 13.0167C11.8916 13.85 11.1916 14.5083 10.6916 14.9833L9.99998 15.6667L4.30831 10.1C2.17498 7.88333 1.92498 5.09999 3.57498 3.49999C3.92486 3.14926 4.3405 2.87099 4.79809 2.68112C5.25568 2.49126 5.74623 2.39353 6.24164 2.39353C6.73706 2.39353 7.22761 2.49126 7.68519 2.68112C8.14278 2.87099 8.55842 3.14926 8.90831 3.49999L8.98331 3.56666C9.0806 3.64055 9.16997 3.72433 9.24998 3.81666L9.99998 4.59999L10.75 3.81666L10.9833 3.59166L11.075 3.51666C11.4253 3.16216 11.8425 2.8807 12.3024 2.68861C12.7623 2.49652 13.2557 2.3976 13.7541 2.3976C14.2525 2.3976 14.746 2.49652 15.2059 2.68861C15.6658 2.8807 16.083 3.16216 16.4333 3.51666C18.075 5.09999 17.825 7.89166 15.6333 10.1667Z" fill="white"/>
    </svg>
    <span><?php echo esc_html('Favorites', 'skolka'); ?></span>
    </a>
    <a href="<?php echo esc_url( home_url( '/my-account/' ) ); ?>">
      <svg width="17" height="19" viewBox="0 0 20 22" fill="none">
    	<path d="M0.958459 21.5C0.406175 21.5 -0.0462572 21.0514 0.00378979 20.5014C0.0980957 19.4649 0.338862 18.4471 0.719665 17.4818C1.22221 16.2079 1.95881 15.0504 2.88739 14.0754C3.81598 13.1004 4.91837 12.3269 6.13163 11.7993C7.34488 11.2716 8.64524 11 9.95846 11C11.2717 11 12.572 11.2716 13.7853 11.7993C14.9986 12.3269 16.1009 13.1004 17.0295 14.0754C17.9581 15.0504 18.6947 16.2079 19.1973 17.4818C19.5781 18.4471 19.8188 19.4649 19.9131 20.5014C19.9632 21.0514 19.5107 21.5 18.9585 21.5C18.4062 21.5 17.9643 21.0509 17.9018 20.5021C17.8152 19.7418 17.6297 18.9958 17.3495 18.2855C16.9475 17.2663 16.3582 16.3403 15.6153 15.5603C14.8724 14.7803 13.9905 14.1616 13.0199 13.7394C12.0493 13.3173 11.009 13.1 9.95846 13.1C8.90789 13.1 7.8676 13.3173 6.89699 13.7394C5.92639 14.1616 5.04448 14.7803 4.30161 15.5603C3.55874 16.3403 2.96946 17.2663 2.56742 18.2855C2.28719 18.9958 2.10175 19.7418 2.01511 20.5021C1.95258 21.0509 1.51074 21.5 0.958459 21.5Z" fill="#ffffff"/>
    	<path d="M13.9585 5C13.9585 7.20914 12.1676 9 9.95846 9C7.74932 9 5.95846 7.20914 5.95846 5C5.95846 2.79086 7.74932 1 9.95846 1C12.1676 1 13.9585 2.79086 13.9585 5ZM1.02154 20.3889C1.02073 20.396 1.01952 20.4028 1.01797 20.4094C1.11426 19.5302 1.32665 18.6682 1.6499 17.8488C2.106 16.6926 2.77333 15.6451 3.61153 14.765C4.44964 13.885 5.44195 13.1897 6.53046 12.7163C7.61884 12.2429 8.78353 12 9.95846 12C11.1334 12 12.2981 12.2429 13.3865 12.7163C14.475 13.1897 15.4673 13.885 16.3054 14.765C17.1436 15.6451 17.8109 16.6926 18.267 17.8488C18.5903 18.6682 18.8027 19.5302 18.8989 20.4094C18.8974 20.4028 18.8962 20.396 18.8954 20.3889C18.7989 19.5423 18.5924 18.711 18.2797 17.9185C17.8312 16.7816 17.1727 15.7456 16.3395 14.8706C15.5061 13.9956 14.5141 13.2988 13.4188 12.8224C12.3233 12.3459 11.1473 12.1 9.95846 12.1C8.7696 12.1 7.59364 12.3459 6.49816 12.8224C5.40281 13.2988 4.41082 13.9956 3.57747 14.8706C2.74422 15.7456 2.08567 16.7816 1.63719 17.9185C1.32455 18.711 1.11801 19.5423 1.02154 20.3889Z" stroke="#ffffff" stroke-width="2"/>
    	</svg>
      <span><?php echo esc_html('Account', 'skolka'); ?></span>
    </a>
    <a href="<?php echo esc_url( home_url( '/cart/' ) ); ?>">
      <svg width="20" height="20" viewBox="0 0 20 20" fill="none">
      <path d="M16.6667 4.16675H3.33341C2.89139 4.16675 2.46746 4.34234 2.1549 4.6549C1.84234 4.96746 1.66675 5.39139 1.66675 5.83341V16.6667C1.66675 17.3298 1.93014 17.9657 2.39898 18.4345C2.86782 18.9034 3.50371 19.1667 4.16675 19.1667H15.8334C16.4965 19.1667 17.1323 18.9034 17.6012 18.4345C18.07 17.9657 18.3334 17.3298 18.3334 16.6667V5.83341C18.3334 5.39139 18.1578 4.96746 17.8453 4.6549C17.5327 4.34234 17.1088 4.16675 16.6667 4.16675ZM16.6667 16.6667C16.6667 16.8878 16.5789 17.0997 16.4227 17.256C16.2664 17.4123 16.0544 17.5001 15.8334 17.5001H4.16675C3.94573 17.5001 3.73377 17.4123 3.57749 17.256C3.42121 17.0997 3.33341 16.8878 3.33341 16.6667V5.83341H16.6667V16.6667Z" fill="white"/>
      <path d="M6.66675 4.58341C6.20651 4.58341 5.82629 4.20781 5.90223 3.75388C5.95512 3.43774 6.03822 3.12785 6.15058 2.82945C6.35998 2.27337 6.66689 1.76811 7.0538 1.34251C7.44072 0.916906 7.90004 0.5793 8.40557 0.348966C8.91109 0.118632 9.45291 8.06502e-05 10.0001 8.06981e-05C10.5473 8.07459e-05 11.0891 0.118632 11.5946 0.348966C12.1001 0.579301 12.5594 0.916906 12.9464 1.34251C13.3333 1.76811 13.6402 2.27337 13.8496 2.82945C13.9619 3.12785 14.045 3.43774 14.0979 3.75388C14.1739 4.20781 13.7937 4.58341 13.3334 4.58341C12.8732 4.58341 12.5117 4.20329 12.3856 3.76068C12.3635 3.68306 12.3382 3.60644 12.3098 3.53104C12.1841 3.19739 12 2.89423 11.7678 2.63887C11.5357 2.38351 11.2601 2.18095 10.9568 2.04275C10.6535 1.90455 10.3284 1.83341 10.0001 1.83341C9.67178 1.83341 9.34669 1.90454 9.04337 2.04275C8.74006 2.18095 8.46446 2.38351 8.23232 2.63887C8.00017 2.89423 7.81602 3.19739 7.69038 3.53103C7.66199 3.60644 7.63671 3.68306 7.61459 3.76068C7.48843 4.20328 7.12699 4.58341 6.66675 4.58341Z" fill="white"/>
      </svg>
      <span id="cart-count"><?php
            $cart_count = WC()->cart->get_cart_contents_count();
            echo  esc_html($cart_count);
            ?></span>
      <span><?php echo esc_html('My Bag', 'skolka'); ?></span>
    </a>
  </div>
</nav>
								<?php } ?>
<?php } ?>
