<?php
/**
 * Skolka functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package skolka
 */

if ( !function_exists( 'skolka_setup' ) ) :
  /**
   * Sets up theme defaults and registers support for various WordPress features.
   *
   * Note that this function is hooked into the after_setup_theme hook, which
   * runs before the init hook. The init hook is too late for some features, such
   * as indicating support for post thumbnails.
   */
	function skolka_setup() {
		if ( !isset( $content_width ) ) {
$content_width = 900;
		}
	  /*
	   * Make theme available for translation.
	   * Translations can be filed in the /languages/ directory.
	   * If you're building a theme based on skolka, use a find and replace
	   * to change  'skolka' to the name of your theme in all the template files.
	   */
	  load_theme_textdomain( 'skolka', get_template_directory() . '/languages' );

	  // ADD MENU HTML CHARS
	  add_filter( 'walker_nav_menu_start_el', 'htmlspecialchars_decode' );



	  // THEME SUPPORTS
	  add_theme_support( 'woocommerce' );
	  add_theme_support( 'wc-product-gallery-zoom' );
	  add_theme_support( 'wc-product-gallery-lightbox' );
	  add_theme_support( 'wc-product-gallery-slider' );
	  add_theme_support( 'wp-block-styles' );
	  add_theme_support( 'responsive-embeds' );
	  add_theme_support( 'custom-logo', array( 'height' => 138, 'width' => 50, ) );
	  add_theme_support( 'align-wide' );


	  // WOOCOMMERCE FUNCTIONS
		if ( class_exists( 'woocommerce' ) ) {
		  include get_template_directory() . '/woocommerce/woo.php';
		}



	  // HIDE WP BLOCK INLINE CSS
		function smartwp_remove_wp_block_library_css() {
		 wp_dequeue_style( 'wp-block-library' );
		 wp_dequeue_style( 'wp-block-library-theme' );
		 wp_dequeue_style( 'wc-blocks-style' );
		}
	  add_action( 'wp_enqueue_scripts', 'smartwp_remove_wp_block_library_css', 100 );


	  // REGISTER BLOCK STYLE
		if ( function_exists( 'register_block_style' ) ) {
		  register_block_style(
			'core/quote',
			array(
			'name' => 'blue-quote',
			'label' => __( 'Blue Quote', 'skolka' ),
			'is_default' => true,
			'inline_style' => '.wp-block-quote.is-style-blue-quote { color: blue; }',
		  )
		  );
		}


	  // REGISTER BLOCK PATTERN
	  register_block_pattern(
	  'skolka/my-awesome-pattern',
	  array(
		'title' => __( 'Two buttons', 'skolka' ),
		'description' => _x( 'Two horizontal buttons, the left button is filled in, and the right button is outlined.', 'Block pattern description', 'skolka' ),
		'content' => "<!-- wp:buttons {\"align\":\"center\"} -->\n<div class=\"wp-block-buttons aligncenter\"><!-- wp:button {\"backgroundColor\":\"very-dark-gray\",\"borderRadius\":0} -->\n<div class=\"wp-block-button\"><a class=\"wp-block-button__link has-background has-very-dark-gray-background-color no-border-radius\">" . esc_html__( 'Button One', 'skolka' ) . "</a></div>\n<!-- /wp:button -->\n\n<!-- wp:button {\"textColor\":\"very-dark-gray\",\"borderRadius\":0,\"className\":\"is-style-outline\"} -->\n<div class=\"wp-block-button is-style-outline\"><a class=\"wp-block-button__link has-text-color has-very-dark-gray-color no-border-radius\">" . esc_html__( 'Button Two', 'skolka' ) . "</a></div>\n<!-- /wp:button --></div>\n<!-- /wp:buttons -->",
	  )
	  );


	  // ADD CUSTOM HEADER
	  add_theme_support( 'custom-header' );
	  $defaults = array(
		'default-image' => '',
		'random-default' => false,
		'width' => 0,
		'height' => 0,
		'flex-height' => false,
		'flex-width' => false,
		'default-text-color' => '',
		'header-text' => true,
		'uploads' => true,
		'wp-head-callback' => '',
		'admin-head-callback' => '',
		'admin-preview-callback' => '',
		'video' => false,
		'video-active-callback' => 'is_front_page',
	  );
	  add_theme_support( 'custom-header', $defaults );


	  // ADD CUSTOM BG FOR HEADER
	  add_theme_support( 'custom-background' );
	  $defaults = array(
		'default-image' => '',
		'default-preset' => 'default',
		'default-position-x' => 'left',
		'default-position-y' => 'top',
		'default-size' => 'auto',
		'default-repeat' => 'repeat',
		'default-attachment' => 'scroll',
		'default-color' => '',
		'wp-head-callback' => '_custom_background_cb',
		'admin-head-callback' => '',
		'admin-preview-callback' => '',
	  );
	  add_theme_support( 'custom-background', $defaults );


	  // CUSTOM STYLES
		function wpdocs_theme_add_editor_styles() {
		  add_editor_style( 'custom-style.css' );
		}
	  add_action( 'admin_init', 'wpdocs_theme_add_editor_styles' );


	  // RSS SUPPORT
	  add_theme_support( 'automatic-feed-links' );

	  // TITLE SUPPORT
	  add_theme_support( 'title-tag' );

	  // POST THUMBS
	  add_theme_support( 'post-thumbnails' );
	  add_image_size( 'skolka-post-thumb-small', 1200, 800, true, array('crop' => 1) );

		// THEME ELEMENTS IMAGE SIZES
		add_image_size( 'slide_image_size', 582, 436, true, array('crop' => 1) );
		add_image_size( 'category_image_size', 996, 300, true, array('crop' => 1) );


	/*	add_filter( 'slide_image_size', function ( $size ) {
		  return array(
			'width' => 582,
			'height' => 436,
			'crop' => 1,
		  );
		} );
*/


	  // SUPPORT VALID HTML
	  add_theme_support( 'html5', array(
	  'search-form',
	  'comment-form',
	  'comment-list',
	  'gallery',
	  'caption',
	  ) );
	}
