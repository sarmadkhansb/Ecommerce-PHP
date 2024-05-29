<?php

function skolka_theme_fonts() {
  if ( skolka_get_option( 'enable_typography' ) ) {
  $enable_typography = get_field('enable_typography', 'option');
  $selected_font = get_field('select_font', 'option');

  if ($enable_typography) {
    wp_enqueue_style('google-fonts', 'https://fonts.googleapis.com/css?family=' . $selected_font . ':wght@400;600;800&display=swap');
  }
  }
}
add_action('wp_enqueue_scripts', 'skolka_theme_fonts');

if ( !function_exists( 'skolka_enqueue_styles_and_scripts' ) ) {
  /**
   * This function enqueues the required css and js files.
   *
   * @return void
   */
	function skolka_enqueue_styles_and_scripts() {
	  /**
	   * Enqueue css files.
	   */
    wp_enqueue_style( 'fontawesome', get_template_directory_uri() . '/css/fontawesome.min.css', array(), '6.2.1' );
	  wp_enqueue_style( 'fancybox', get_template_directory_uri() . '/css/fancybox.min.css', array(), '4.0.31' );
	  wp_enqueue_style( 'swiper', get_template_directory_uri() . '/css/swiper.min.css', array(), '8.4.5' );
	  wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/css/bootstrap.min.css', array(), '5.3.0' );
	  wp_enqueue_style( 'skolka-main-style', get_template_directory_uri() . '/css/styles.css', array(), '1.0' );
	  wp_enqueue_style( 'skolka-stylesheet', get_stylesheet_uri(), array(), '1.0' );
	  wp_add_inline_style( 'skolka-stylesheet', skolka_dynamic_css(), array(), '1.0' );

	  /**
	   * Enqueue javascript files.
	   */

    wp_enqueue_script( 'comments', get_template_directory_uri() . '/js/comments.js', array(), false, false );
	  wp_enqueue_script( 'swiper', get_template_directory_uri() . '/js/swiper.min.js', array( 'jquery' ), '8.4.5', false );
    wp_enqueue_script( 'swiper-carousel', get_template_directory_uri() . '/js/swiper-carousel.js', array( 'jquery' ), false, true, '8.4.5' );
		wp_enqueue_script( 'fancybox', get_template_directory_uri() . '/js/fancybox.umd.js', array('jquery'), '4.0.31', false );
		wp_enqueue_script( 'skolka-scripts', get_template_directory_uri() . '/js/scripts.js', array('jquery'), '1.0', false );

    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
    wp_enqueue_script( 'comment-reply' );
    }


    $comment_data = array(
    'name' => esc_html__( 'Name is required', 'skolka' ),
    'email' => esc_html__( 'Email is required', 'skolka' ),
    'comment' => esc_html__( 'Comment is required', 'skolka' ),

    );


    wp_localize_script( 'comments', 'comment_data', $comment_data );
	  wp_localize_script( 'ajax-search', 'skolka_ajax_search', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
	}

  add_action( 'wp_enqueue_scripts', 'skolka_enqueue_styles_and_scripts', 10 );
}

if ( !function_exists( 'skolka_dynamic_css' ) ) {
	function skolka_dynamic_css() {

	  $styles = '';

    if ( skolka_get_option( 'footer_bg_color' ) ) {
      $footer_bg_color = skolka_get_option('footer_bg_color');
      $styles .= ".footer, .footer-icon-bar { background-color: {$footer_bg_color}; }";
    }

    if ( skolka_get_option( 'footer_bg_image' ) ) {
      $footer_bg_image = skolka_get_option('footer_bg_image');
      $styles .= ".footer { background-image:url('{$footer_bg_image}'); }";
    }

    if ( skolka_get_option( 'footer_content_color' ) ) {
      $footer_content_color = skolka_get_option('footer_bg_color');
      $styles .= ".footer, .footer-icon-bar, .footer a, .footer-icon-bar a, .footer .calendar_wrap caption { color:{$footer_content_color}; }";
      $styles .= ".footer .widget .wp-block-social-links .wp-social-link a svg{ fill:{$footer_content_color}; }";
      $styles .= ".footer .widget ul li.recentcomments:before, .footer .widget_recent_entries ul li:before{ background-color:{$footer_content_color}; }";
    }




    if ( skolka_get_option( 'site_logo' ) ) {
      $site_logo = skolka_get_option('site_logo');
      $styles .= "
      .navbar .logo a.desktop-logo img { height: {$site_logo['desktop_logo_height']}px; }
      .navbar .logo a.mobile-logo img { height: {$site_logo['mobile_logo_height']}px; }
      ";
    }


		if ( skolka_get_option( 'enable_typography' ) ) {
			$font_size = skolka_get_option('body_font_size');
  		$line_height = skolka_get_option('body_font_line_heigt');
  		$letter_spacing = skolka_get_option('body_font_letter_spacing');
			$selected_font = skolka_get_option( 'select_font' );
			$selected_font_name = str_replace( '+', ' ', $selected_font );

		  $styles .= "body { font-family: '{$selected_font_name}', sans-serif; font-size: {$font_size}px; line-height: {$line_height}; letter-spacing: {$letter_spacing}px; }";
		}


		if ( skolka_get_option( 'enable_dynamic_color' ) ) {

		  $theme_color1 = ( skolka_get_option( 'theme_color1' ) ) ? skolka_get_option( 'theme_color1' ) : '#FF6900';
		  $theme_color2 = ( skolka_get_option( 'theme_color2' ) ) ? skolka_get_option( 'theme_color2' ) : '#6328DF';
		  $theme_color3 = ( skolka_get_option( 'theme_color3' ) ) ? skolka_get_option( 'theme_color3' ) : '#FF0000';
		  $theme_color4 = ( skolka_get_option( 'theme_color4' ) ) ? skolka_get_option( 'theme_color4' ) : '#FF0000';
		  $theme_color5 = ( skolka_get_option( 'theme_color5' ) ) ? skolka_get_option( 'theme_color5' ) : '#FF0000';
		  $theme_color6 = ( skolka_get_option( 'theme_color6' ) ) ? skolka_get_option( 'theme_color6' ) : '#FF0000';
		  $dark_color   = ( skolka_get_option( 'dark_color' ) ) ? skolka_get_option( 'dark_color' ) : '#000000';
		  $white_text   = ( skolka_get_option( 'white_text' ) ) ? skolka_get_option( 'white_text' ) : '#FFFFFF';

		  $styles .= "
				:root {
					--color-main: {$theme_color1};
					--color-second: {$theme_color2};
					--color-highlight: {$theme_color3};
					--color-gray: {$theme_color4};
					--color-gray-light: {$theme_color5};
					--color-gray-lighter: {$theme_color6};
					--color-dark: {$dark_color};
					--color-white-text: {$white_text};
          }
        ";
		}

	  return $styles;
	}
}




add_action( 'init', 'skolka_dynamic_css' );
add_action(
  'after_setup_theme',
  function () {
		add_theme_support( 'html5', [ 'script', 'style' ] );
	}
);
