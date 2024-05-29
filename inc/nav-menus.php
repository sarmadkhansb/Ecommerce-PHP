<?php

if ( !function_exists( 'skolka_register_nav_menus' ) ) {
  /**
   * Register required nav menus
   */
	function skolka_register_nav_menus() {

	  register_nav_menus( array(
		'topbar' => esc_html__( 'Topbar Menu', 'skolka' ),
		'categories' => esc_html__( 'Categories Menu', 'skolka' ),
		'menu' => esc_html__( 'Main Menu', 'skolka' ),
	  ) );


	}
  add_action( 'after_setup_theme', 'skolka_register_nav_menus' );
}