endif;
add_action( 'after_setup_theme', 'skolka_setup' );

// CONTENT WIDTH
function skolka_content_width() {
	/**
	 * Filters the content width in pixels, based on the theme's design and stylesheet.
	 *
	 * @global int $content_width The content width in pixels.
	 *
	 * @param int $content_width The default content width, in pixels.
	 *
	 * @return int The filtered content width, in pixels.
	 *
	 * @since 1.0
	 *
	 */
  $GLOBALS[ 'content_width' ] = apply_filters( 'skolka_content_width', 640 );
}
add_action( 'after_setup_theme', 'skolka_content_width', 0 );


// WPML LANGUAGE SELECTION
function skolka_get_wpml_langs() {
	/**
	 * Filters the active languages used in the website.
	 *
	 * @since 1.0.0
	 *
	 * @param null  $langs     The active languages.
	 * @param array $args      The arguments to retrieve the active languages, like 'orderby' and 'order'.
	 *
	 * @return array The filtered active languages, ordered by ID in descending order.
	 */
  $langs = apply_filters( 'wpml_active_languages', null, 'orderby=id&order=desc' );

	if ( !empty( $langs ) ) {
		?>



<a href="#language-selector" class="select-language" data-fancybox> <svg viewBox="0 0 256 256" width="24" height="24"><rect fill="none" height="256" width="256"/><circle cx="128" cy="128" fill="none" r="96" stroke="#000" stroke-miterlimit="10" stroke-width="16"/><line fill="none" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="16" x1="37.5" x2="218.5" y1="96" y2="96"/><line fill="none" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="16" x1="37.5" x2="218.5" y1="160" y2="160"/><ellipse cx="128" cy="128" fill="none" rx="40" ry="93.4" stroke="#000" stroke-miterlimit="10" stroke-width="16"/></svg>
		<?php
$current_language = ICL_LANGUAGE_CODE;
echo  esc_html($current_language);
?> </a>
<div id="language-selector" style="display:none;">
	<h5><?php echo esc_html('Select your language preference ?'); ?></h5>

<?php
$languages = icl_get_languages('skip_missing=0&orderby=code');
if(!empty($languages)){
  foreach($languages as $l){
    $selected = ($l['language_code']==ICL_LANGUAGE_CODE) ? 'checked="checked"' : '';
    echo '<label><input type="radio" name="lang" value="'.$l['language_code'].'" '.$selected.'>'.$l['native_name'].'</label> ';
  }
}
?>

<button onclick="changeLanguage()" class="language-button"><?php echo esc_html('Change Language'); ?></button>
</div>
<script>
function changeLanguage() {
  var langInput = document.querySelector('input[name="lang"]:checked');
  var language_code = langInput.value;
  var url = '/?lang=' + encodeURIComponent(language_code);
  window.location.href = url;
}
</script>




<?php
	}
}



//  TOPBAR MENU
function skolka_get_topbar_menu() {
	if ( has_nav_menu( 'topbar' ) ) {
	  wp_nav_menu( array(
		'theme_location' => 'topbar',
		'container' => 'div',
		'container_class' => 'topbar-menu',
		'walker' => new WP_skolka_Navwalker(),
	  ) );
	}
}

// REGISTER WIDGETS
function skolka_widgets_init() {

  register_sidebar( array(
	'name' => esc_html__( 'Shop Filter', 'skolka' ),
	'id' => 'shop-filter',
	'before_widget' => '<div class="widget aside">',
	'after_widget' => '</div>',
	'before_title' => '<h6 class="widget-title">',
	'after_title' => '</h6>',
  ) );

  register_sidebar( array(
	'name' => esc_html__( 'Sidebar', 'skolka' ),
	'id' => 'sidebar-1',
	'description' => esc_html__( 'Add widgets here.', 'skolka' ),
	'before_widget' => '<div id="%1$s" class="widget %2$s">',
	'after_widget' => '</div>',
	'before_title' => '<h6 class="widget-title">',
	'after_title' => '</h6>',
  ) );

  register_sidebar( array(
	'name' => esc_html__( 'Footer 1', 'skolka' ),
	'id' => 'footer-widget-1',
	'before_widget' => '<div class="widget footer-widget">',
	'after_widget' => '</div>',
	'before_title' => '<h6 class="widget-title">',
	'after_title' => '</h6>',
  ) );

  register_sidebar( array(
	'name' => esc_html__( 'Footer 2', 'skolka' ),
	'id' => 'footer-widget-2',
	'before_widget' => '<div class="widget footer-widget">',
	'after_widget' => '</div>',
	'before_title' => '<h6 class="widget-title">',
	'after_title' => '</h6>',
  ) );

  register_sidebar( array(
	'name' => esc_html__( 'Footer 3', 'skolka' ),
	'id' => 'footer-widget-3',
	'before_widget' => '<div class="widget footer-widget">',
	'after_widget' => '</div>',
	'before_title' => '<h6 class="widget-title">',
	'after_title' => '</h6>',
  ) );

  register_sidebar( array(
	'name' => esc_html__( 'Footer 4', 'skolka' ),
	'id' => 'footer-widget-4',
	'before_widget' => '<div class="widget footer-widget">',
	'after_widget' => '</div>',
	'before_title' => '<h6 class="widget-title">',
	'after_title' => '</h6>',
  ) );

}
add_action( 'widgets_init', 'skolka_widgets_init' );

// CUSTOM TAGS
require get_template_directory() . '/inc/template-tags.php';

// CUSTOM FUNCTIONS
require get_template_directory() . '/inc/template-functions.php';

// JETPACK
if ( defined( 'JETPACK__VERSION' ) ) {
  require get_template_directory() . '/inc/jetpack.php';
}

// ENQUEUE STYLES & SCRIPTS
require_once get_template_directory() . '/inc/styles-and-scripts.php';

// REGISTER MENUS
require_once get_template_directory() . '/inc/nav-menus.php';

// CUSTOM MENU WALKER
require_once get_template_directory() . '/inc/class.custom-menu-walker.php';
require_once get_template_directory() . '/inc/class-wp-bootstrap-navwalker.php';

// TGM PLUGIN
require_once get_template_directory() . '/inc/tgm.php';

// POST EXCERPT
if ( !function_exists( 'skolka_get_the_post_excerpt' ) ) {
	function skolka_get_the_post_excerpt( $string, $limit = 70, $more = '...', $break_words = false ) {
		if ( 0 == $limit ) {
return '';
		}

		if ( mb_strlen( $string, 'utf8' ) > $limit ) {
		  $limit -= mb_strlen( $more, 'utf8' );

			if ( !$break_words ) {
				$string = preg_replace( '/\s+\S+\s*$/su', '', mb_substr( $string, 0, $limit + 1, 'utf8' ) );
			}

		  return '<p>' . mb_substr( $string, 0, $limit, 'utf8' ) . $more . '</p>';
		} else {

		  return '<p>' . $string . '</p>';
		}
	}

}

// POST DATE WITH TAGS
if ( !function_exists( 'skolka_posted_date_with_tags' ) ) {
	function skolka_posted_date_with_tags() {

		// translators: %s is the date
	  echo sprintf( esc_html__( 'Posted %s', 'skolka' ), get_the_date( 'j F Y' ) );

	  $tags = get_the_tags();
		if ( false !== $tags ) {
			foreach ( $tags as $tag ) {
				$link   = get_tag_link( $tag->term_id );
				$data[] = '<a href="' . $link . '">' . $tag->name . '</a>';
			}

		  echo ' | ' . implode( ', ', array_map( 'esc_html', $data ) );
		}
	}
}


// MOVE COMMENT FIELD
if ( !function_exists( 'skolka_move_comment_field_to_bottom' ) ) {
	function skolka_move_comment_field_to_bottom( $fields ) {
	  $comment_field = $fields[ 'comment' ];
	  unset( $fields[ 'comment' ] );
	  $fields[ 'comment' ] = $comment_field;

	  return $fields;
	}

  add_filter( 'comment_form_fields', 'skolka_move_comment_field_to_bottom' );
}


// UNSET WEBSITE FIELD
function skolka_remove_comment_fields( $fields) {
	unset($fields['url']);
	return $fields;
}
add_filter('comment_form_default_fields', 'skolka_remove_comment_fields');


// CUSTOM COMMENT
if ( !function_exists( 'skolka_bootstrap_comment' ) ) {
  /**
   * Custom callback for comment output
   *
   */
	function skolka_bootstrap_comment( $comment, $args, $depth ) {

	  $comment_link_args = array(
		'add_below' => 'comment',
		'respond_id' => 'respond',
		'reply_text' => esc_html__( 'Reply', 'skolka' ),
		'login_text' => esc_html__( 'Log in to Reply', 'skolka' ),
		'depth' => 1,
		'before' => '',
		'after' => '',
		'max_depth' => 5
	  );
		?>
<?php if ( '1' == $comment->comment_approved ) : ?>
<li class="comment">
  <figure class="comment-avatar"><?php echo get_avatar( $comment ); ?></figure>
  <div class="comment-content">
	<h4>
			  <?php comment_author_link(); ?>
	</h4>
	<p>
			  <?php comment_text(); ?>
	</p>
	<small>
			<?php comment_date(); ?>
	</small>
			<?php
				  comment_reply_link( $comment_link_args );
			?>
  </div>
</li>
			  <?php
endif;
	}

}

// SLUG
if ( !function_exists( 'skolka_get_option' ) ) {

	function skolka_get_option( $slug ) {
		if ( function_exists( 'get_field' ) ) {
		  return get_field( $slug, 'option' );
		}

	  return false;
	}
}

if ( !function_exists( 'skolka_get_field' ) ) {

	function skolka_get_field( $slug, $post_id = 0 ) {
		if ( function_exists( 'get_field' ) ) {
		  return get_field( $slug, $post_id );
		}

	  return false;
	}
}


// PAGINATION
if ( !function_exists( 'skolka_pagination' ) ) {
  function skolka_pagination( $pages = '' ) {
    global $wp_query, $wp_rewrite;
    $wp_query->query_vars[ 'paged' ] > 1 ? $current = $wp_query->query_vars[ 'paged' ] : $current = 1;
    if ( $pages == '' ) {
      global $wp_query;
      $pages = $wp_query->max_num_pages;
      if ( !$pages ) {
        $pages = 1;
      }
    }
    $pagination = array(
      'base' => str_replace( 999999999, '%#%', get_pagenum_link( 999999999 ) ),
      'format' => '',
      'current' => max( 1, get_query_var( 'paged' ) ),
      'total' => $pages,
      'prev_text' => wp_specialchars_decode( esc_html__( '<svg width="24" height="24" viewBox="0 0 24 24" fill="var(--color-dark)">
					<path d="M14.7105 17.2998C14.3205 17.6898 13.6905 17.6898 13.3005 17.2998L8.71047 12.7098C8.32047 12.3198 8.32047 11.6898 8.71047 11.2998L13.3005 6.70979C13.6905 6.31979 14.3205 6.31979 14.7105 6.70979C15.1005 7.09979 15.1005 7.72978 14.7105 8.11979L10.8305 12.0098L14.7105 15.8898C15.1005 16.2798 15.0905 16.9198 14.7105 17.2998Z"></path>
				</svg>', 'skolka' ), ENT_QUOTES ),
      'next_text' => wp_specialchars_decode( esc_html__( '<svg width="24" height="24" viewBox="0 0 24 24" fill="var(--color-dark)">
				<path d="M8.70995 6.70998C9.09995 6.31998 9.72995 6.31998 10.1199 6.70998L14.7099 11.3C15.0999 11.69 15.0999 12.32 14.7099 12.71L10.1199 17.3C9.72995 17.69 9.09995 17.69 8.70995 17.3C8.31995 16.91 8.31995 16.28 8.70995 15.89L12.5899 12L8.70995 8.11998C8.31995 7.72998 8.32995 7.08998 8.70995 6.70998Z"></path>
			</svg>', 'skolka' ), ENT_QUOTES ),
      'type' => 'list',
      'end_size' => 3,
      'mid_size' => 3
    );
    $return = paginate_links( $pagination );
    echo str_replace( "<ul class='page-numbers'>", '<ul class="pagination">', $return );
  }
}

// POST THUMB URL
if ( !function_exists( 'skolka_get_post_thumbnail_url' ) ) {

	function skolka_get_post_thumbnail_url() {
		if ( get_the_post_thumbnail_url() ) {
		  return get_the_post_thumbnail_url( get_the_ID(), 'skolka-post-thumb-small' );
		}

	  return false;
	}
}


// PAGE TITLES
if ( !function_exists( 'skolka_get_page_title' ) ) {

	function skolka_get_page_title() {
	  $title = '';

		if ( is_category() ) {
		  $title = single_cat_title( '', false );
		} elseif ( is_tag() ) {
		  $title = single_term_title( '', false ) . esc_html__( 'Tag', 'skolka' );
		} elseif ( is_date() ) {
		  $title = get_the_time( 'F Y' );
		} elseif ( is_author() ) {
		  $title = esc_html__( 'Author:', 'skolka' ) . ' ' . esc_html( get_the_author() );
		} elseif ( is_search() ) {
		  $title = ( skolka_get_option( 'search_page_title' ) ) ? esc_html( skolka_get_option( 'search_page_title' ) ) : esc_html__( 'Search Result', 'skolka' );
		} elseif ( is_404() ) {
		  $title = ( skolka_get_option( 'error_page_title' ) ) ? esc_html( skolka_get_option( 'error_page_title' ) ) : esc_html__( 'Page Not Found', 'skolka' );
		} elseif ( is_archive() ) {
		  $title = ( skolka_get_option( 'display_page_header_archive' ) ) ? esc_html( skolka_get_option( 'display_page_header_archive' ) ) : esc_html__( 'Archive', 'skolka' );
		} elseif ( is_home() || is_front_page() ) {
			if ( is_home() && !is_front_page() ) {
			  $title = esc_html( single_post_title( '', false ) );
			} else {
			  $title = ( skolka_get_option( 'archive_blog_title' ) ) ? esc_html( skolka_get_option( 'archive_blog_title' ) ) : esc_html__( 'Blog', 'skolka' );
			}
		} else {
		  global $post;
			if ( !empty( $post ) ) {
				if ( 'post' == $post->post_type ) {
					$title = ( skolka_get_option( 'archive_blog_title' ) ) ? esc_html( skolka_get_option( 'archive_blog_title' ) ) : esc_html__( 'Blog', 'skolka' );
				} else {
				  $id    = $post->ID;
				  $title = esc_html( get_the_title( $id ) );
				}
			} else {
			  $title = esc_html__( 'Post not found.', 'skolka' );
			}
		}

	  return $title;
	}

}

// PAGE HEADER
if ( !function_exists( 'skolka_render_page_header' ) ) {

	function skolka_render_page_header( $type ) {

		 $show_header = false;
		 $header_title = '';

		 switch ( $type ) {
		 	case 'page':
		 		$show_header = false;

				if ( skolka_get_option( 'show_page_header' ) ) {
					$show_header     = true;
				}
				$header_title    = get_the_title();

				break;
			case 'single':
				$show_header = false;

				if ( skolka_get_option( 'display_page_header_single' ) ) {
					$show_header     = true;
				}
			  $header_title    = get_the_title();

				break;

			case 'archive':
			$show_header = false;

			if ( skolka_get_option( 'display_page_header_archive' ) ) {
				$show_header     = true;
			}
			$header_title    =  skolka_get_option( 'archive_title' )  ? esc_html( skolka_get_option( 'archive_title' ) ) : esc_html__( 'Archive', 'skolka' );

			break;

			case 'frontpage':
				$show_header = false;

				if ( skolka_get_option( 'display_page_header_archive' ) ) {
					$show_header     = true;
				}
			  $header_title    = skolka_get_option( 'archive_title' )  ? esc_html( skolka_get_option( 'archive_title' ) ) : esc_html__( 'Archive', 'skolka' );

				break;

				case 'product':
					$show_header = false;

					if ( skolka_get_option( 'display_page_header_product_archive' ) ) {

					$show_header     = true;
				  $header_title    = single_term_title();
					}

					break;


			case 'search':
				$show_header = false;

				if ( skolka_get_option( 'display_page_header_product_search' ) ) {
					$show_header     = true;
				}
			  $header_title    = skolka_get_page_title();


				break;
		}


		if ( $show_header ) {


			?>
<header class="page-header">
  <div class="container">
		<?php
			if ( class_exists( 'WooCommerce' ) ) {
				?>
			  <?php woocommerce_breadcrumb(); ?>
			  <?php } ?>
	<h2><?php echo esc_html( $header_title ); ?></h2>
  </div>
</header>

<?php
		}
	}
}

// POST TAGS
if ( !function_exists( 'skolka_post_tags' ) ) {

	function skolka_post_tags() {

	  $tags = get_the_tags();
		if ( false !== $tags ) {
			?>
<ul class="post-tags">
	  <?php
			foreach ( $tags as $tag ) {
				  $link = get_tag_link( $tag->term_id );
				?>
  <li><a href="<?php echo esc_url( $link ); ?>"><?php echo esc_html( $tag->name ); ?></a></li>
					  <?php } ?>
</ul>
<?php
		}
	}
}


// BODY OPEN
if ( !function_exists( 'wp_body_open' ) ) {
	function wp_body_open() {
		/**
		 * Fires after the opening <body> tag.
		 *
		 * @since 5.2.0
		 */
	  do_action( 'wp_body_open' );
	}
}





// DEMO IMPORT
function skolka_import_files() {
  return array(
	array(
	  'import_file_name' => esc_html__( 'Themezinho Demo Import', 'skolka' ),
	  'import_file_url' => 'http://skolka.themezinho.net/import/skolka-demo-data.xml',
	  'import_widget_file_url' => 'http://skolka.themezinho.net/import/skolka-widgets.wie',
	  'import_notice' => esc_html__( 'After you import this demo, you will have to setup the theme option separately.', 'skolka' ),
	  'preview_url' => 'https://skolka.themezinho.net',
	),
  );

}
add_filter( 'pt-ocdi/import_files', 'skolka_import_files' );


// SET MENU AFTER DEMO IMPORT
function skolka_after_import_setup() {
	$topbar = get_term_by( 'name', 'Topbar Menu', 'nav_menu' );
	$categories_menu = get_term_by( 'name', 'Categories Menu', 'nav_menu' );
	$main_menu = get_term_by( 'name', 'Main Menu', 'nav_menu' );
	set_theme_mod( 'nav_menu_locations', array(
		'topbar' => $topbar->term_id,
		'categories' => $categories_menu->term_id,
		'menu' => $main_menu->term_id,
	) );

  $front_page_id = get_page_by_title( 'Home' );
  $blog_page_id  = get_page_by_title( 'Blog' );

  update_option( 'show_on_front', 'page' );
  update_option( 'page_on_front', $front_page_id->ID );
  update_option( 'page_for_posts', $blog_page_id->ID );

	if ( function_exists( 'skolka_after_import' ) ) {
	  skolka_after_import();
	}
}


add_action( 'pt-ocdi/after_import', 'skolka_after_import_setup' );
add_filter( 'pt-ocdi/regenerate_thumbnails_in_content_import', '__return_false' );
add_action( 'pt-ocdi/disable_pt_branding', '__return_true' );
