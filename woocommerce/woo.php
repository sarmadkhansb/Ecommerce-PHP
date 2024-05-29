<?php



// VIDEO THUMB
add_action( 'woocommerce_product_thumbnails', 'skolka_product_video_thumbnails', 0 );
function skolka_product_video_thumbnails() {
    global $product;

    if ( ! is_a( $product, 'WC_Product' ) ) {
        return;
    }

    $attachment_ids = $product->get_gallery_image_ids();

    if ( ! $attachment_ids ) {
        return;
    }

    $html = '';
    $video_type = get_field('product_video');

    if ( $video_type === 'embed' ) {
        $video_src = get_field('embed');
        $youtube_poster = get_field('youtube_poster');

        if ( preg_match('/youtube\.com\/watch\?v=([^\&\?\/]+)/', $video_src, $matches) || preg_match('/youtube\.com\/embed\/([^\&\?\/]+)/', $video_src, $matches) ) {
            $youtube_id = $matches[1];
            $video = '<div class="youtube-wrapper"><iframe src="https://www.youtube.com/embed/' . $youtube_id . '" frameborder="0" allowfullscreen></iframe></div>';
            $html .= '<div class="woocommerce-product-gallery__image product-video" data-thumb=" '. $youtube_poster . ' " >';
            $html .= $video;
            $html .= '</div>';
        } else {
            $video = '';
        }
    } elseif ( $video_type === 'upload' ) {
        $video_src = get_field('upload');
        $upload_poster = get_field('upload_poster');
        $video = '<video loop muted playinline autoplay><source src="' . esc_url( $video_src ) . '"></video>';
        $html .= '<div class="woocommerce-product-gallery__image product-video" data-thumb=" '. $upload_poster . ' " >';
        $html .= $video;
        $html .= '</div>';
    } else {
        $video = '';
    }

    // Add the video to the beginning of the gallery
    array_unshift( $attachment_ids, $product->get_image_id() );
    $product->set_gallery_image_ids( $attachment_ids );

    echo $html;
}





// WOO ADMIN TABLE CSS
function custom_admin_css() {
  echo '<style>
    table.wp-list-table .column-product_cat, table.wp-list-table .column-product_tag{ width:auto !important;}
	.widefat td, .widefat th{ width:auto !important;}
  </style>';
}
add_action('admin_head', 'custom_admin_css');



// UPDATE PHONE COUNTRY CODE
add_action( 'wp_ajax_nopriv_append_country_prefix_in_billing_phone', 'country_prefix_in_billing_phone' );
add_action( 'wp_ajax_append_country_prefix_in_billing_phone', 'country_prefix_in_billing_phone' );
function country_prefix_in_billing_phone() {
    $calling_code = '';
    $country_code = isset( $_POST['country_code'] ) ? $_POST['country_code'] : '';
    if( $country_code ){
        $calling_code = WC()->countries->get_country_calling_code( $country_code );
        $calling_code = is_array( $calling_code ) ? $calling_code[0] : $calling_code;

    }
    echo esc_attr($calling_code);
    die();
}



// REMOVE BREADCRUMB
remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );





// CUSTOM QTY INPUT
function woocommerce_quantity_input( $args = array(), $product = null, $echo = true ) {


    $defaults = array(
        'input_id'       => uniqid( 'quantity_' ),
        'input_class'    => 'input-text qty text',
        'input_name'     => 'quantity',
        'input_value'    => '1',
        'product_name'  => $product ? $product->get_name() : '',
        'product_id'    => $product ? $product->get_id() : 0,
        'max_value'      => apply_filters( 'woocommerce_quantity_input_max', '', $product ),
        'min_value'      => apply_filters( 'woocommerce_quantity_input_min', '1', $product ),
        'step'           => apply_filters( 'woocommerce_quantity_input_step', '1', $product ),
        'placeholder'    => '',
        'inputmode'      => apply_filters( 'woocommerce_quantity_input_inputmode', 'numeric', $product ),
        'wrapper_class' => 'quantity',
        'custom_attributes' => array(),

    );

    $args = wp_parse_args( $args, $defaults );

    ob_start();

    do_action( 'woocommerce_before_quantity_input_field' );

    echo '<div class="' . esc_attr( $args['wrapper_class'] ) . '">';
    echo '<input type="text" id="' . esc_attr( $args['input_id'] ) . '" class="' . esc_attr( $args['input_class'] ) . '" name="' . esc_attr( $args['input_name'] ) . '" value="' . esc_attr( $args['input_value'] ) . '" min="' . esc_attr( $args['min_value'] ) . '" max="999" step="' . esc_attr( $args['step'] ) . '" inputmode="' . esc_attr( $args['inputmode'] ) . '" ' . implode( ' ', $args['custom_attributes'] ) . ' />';
    echo '<div class="qty-controls">';
    echo '<input type="button" value="+" class="plus">';
    echo '<input type="button" value="-" class="minus">';
    echo '</div>';
    echo '</div>';

    do_action( 'woocommerce_after_quantity_input_field' );

    if ( $echo ) {
        echo ob_get_clean();
    } else {
        return ob_get_clean();
    }
}






// REDIRECT LOGIN
function wpse_131562_redirect() {
    if (
        ! is_user_logged_in()
        && (is_cart() || is_checkout())
    ) {
        // feel free to customize the following line to suit your needs
         wp_redirect( home_url( '/my-account/' ) );
        exit;
    }
}
add_action('template_redirect', 'wpse_131562_redirect');





// PRINT SIMPLE INVOICE
add_action( 'woocommerce_thankyou', 'add_print_button_to_thankyou', 20, 1 );
function add_print_button_to_thankyou( $order_id ) { ?>
  <div class="print-invoice-button">
    <button type="button" onclick="window.print()"><i class="fa-solid fa-print"></i> <?php echo esc_html('Print Invoice'); ?></button>
  </div>
<style>
@media print {
  .woocommerce-order{ width: 100%;}
   .woocommerce-notice.woocommerce-notice--success.woocommerce-thankyou-order-received{display: none;}
   .navbar-simple .container{justify-content: center;}
   .navbar-simple .container .secure-shopping{display: none;}
   .print-invoice-button{display: none;}
   .woocommerce-customer-details{ width: 100%; border:none;}
   .woocommerce-order-details{ width: 100%; border: none;}
   .simple-footer{display: none;}
}
</style>
<?php }



// CUSTOM THANK YOU TEXT
add_filter( 'woocommerce_thankyou_order_received_text', 'custom_woocommerce_thankyou_order_received_text', 10, 2 );

function custom_woocommerce_thankyou_order_received_text( $thankyou_text, $order ) {
    $thankyou_text = '<span>Thank you.</span><br>Your order has been received.';
    return $thankyou_text;
}




// LOOP THUMB WRAPPER START
add_action( 'woocommerce_before_shop_loop_item_title', 'skolka_product_loop_thumbs_wrapper_starts', 0 );
function skolka_product_loop_thumbs_wrapper_starts() { ?>
<div class="product-thumbs">
<?php
}


// LOOP THUMB WRAPPER START
add_action( 'woocommerce_before_shop_loop_item_title', 'skolka_product_loop_thumbs_wrapper_ends', 99 );
function skolka_product_loop_thumbs_wrapper_ends() {
	?>
</div>
<?php
}


// LOOP AVAILABLE COLORS
function skolka_product_available_colors() {
  global $product;

	if ( ! $product->is_type( 'variable' ) ) {
		return;
	}

	$variations = $product->get_available_variations();
	$product_id = $product->get_id();

	if ( empty( $variations ) ) {
	  return;
	}

	$colours = [];

	foreach ( $variations as $variation ) {
		if ( ! empty( $variation['attributes']['attribute_pa_color'] ) ) {
		if ( ! $variation['is_in_stock'] && ! $variation['backorders_allowed']  ) {
			continue;
		}

		$link      = add_query_arg( 'attribute_pa_color', $variation['attributes']['attribute_pa_color'], get_permalink( $product_id ) );
		$term      = get_term_by( 'slug', $variation['attributes']['attribute_pa_color'], 'pa_color' );
		$term_meta = get_term_meta( $term->term_id, false );

		if (isset($term_meta['pa_color_swatches_id_type'])) {
			$type = $term_meta['pa_color_swatches_id_type'];
			if (isset($type[0])) {
				if ( 'color' === $type[0] ) {
					if (isset($term_meta['pa_color_swatches_id_color'])) {
						$hexes = $term_meta['pa_color_swatches_id_color'];
						foreach ( $hexes as $hex ) {
							$colours[] = $hex;
						}
					}
				}
			}
		}
	}
}

if ( empty( $colours ) ) {
	return;
}

$colours = array_unique( $colours );
?>

<ul class="available-color-options">
	<?php
	foreach ( $colours as $colour ) {
		echo '<li style="background-color: ' . esc_attr( $colour ) . ';"></li>';
	}
	?>
</ul>

	<?php
}
add_action( 'woocommerce_before_shop_loop_item_title', 'skolka_product_available_colors', 5 );





// LOOP AVAILABLE SIZES
function skolka_product_available_sizes() {
  $product_id    = get_the_ID();
$attribute_name  = 'pa_size';
$attribute_terms = get_the_terms( $product_id, $attribute_name );
	if ( !empty( $attribute_terms ) ) {
		echo '<ul class="available-size-options">';
		foreach ( $attribute_terms as $term ) {
			echo '<li>' . esc_html($term->name) . '</li>';
		}
		echo '</ul>';
	}
}
add_action( 'woocommerce_before_shop_loop_item_title', 'skolka_product_available_sizes', 5 );





// AJAX LOOP SORTING
add_action( 'wp_ajax_nopriv_sort_products', 'sort_products' );
add_action( 'wp_ajax_sort_products', 'sort_products' );
function sort_products() {

	if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
		if ( isset($_REQUEST['orderby']) ) {
			$orderby_value = sanitize_text_field( $_REQUEST['orderby'] );
			$orderby       = $orderby_value;
		}

	  $args = array(
		'post_type' => 'product',
		'post_status' => 'publish',
		'posts_per_page' => -1,
	  );

		switch ($orderby) {
			case 'price':
			  $args['orderby']  = 'meta_value_num';
			  $args['meta_key'] = '_price';
			  $args['order']    = 'asc';
				break;
			case 'price-desc':
			  $args['orderby']  = 'meta_value_num';
			  $args['meta_key'] = '_price';
			  $args['order']    = 'desc';
				break;
			case 'date':
			  $args['orderby'] = 'date';
			  $args['order']   = 'desc';
				break;
			case 'title':
			  $args['orderby'] = 'title';
			  $args['order']   = 'asc';
				break;
			default:
			  $args['orderby'] = 'menu_order';
			  $args['order']   = 'asc';
				break;
		}

	  $query = new WP_Query( $args );
		if ( $query->have_posts() ) {
		  ob_start();
			while ( $query->have_posts() ) {
			  $query->the_post();
			  wc_get_template_part( 'content', 'product' );
			}
		  wp_reset_postdata();
		  $products = ob_get_clean();
		  wp_send_json_success( $products );
		} else {
		  wp_send_json_error( 'No products found' );
		}
	} else {
	  wp_send_json_error( 'Invalid request' );
	}
  exit;
}



// REMOVE DEFAULT SORTING
add_action( 'init', 'remove_woocommerce_shop_sort_by_select' );
function remove_woocommerce_shop_sort_by_select() {
  remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
  remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
}


// CUSTOM SORT OPTIONS
add_filter( 'woocommerce_catalog_orderby', 'skolka_custom_woocommerce_shop_sort_by_select' );
function skolka_custom_woocommerce_shop_sort_by_select( $sortby ) {
	unset($sortby['rating']);
	$sortby['menu_order'] = 'Sort By';
	$sortby['popularity'] = 'Popular';
	$sortby['date']       = 'Newest';
	$sortby['price']      = 'Lowest Price';
	$sortby['price-desc'] = 'Highest Price';
	$sortby['onsale']     = 'Sale';
	return $sortby;
}






add_action( 'woocommerce_before_shop_loop', 'skolka_custom_ajax_filter', 15 );
function skolka_custom_ajax_filter() {
  if ( skolka_get_option('display_product_filter') ) {
	?>

  <form id="skolka-product-filter" class="product-filter">
	<div class="filter-inner">
  <?php
  $attributes = get_object_taxonomies('product', 'objects');
	if (!empty($attributes)) {
		foreach ($attributes as $attribute) {
			if (in_array($attribute->name, array('gender', 'pa_color', 'pa_size', 'pa_sizes', 'brand', 'material', 'style'))) {
				$options = get_terms(array(
				 'taxonomy' => $attribute->name,
				 'hide_empty' => false,
				));
				 echo '<div class="attribute">';
				 echo '<select id="' . esc_attr( $attribute->name ) . '" class="attribute-select" name="' . esc_attr( $attribute->name ) . '">';
				 echo '<option value="">' . esc_html( $attribute->label ) . '</option>';
				foreach ($options as $option) {
					$selected = '';
					if (isset($_GET[$attribute->name]) && $_GET[$attribute->name] == $option->slug) {
						$selected = 'selected';
					}
					echo '<option value="' . esc_attr( $option->slug ) . '" ' . esc_attr( $selected ) . '>' . esc_html( $option->name ) . '</option>';
				}
				 echo '</select>';
				 echo '</div>';
			}
		}
	}
	?>
	<div class="saving-percentage">
  <select id="discount-percentage" class="attribute-select" name="discount-percentage">
    <?php
    $options = array(
      '' => esc_html('Discount'),
      '1, 20' => esc_html('Up to 20%'),
      '20, 30' => esc_html('20% - 30%'),
      '30, 40' => esc_html('30% - 40%'),
      '40, 50' => esc_html('40% - 50%'),
      '50, 99' => esc_html('50% More'),
    );

    foreach ($options as $value => $label) {
      $selected = '';
      if (isset($_GET['discount-percentage']) && $_GET['discount-percentage'] === $value) {
        $selected = 'selected';
      }
      echo '<option value="' . esc_attr($value) . '" ' . $selected . '>' . $label . '</option>';
    }
    ?>
  </select>
</div>

<input type="hidden" name="current_category" value="<?php echo get_queried_object_id(); ?>">
<input type="hidden" name="custom_page" value="<?php echo is_paged() ? get_query_var('paged') : 1; ?>">


  </div>
  </form>

<div class="skolka-result-count">
  <?php
  global $wp_query;
  $total = $wp_query->found_posts;
	if ( $total > 1 ) {
	echo '<span>' . esc_html( $total ) . '</span> ' . esc_html( 'products found' );
	} else {
	echo '<span>' . esc_html( $total ) . '</span> ' . esc_html( 'product found' );
	}
	?>
</div>




<?php
} }




// Add filter action to admin-ajax.php
add_action('wp_ajax_filter_products', 'filter_products');
add_action('wp_ajax_nopriv_filter_products', 'filter_products');

function filter_products() {
    $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $perPage = isset($_POST['per_page']) ? intval($_POST['per_page']) : 3;
		$category_id = isset($_POST['current_category']) ? intval($_POST['current_category']) : 0;


    $args = array(
        'post_type' => 'product',
        //'posts_per_page' => 1,
        'paged' => $page,
        'tax_query' => array(
            'relation' => 'AND'
        ),
        'meta_query' => array(
            'relation' => 'AND'
        )
    );



    $attributes = array('gender', 'pa_color', 'pa_size', 'pa_sizes', 'brand', 'material', 'style');

    foreach ($attributes as $attribute) {
        if (!empty($_POST[$attribute])) {
            $args['tax_query'][] = array(
                'taxonomy' => $attribute,
                'field' => 'slug',
                'terms' => $_POST[$attribute]
            );
        }
    }

    if (!empty($_POST['discount-percentage'])) {
        $range = explode(',', $_POST['discount-percentage']);
        $args['meta_query'][] = array(
            'key' => 'discount_percentage',
            'value' => $range,
            'type' => 'numeric',
            'compare' => 'BETWEEN'
        );
    }


		if (isset($_POST['current_category']) && !empty($_POST['current_category'])) {
    $current_category = sanitize_text_field($_POST['current_category']);
    $args['tax_query'][] = array(
        'taxonomy' => 'product_cat',
        'field' => 'term_id',
        'terms' => $current_category,
    );
	}

    $query = new WP_Query($args);
		$products = '';
		$total_posts = $query->found_posts;
    ob_start();
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $discount_percentage = get_post_meta(get_the_ID(), 'discount_percentage', true);
            $query->the_post();
            wc_get_template_part('content', 'product');
        }
    } else {
        echo '<p class="woocommerce-info woocommerce-no-products-found">No products were found matching your selection.</p>';
    }
    $products = ob_get_clean();

			wp_reset_postdata();


			$total = $query->max_num_pages;
			$next_text = '<svg width="24" height="24" viewBox="0 0 24 24" fill="var(--color-dark)">
				<path d="M8.70995 6.70998C9.09995 6.31998 9.72995 6.31998 10.1199 6.70998L14.7099 11.3C15.0999 11.69 15.0999 12.32 14.7099 12.71L10.1199 17.3C9.72995 17.69 9.09995 17.69 8.70995 17.3C8.31995 16.91 8.31995 16.28 8.70995 15.89L12.5899 12L8.70995 8.11998C8.31995 7.72998 8.32995 7.08998 8.70995 6.70998Z"></path>
			</svg>';
			$prev_text = '<svg width="24" height="24" viewBox="0 0 24 24" fill="var(--color-dark)">
					<path d="M14.7105 17.2998C14.3205 17.6898 13.6905 17.6898 13.3005 17.2998L8.71047 12.7098C8.32047 12.3198 8.32047 11.6898 8.71047 11.2998L13.3005 6.70979C13.6905 6.31979 14.3205 6.31979 14.7105 6.70979C15.1005 7.09979 15.1005 7.72978 14.7105 8.11979L10.8305 12.0098L14.7105 15.8898C15.1005 16.2798 15.0905 16.9198 14.7105 17.2998Z"></path>
				</svg>';
			$pagination = paginate_links(array(
	    'base' => add_query_arg('page', '%#%'),
	    'format' => '',
	    'prev_text' => $prev_text,
	    'next_text' => $next_text,
			'prev_next' => true,
	    'type' => 'array',
	    'total' => $total,
	    'current' => $page,
	));

	// Remove all the page numbers except for the previous and next buttons
if (!empty($pagination)) {
    $prev_link = $pagination[0];
    if ($page == 1) {
        $prev_link = '<span class="prev page-numbers">' . $prev_text . '</span>';
    }

		$next_link = $pagination[count($pagination) - 1];
		if ($page == $total) {
			$next_link = '<span class="next page-numbers">' . $next_text . '</span>';
		}


	// Add current page of total pages information in the pagination links
	$pagination = ' '.$prev_link.'<div class=\'result\'> <div class=\'current\'>Page '.$page.'</div> <div class=\'total\'>of '.$total.'</div></div> '.$next_link.' ';

}

		wp_reset_postdata();
		$result = array(
		    'products' => $products,
		    'pagination' => $pagination,
		    'total_products' => $total_posts,
		);
		wp_send_json($result);
		die();
}





function woocommerce_pagination() {
  global $wp_query;
  $big = 999999999;
  if (isset($_GET['total_pages'])) {
    $total = $_GET['total_pages'];
  } else {
    $total = $wp_query->max_num_pages;
  }
  $page = max(1, get_query_var('paged'));

	//$page = isset( $page ) ? $page : wc_get_loop_prop( 'current_page' );
	//$total   = isset( $total ) ? $total : wc_get_loop_prop( 'total_pages' );

  $next_text = '<svg width="24" height="24" viewBox="0 0 24 24" fill="var(--color-dark)">
    <path d="M8.70995 6.70998C9.09995 6.31998 9.72995 6.31998 10.1199 6.70998L14.7099 11.3C15.0999 11.69 15.0999 12.32 14.7099 12.71L10.1199 17.3C9.72995 17.69 9.09995 17.69 8.70995 17.3C8.31995 16.91 8.31995 16.28 8.70995 15.89L12.5899 12L8.70995 8.11998C8.31995 7.72998 8.32995 7.08998 8.70995 6.70998Z"></path>
  </svg>';
  $prev_text = '<svg width="24" height="24" viewBox="0 0 24 24" fill="var(--color-dark)">
      <path d="M14.7105 17.2998C14.3205 17.6898 13.6905 17.6898 13.3005 17.2998L8.71047 12.7098C8.32047 12.3198 8.32047 11.6898 8.71047 11.2998L13.3005 6.70979C13.6905 6.31979 14.3205 6.31979 14.7105 6.70979C15.1005 7.09979 15.1005 7.72978 14.7105 8.11979L10.8305 12.0098L14.7105 15.8898C15.1005 16.2798 15.0905 16.9198 14.7105 17.2998Z"></path>
    </svg>';


	echo '<nav class="woocommerce-pagination">';
	$pagination = paginate_links(array(
	'base' => add_query_arg('page', '%#%'),
	'format' => '',
	'prev_text' => $prev_text,
	'next_text' => $next_text,
	'prev_next' => true,
	'type' => 'array',
	'total' => $total,
	'current' => $page,
));

// Remove all the page numbers except for the previous and next buttons

if (!empty($pagination)) {
	$prev_link = $pagination[0];
}
	if ($page == 1) {
			$prev_link = '<span class="prev page-numbers">' . $prev_text . '</span>';
	}

  $next_link = '';
	if (!empty($pagination)) {
    $next_link = $pagination[count($pagination) - 1];
	}

	if ($page == $total) {
		$next_link = '<span class="next page-numbers">' . $next_text . '</span>';
	}


echo $pagination = ' '.$prev_link.' <div class=\'result\'><div class=\'current\'>Page '.$page.'</div> <div class=\'total\'>of '.$total.'</div></div> '.$next_link.' ';

	echo '</nav>';

}


remove_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination', 10 );
add_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination', 10 );





function woocommerce_custom_pages_pagination($query) {
    $big = 999999999;
    if (isset($_GET['total_pages'])) {
      $total = $_GET['total_pages'];
    } else {
      $total = $query->max_num_pages;
    }
    $page = max(1, get_query_var('paged'));

	//$page = isset( $page ) ? $page : wc_get_loop_prop( 'current_page' );
	//$total   = isset( $total ) ? $total : wc_get_loop_prop( 'total_pages' );

  $next_text = '<svg width="24" height="24" viewBox="0 0 24 24" fill="var(--color-dark)">
    <path d="M8.70995 6.70998C9.09995 6.31998 9.72995 6.31998 10.1199 6.70998L14.7099 11.3C15.0999 11.69 15.0999 12.32 14.7099 12.71L10.1199 17.3C9.72995 17.69 9.09995 17.69 8.70995 17.3C8.31995 16.91 8.31995 16.28 8.70995 15.89L12.5899 12L8.70995 8.11998C8.31995 7.72998 8.32995 7.08998 8.70995 6.70998Z"></path>
  </svg>';
  $prev_text = '<svg width="24" height="24" viewBox="0 0 24 24" fill="var(--color-dark)">
      <path d="M14.7105 17.2998C14.3205 17.6898 13.6905 17.6898 13.3005 17.2998L8.71047 12.7098C8.32047 12.3198 8.32047 11.6898 8.71047 11.2998L13.3005 6.70979C13.6905 6.31979 14.3205 6.31979 14.7105 6.70979C15.1005 7.09979 15.1005 7.72978 14.7105 8.11979L10.8305 12.0098L14.7105 15.8898C15.1005 16.2798 15.0905 16.9198 14.7105 17.2998Z"></path>
    </svg>';


	echo '<nav class="woocommerce-paginationa custom_pages_pagination">';
	$pagination = paginate_links(array(
	'base' => add_query_arg('page', '%#%'),
	'format' => '',
	'prev_text' => $prev_text,
	'next_text' => $next_text,
	'prev_next' => true,
	'type' => 'array',
	'total' => $total,
	'current' => $page,
));

// Remove all the page numbers except for the previous and next buttons

if (!empty($pagination)) {
	$prev_link = $pagination[0];
}
	if ($page == 1) {
			$prev_link = '<span class="prev page-numbers">' . $prev_text . '</span>';
	}

  $next_link = '';
	if (!empty($pagination)) {
    $next_link = $pagination[count($pagination) - 1];
	}

	if ($page == $total) {
		$next_link = '<span class="next page-numbers">' . $next_text . '</span>';
	}


echo $pagination = ' '.$prev_link.' <div class=\'result\'><div class=\'current\'>Page '.$page.'</div> <div class=\'total\'>of '.$total.'</div></div> '.$next_link.' ';

	echo '</nav>';

}









// DISPLAY RATING PRODUCT SINGLE
function skolka_display_rating() {
  global $product;

  $rating = $product->get_average_rating();

	if ( ! $rating ) {
	  $rating = 0;
	}

  $review_count  = $product->get_review_count();
	$review_text = 'Review' == $review_count ? 'Review' : ( 'Review' == $review_count ? 'Review' : 'Reviews' );
	?>

  <div class="woocommerce-product-rating">
		<div class="star-rating" title="<?php echo esc_attr('Rated'); ?> <?php echo esc_attr($rating); ?> <?php echo esc_attr('out of 5'); ?>">
			<span style="width: <?php echo esc_attr( ( ( $rating / 5 ) * 100 ) . '%' ); ?>"></span>
		</div>
		<a href="#reviews" class="woocommerce-review-link" rel="nofollow">(<span class="count"><?php echo esc_html( $review_count ); ?></span> <?php echo esc_html( $review_text ); ?>)</a></div>
<?php
}

add_action( 'woocommerce_single_product_summary', 'skolka_display_rating', 11);

function skolka_remove_default_rating() {
  remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
}
add_action( 'init', 'skolka_remove_default_rating' );







// PRODUCT LOOP SECOND IMAGE
add_action( 'woocommerce_before_shop_loop_item_title', 'skolka_product_loop_second_image', 10 );
function skolka_product_loop_second_image() {
	global $product;
	$attachment_ids = $product->get_gallery_image_ids();
	if ( $attachment_ids ) {
		if (count($attachment_ids)>1) {
			$secondary_image_id = $attachment_ids[1];
		} else {
			$secondary_image_id = $attachment_ids[0];
		}
		echo wp_get_attachment_image( $secondary_image_id, 'woocommerce_thumbnail', '', $attr = array( 'class' => 'secondary-image attachment-shop-catalog' ) );
	}
}






// PRODUCT LOOP BADGE TEXT
add_action( 'woocommerce_before_shop_loop_item', 'skolka_product_loop_badge_text', 10 );
function skolka_product_loop_badge_text() {
	?>
  <?php if ( get_field('badge_label') ) { ?>
  <div class="badge-text">
	<div class="label"><?php the_field('badge_label'); ?></div>
  </div>
  <?php } ?>
<?php
}



// PRODUCT LOOP BRAND
add_action( 'woocommerce_after_shop_loop_item', 'skolka_product_loop_brand_name', 10 );
function skolka_product_loop_brand_name() {
	?>
	<div class="product-brand">
	<?php
	$product_brands = get_the_terms( get_the_ID(), 'brand' );
	if ( $product_brands && ! is_wp_error( $product_brands ) ) {
	  echo '<ul class="brands">';
		foreach ( $product_brands as $brand ) {
		  $brand_logo_id = get_term_meta( $brand->term_id, 'brand_logo', true );
		  $brand_link    = get_term_link( $brand );
		  echo '<li>';
		  echo '<a href="' . esc_url( $brand_link ) . '">';
		  echo '<span>' . esc_html($brand->name) . '</span>';
		  echo '</a>';
		  echo '</li>';
		}
	  echo '</ul>';
	}
	?>
  </div>
  <?php if ( get_field('badge_icon') ) { ?>
  <div class="badge-icon">
		<img src="<?php echo esc_url( get_field('badge_icon') ); ?>" alt="<?php echo esc_attr('Image') ?>">
  </div>
	<?php } ?>
<?php
}


// REDIRECT TO MY-ACCOUNT
add_filter( 'woocommerce_login_redirect', 'skolka_custom_login_redirect' );

function skolka_custom_login_redirect( $redirect ) {
    $redirect = get_permalink( get_option( 'woocommerce_myaccount_page_id' ) );
    return $redirect;
}



// REDIRECT TO LOGIN PAGE
add_action( 'template_redirect', 'skolka_redirect_myaccount_to_login' );
function skolka_redirect_myaccount_to_login() {
    if ( ! is_user_logged_in() && is_page( 'my-account' ) ) {
        wp_redirect( site_url('/login/') );
        exit;
    }
}


add_action( 'woocommerce_after_customer_login_form', 'my_custom_login_text' );
function my_custom_login_text() {
  global $woocommerce;
  ?>
  <form method="post" class="lost_reset_password">
      <h6><?php echo esc_html( 'Lost Password', 'skolka' ); ?></h6>
      <p><?php echo apply_filters( 'woocommerce_lost_password_message', __( 'Lost your password? Please enter your username or email address. You will receive a link to create a new password via email.', 'skolka' ) ); ?></p>
      <p class="woocommerce-form-row woocommerce-form-row--first form-row form-row-first">
          <label for="user_login"><?php _e( 'Username or email', 'skolka' ); ?></label>
          <input class="woocommerce-Input woocommerce-Input--text input-text" type="text" name="user_login" id="user_login" />
      </p>
      <div class="clear"></div>
      <?php do_action( 'woocommerce_lostpassword_form' ); ?>
      <p class="woocommerce-form-row form-row">
          <input type="hidden" name="wc_reset_password" value="true" />
          <button type="submit" class="woocommerce-Button button" value="<?php esc_attr_e( 'Reset password', 'skolka' ); ?>"><?php esc_html_e( 'Reset', 'skolka' ); ?></button>
      </p>
      <?php wp_nonce_field( 'lost_password', 'woocommerce-lost-password-nonce' ); ?>
  </form>
  <?php
}








// REGISTER FORM START
add_action( 'woocommerce_register_form_start', 'skolka_registration_form_start' );
function skolka_registration_form_start() {
	remove_action( 'woocommerce_register_form', 'woocommerce_register_form', 0 );
	remove_action( 'woocommerce_register_form', 'woocommerce_register_form_end', 999 );

	echo '<div class="register-wrapper">
    <h6>' . esc_html('Sign up to Skolka') . '</h6>
    <div class="register-button">' . esc_html('Click here to') . ' <span>' . esc_html('register') . '</span></div>
    <div class="register-form-toggle">';
}




// REGISTER FORM FIELDS
add_action( 'woocommerce_register_form', 'skolka_registration_form_fields' );
function skolka_registration_form_fields() {
    $email = ( ! empty( $_POST['email'] ) ) ? sanitize_email( $_POST['email'] ) : '';
    ?>
    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
        <label for="reg_password"><?php _e( 'Password', 'skolka' ); ?> <span class="required">*</span></label>
        <input type="password" class="input-text" name="password" id="reg_password" autocomplete="new-password" />
    </p>
    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
        <label for="reg_password2"><?php _e( 'Confirm password', 'skolka' ); ?> <span class="required">*</span></label>
        <input type="password" class="input-text" name="password2" id="reg_password2" autocomplete="new-password" />
    </p>
    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide terms wc-terms-and-conditions">
        <label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
            <input type="checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" name="terms" <?php checked( apply_filters( 'woocommerce_terms_is_checked_default', isset( $_POST['terms'] ) ), true ); ?> id="terms" /> <span><?php printf( __( 'I&rsquo;ve read and accept the <a href="%s" target="_blank" class="woocommerce-terms-and-conditions-link">terms &amp; conditions</a>', 'skolka' ), esc_url( wc_get_page_permalink( 'terms' ) ) ); ?></span> <span class="required">*</span>
        </label>
        <input type="hidden" name="terms-field" value="1" />
    </p>

    <?php
}


// REGISTER FORM ENDS
add_action( 'woocommerce_register_form_end', 'skolka_registration_form_end' );
function skolka_registration_form_end() {
	echo '</div>';
}




// PASSWORD MATCH VALIDATION
add_action( 'woocommerce_register_post', 'skolka_validate_password_fields', 10, 3 );
function skolka_validate_password_fields( $username, $email, $validation_errors ) {
	if ( ! isset( $_POST['nonce_field_name'] ) || ! wp_verify_nonce( sanitize_text_field( $_POST['nonce_field_name'] ), 'nonce_action_name' ) ) {
		wp_die( 'Invalid nonce' );
	}

	if ( isset( $_POST['password'] ) && isset( $_POST['password2'] ) && sanitize_text_field( $_POST['password'] ) !== sanitize_text_field( $_POST['password2'] ) ) {
		$validation_errors->add( 'password_error', __( 'Passwords do not match.', 'skolka' ) );
	}
	return $validation_errors;
}




// UPDATE PASSWORD
add_action( 'woocommerce_created_customer', 'skolka_update_user_password' );
function skolka_update_user_password( $customer_id ) {
	if ( ! isset( $_POST['nonce_field_name'] ) || ! wp_verify_nonce( sanitize_text_field( $_POST['nonce_field_name'] ), 'nonce_action_name' ) ) {
		wp_die( 'Invalid nonce' );
	}

	if ( isset( $_POST['password'] ) ) {
		$password = sanitize_text_field( $_POST['password'] );
		wp_set_password( $password, $customer_id );
	}
}





// TERMS & CONDITIONS VALIDATION
add_action( 'woocommerce_register_post', 'skolka_terms_and_conditions_validation', 20, 3 );
function skolka_terms_and_conditions_validation( $username, $email, $validation_errors ) {
	if ( ! isset( $_POST['nonce_field_name'] ) || ! wp_verify_nonce( sanitize_text_field($_POST['nonce_field_name'] ), 'nonce_action_name' ) ) {
		wp_die( 'Invalid nonce' );
	}
	if ( ! isset( $_POST['terms'] ) ) {
		$validation_errors->add( 'terms_error', __( 'Terms and condition are not checked!', 'skolka' ) );
	}
	return $validation_errors;
}
add_filter( 'woocommerce_checkout_show_terms', '__return_false' );




// LOGIN FORM STARTS
add_action( 'woocommerce_login_form_start', 'skolka_login_form_start' );
function skolka_login_form_start() {
	if ( is_checkout() ) {
return;
	}
   echo '<div class="login-wrapper">
   <h6>' . esc_html('Login to Skolka') . '</h6>
   <div class="login-button">' . esc_html('Click here to') . ' <span>' . esc_html('login') . '</span></div>
   <div class="login-form-toggle">';
}



// LOGIN FORMS ENDS
add_action( 'woocommerce_login_form_end', 'skolka_login_form_end' );
function skolka_login_form_end() {
	if ( is_checkout() ) {
return;
	}
  echo '</div>' ;
 echo '</div>' ;
}





// ADD TITLE FOR ORDER REViEW
add_action( 'woocommerce_checkout_before_order_review', 'skolka_wrapper_before_order_review' );
function skolka_wrapper_before_order_review() {
	echo '<div class="order-review-wrapper">';
	echo '<h3 class="order-review-title">' . esc_html('Your Order') . '</h3>';
}

add_action( 'woocommerce_checkout_after_order_review', 'skolka_wrapper_after_order_review' );
function skolka_wrapper_after_order_review() {
	echo '<div>';
}



// ADD CLEAR CART ACTION
add_action( 'woocommerce_cart_actions', 'skolka_add_clear_cart_button', 0 );
function skolka_add_clear_cart_button() {
	echo '<a href="?clear-cart=true" class="button clear-button">' . esc_html('Clear Cart') . '</a>';
}

// CLEAR CART INIT
add_action( 'init', 'clear_cart_on_request' );
function clear_cart_on_request() {
	if ( isset( $_GET['clear-cart'] ) && 'true' === $_GET['clear-cart'] ) {
		WC()->cart->empty_cart();
	}
}




// MIN QTY
add_filter( 'woocommerce_quantity_input_min', 'skolka_minimum_quantity' );
function skolka_minimum_quantity( $quantity ) {
	return 1;
}






// ADD BRAND LOGO FIELD
function skolka_add_brand_logo_field( $taxonomy) {
	if ('brand' !== $taxonomy) {
		return;
	}
	wp_enqueue_media();
	$brand_id       = isset($_GET['tag_ID']) ? (int) $_GET['tag_ID'] : 0;
	$brand_logo_id  = get_term_meta($brand_id, 'brand_logo', true);
	$brand_logo_url = wp_get_attachment_url($brand_logo_id);
	?>
	 <div class="form-field">
		 <label for="brand_logo"><?php echo esc_html('Brand Logo'); ?></label>
			 <input type="hidden" name="brand_logo" id="brand_logo" value="<?php echo esc_attr($brand_logo_id); ?>">
			 <button type="button" class="upload_image_button button"><?php echo esc_html('Upload Image'); ?></button>
			 <button type="button" class="edit_image_button button" <?php echo !$brand_logo_id ? 'style="display:none"' : ''; ?>><?php echo esc_html('Edit Image'); ?></button>
			 <button type="button" class="remove_image_button button" <?php echo !$brand_logo_id ? 'style="display:none"' : ''; ?>><?php echo esc_html('Remove Image'); ?></button>
			 <div id="brand_logo_preview" <?php echo !$brand_logo_url ? 'style="display:none"' : ''; ?>>
				 <img src="<?php echo esc_url($brand_logo_url); ?>" width="100" height="100" style="object-fit: cover;">
			 </div>
	 </div>
	<script>
	jQuery(document).ready(function($) {

$('.upload_image_button').click(function(e) {
	e.preventDefault();
	var custom_uploader = wp.media({
		title: 'Upload Image',
		button: {
			text: 'Select'
		},
		multiple: false
	}).on('select', function() {
		var attachment = custom_uploader.state().get('selection').first().toJSON();
		$('#brand_logo').val(attachment.id);
		$('#brand_logo_preview').html('<img src="' + attachment.url + '" width="100" height="100" style="object-fit: cover;">').show();
		$('.edit_image_button, .remove_image_button').show();
		$('.upload_image_button').hide();
	}).open();
});


$('.edit_image_button').click(function(e) {
	e.preventDefault();
	var image_id = $('#brand_logo').val();
	var custom_uploader = wp.media({
		title: 'Edit Image',
		button: {
			text: 'Update'
		},
		multiple: false,
		library: {
			type: 'image'
		},
		selected: image_id
	}).on('select', function() {
		var attachment = custom_uploader.state().get('selection').first().toJSON();
		$('#brand_logo').val(attachment.id);
		$('#brand_logo_preview').html('<img src="' + attachment.url + '" width="100" height="100" style="object-fit: cover;">');
		$('.edit_image_button, .remove_image_button').show();
		$('.upload_image_button').hide();
	}).open();
});


$('.remove_image_button').click(function(e) {
	e.preventDefault();
	$('#brand_logo').val('');
	$('#brand_logo_preview').html('').hide();
	$('.edit_image_button, .remove_image_button').hide();
	$('.upload_image_button').show();
});
});
</script>
<?php
}
add_action('brand_edit_form_fields', 'skolka_edit_brand_logo_field', 10, 2);
add_action('brand_add_form_fields', 'skolka_add_brand_logo_field', 10, 2);

function save_brand_logo($term_id) {
    if (isset($_POST['brand_logo'])) {
        update_term_meta($term_id, 'brand_logo', $_POST['brand_logo']);
    }
}
add_action('edited_brand', 'save_brand_logo', 10, 2);
add_action('create_brand', 'save_brand_logo', 10, 2);





function skolka_edit_brand_logo_field( $term ) {
	wp_enqueue_media();
	$brand_logo_id  = get_term_meta($term->term_id, 'brand_logo', true);
	$brand_logo_url = wp_get_attachment_url($brand_logo_id);
	?>
	<tr class="form-field">
		<th scope="row" valign="top"><label for="brand_logo"><?php echo esc_html('Brand Logo'); ?></label></th>
		<td>
			<input type="hidden" name="brand_logo" id="brand_logo" value="<?php echo esc_attr($brand_logo_id); ?>">
			<button type="button" class="upload_image_button button"><?php echo esc_html('Upload Image'); ?></button>
			<button type="button" class="edit_image_button button" <?php echo !$brand_logo_id ? 'style="display:none"' : ''; ?>><?php echo esc_html('Edit Image'); ?></button>
			<button type="button" class="remove_image_button button" <?php echo !$brand_logo_id ? 'style="display:none"' : ''; ?>><?php echo esc_html('Remove Image'); ?></button>
			<div id="brand_logo_preview" <?php echo !$brand_logo_url ? 'style="display:none"' : ''; ?>>
				<img src="<?php echo esc_url($brand_logo_url); ?>" width="100" height="100" style="object-fit: cover;">
			</div>
		</td>
	</tr>
	<script>
		jQuery(document).ready(function($) {

			$('.upload_image_button').click(function(e) {
				e.preventDefault();
				var custom_uploader = wp.media({
					title: 'Upload Image',
					button: {
						text: 'Select'
					},
					multiple: false
				}).on('select', function() {
					var attachment = custom_uploader.state().get('selection').first().toJSON();
					$('#brand_logo').val(attachment.id);
					$('#brand_logo_preview').html('<img src="' + attachment.url + '" width="100" height="100" style="object-fit: cover;">').show();
					$('.edit_image_button, .remove_image_button').show();
					$('.upload_image_button').hide();
				}).open();
			});


			$('.edit_image_button').click(function(e) {
				e.preventDefault();
				var image_id = $('#brand_logo').val();
				var custom_uploader = wp.media({
					title: 'Edit Image',
					button: {
						text: 'Update'
					},
					multiple: false,
					library: {
						type: 'image'
					},
					selected: image_id
				}).on('select', function() {
					var attachment = custom_uploader.state().get('selection').first().toJSON();
					$('#brand_logo').val(attachment.id);
					$('#brand_logo_preview').html('<img src="' + attachment.url + '" width="100" height="100" style="object-fit: cover;">');
					$('.edit_image_button, .remove_image_button').show();
					$('.upload_image_button').hide();
				}).open();
			});


			$('.remove_image_button').click(function(e) {
				e.preventDefault();
				$('#brand_logo').val('');
				$('#brand_logo_preview').html('').hide();
				$('.edit_image_button, .remove_image_button').hide();
				$('.upload_image_button').show();

				// Check if the preview element is hidden, then hide the edit and remove buttons
				if ($('#brand_logo_preview').is(':hidden')) {
					$('.edit_image_button, .remove_image_button').hide();
				}
			});
		});
		</script>
		<?php
		}




// SIZE GUIDE
add_action( 'woocommerce_before_add_to_cart_button', 'skolka_size_guide', 5);
function skolka_size_guide() {
	?>
  <?php if ( get_field('display_size_guide') ) { ?>
 <div class="size-guide">
   <a href="<?php the_field('size_guide_file'); ?>" data-fancybox data-width="880"><?php the_field('size_guide_label'); ?></a>
   <svg width="18" height="18" viewBox="0 0 18 18" fill="none">
   <path d="M9.31886 3.59873L11.8618 6.14169M6.45803 6.45956L9.00099 9.00253M3.59719 9.3204L6.14016 11.8634M1.1862 11.7278L11.7278 1.1862C11.9761 0.937932 12.3786 0.937932 12.6269 1.1862L16.8138 5.37308C17.0621 5.62135 17.0621 6.02388 16.8138 6.27215L6.27215 16.8138C6.02388 17.0621 5.62135 17.0621 5.37308 16.8138L1.1862 12.6269C0.937932 12.3786 0.937932 11.9761 1.1862 11.7278Z" stroke="black" stroke-linecap="round" stroke-linejoin="round"/>
   </svg>
 </div>
  <?php } ?>
<?php
}



// PRODUCT BENEFITS
function skolka_product_benefits() {
	?>
  <?php if ( get_field('product_benefits') ) { ?>
		<?php
		while ( have_rows('add_benefit') ) :
the_row();
			?>
		  <div class="product-benefits">
			<?php if ( get_sub_field('benefit_icon') ) { ?>
			<figure><img src="<?php echo esc_url( get_sub_field( 'benefit_icon' ) ); ?>" alt="<?php echo esc_html('Image'); ?>" /></figure>
			<?php } ?>
			<div class="content">
				<?php echo wp_kses_post(get_sub_field( 'benefit_content' )); ?>
			</div>
			</div>
		<?php endwhile; ?>
  <?php } ?>
<?php
}
 add_action( 'woocommerce_single_product_summary', 'skolka_product_benefits', 30 );



// PRODUCT ACCORDION CONTENT
function skolka_product_accordion_content() {
	?>
	<?php if ( get_field('accordion_content') ) { ?>
  <div class="skolka-accordion">
	<?php
		while ( have_rows('add_content') ) :
	the_row();
			?>
	<div class="skolka-accordion-wrapper">
	  <h3 class="accordion-toggle"><?php echo esc_html( get_sub_field( 'title' ) ); ?> <span class="arrow"></span></h3>
	  <div class="accordion-content">
			<?php echo wp_kses_post(get_sub_field( 'content' )); ?>
	  </div>
	</div>
	<?php endwhile; ?>
</div>
<?php } ?>
<?php
}
add_action( 'woocommerce_single_product_summary', 'skolka_product_accordion_content', 35 );





// CUSTOM RE-VIEW SINGLE
function skolka_single_custom_reviews() {
	?>
<div class="product-single-reviews" id="reviews">
  <h6>
	<?php
	global $product;
	$review_count = $product->get_review_count();
	if (1 == $review_count) {
		echo 'Review(' . esc_html($review_count) . ')';
	} else {
		echo 'Reviews(' . esc_html($review_count) . ')';
	}
	?>
  </h6>
  <div class="avarage-rating">
	<?php
	global $product;
   $average = $product->get_average_rating();
   echo '<div class="avarage-text">' . esc_html($average) . '/5</div>';
	?>
	<?php
	global $product;
	$average = $product->get_average_rating();
	echo '<div class="star-rating">';
	echo '<span style="width: ' . esc_attr( ( ( $average / 5 ) * 100 ) ) . '%"></span>';
	echo '</div>';
	?>

  </div>
  <?php
  global $product;
	$rating_count = $product->get_rating_count();
	echo '<ul class="rating-progress-bars">';
	if ($rating_count > 0) {
	echo '<li>';
	echo '<small>' . esc_html('5 stars') . '</small>';
	echo '<div class="rating-progress-bar">';
	echo '<div class="bar" style="width: ' . esc_attr( ( ( $product->get_rating_count('5') / $rating_count ) * 100 ) ) . '%"></div>';
	echo '</div>';
	echo '</li>';
	}
	if ($rating_count > 0) {
	echo '<li>';
	echo '<small>' . esc_html('4 stars') . '</small>';
	echo '<div class="rating-progress-bar">';
	echo '<div class="bar" style="width: ' . esc_attr( ( ( $product->get_rating_count('4') / $rating_count ) * 100 ) ) . '%"></div>';
	echo '</div>';
	echo '</li>';
	}
	if ($rating_count > 0) {
	echo '<li>';
	echo '<small>' . esc_html('3 stars') . '</small>';
	echo '<div class="rating-progress-bar">';
	echo '<div class="bar" style="width: ' . esc_attr( ( ( $product->get_rating_count('3') / $rating_count ) * 100 ) ) . '%"></div>';
	echo '</div>';
	echo '</li>';
	}
	if ($rating_count > 0) {
	echo '<li>';
	echo '<small>' . esc_html('2 stars') . '</small>';
	echo '<div class="rating-progress-bar">';
	echo '<div class="bar" style="width: ' . esc_attr( ( ( $product->get_rating_count('2') / $rating_count ) * 100 ) ) . '%"></div>';
	echo '</div>';
	echo '</li>';
	}
	if ($rating_count > 0) {
	echo '<li>';
	echo '<small>' . esc_html('1 star') . '</small>';
	echo '<div class="rating-progress-bar">';
	echo '<div class="bar" style="width: ' . esc_attr( ( ( $product->get_rating_count('1') / $rating_count ) * 100 ) ) . '%"></div>';
	echo '</div>';
	echo '</li>';
	}
	echo '</ul>';
	?>

<a href="#comments" class="review-button" data-fancybox><?php echo esc_html( 'See Reviews' ); ?></a>
<a href="#review_form_wrapper" class="review-button" data-fancybox><?php echo esc_html( 'Write Review' ); ?></a>
</div>
 <?php
}
  add_action( 'woocommerce_single_product_summary', 'skolka_single_custom_reviews', 40 );






// REMOVE TABS
function skolka_remove_product_tabs( $tabs ) {
	unset( $tabs['description'] );
	unset( $tabs['additional_information'] );
	return $tabs;
}
add_filter( 'woocommerce_product_tabs', 'skolka_remove_product_tabs', 98 );



// SHOW DESCRIPTION AFTER FEATURES BOX
function skolka_display_product_description() {
	global $product;
	if ( $product->get_description() ) {
		echo '<div class="product-description">';
		echo '<h4>' . esc_html__( 'Product Description', 'skolka' ) . '</h4>';
		echo esc_html( $product->get_description() );
		echo '</div>';
	}
}
add_action( 'woocommerce_single_product_summary', 'skolka_display_product_description', 30 );






// HIDE GROUPED PRODUCTS PRICE
function skolka_hide_price_for_product_type_grouped( $price, $product ) {
	if ( $product->is_type( 'grouped' ) ) {
	  $price = '';
	  remove_action( 'woocommerce_single_product_summary', 'custom_reviews', 40 );
	  remove_action( 'woocommerce_single_product_summary', 'skolka_wishlist_button', 10 );

	}
  return $price;
}
add_filter( 'woocommerce_get_price_html', 'skolka_hide_price_for_product_type_grouped', 10, 2 );




// DISPLAY VARIABLE PRICES IN GENERAL PRICE
add_action( 'woocommerce_variable_add_to_cart', 'skolka_update_price_with_variation_price' );
function skolka_update_price_with_variation_price() {
  global $product;
  $price = $product->get_price_html();
  wc_enqueue_js( "
      $(document).on('found_variation', 'form.cart', function( event, variation ) {
         if(variation.price_html) $('.product .price').html(variation.price_html);
         $('.woocommerce-variation-price').hide();
      });
      $(document).on('hide_variation', 'form.cart', function( event, variation ) {
         $('.product .price').html('" . $price . "');
      });
   " );
}




// PRODUCT SINGLE SALE PERCENTAGE
function skolka_discount_percentage( $price, $product ) {
	if ( $product->is_on_sale() ) {
		$regular_price = $product->get_regular_price();
		$sale_price    = $product->get_sale_price();
		if ( ! empty( $regular_price ) && ! empty( $sale_price ) ) {
			$discount = round(( ( $regular_price - $sale_price ) / $regular_price ) * 100);
			$price    = '<span class="price">
			<del>' . wc_price( $regular_price ) . '</del>
			<span class="discount-percentage">- ' . $discount . ' %</span>
			<span class="clear"></span>
			<ins>' . wc_price( $sale_price ) . '</ins>
			<span class="vat-included">' . esc_html( skolka_get_option( 'price_custom_text' ) ) . '</span>
			</span>';
		}
	} else {
		$regular_price = $product->get_regular_price();
		if ( ! empty( $regular_price ) ) {
			$price = '<span class="price">
			<ins>' . wc_price( $regular_price ) . '</ins>
			<span class="vat-included">' . esc_html( skolka_get_option( 'price_custom_text' ) ) . '</span>
			</span>';
		}
	}
	return $price;
}
add_filter( 'woocommerce_get_price_html', 'skolka_discount_percentage', 20, 2 );




// PRODUCT LOOP DISPLAY VARIABLES LOWEST PRICE
add_filter( 'woocommerce_variable_sale_price_html', 'skolka_variation_price_format', 10, 2 );
add_filter( 'woocommerce_variable_price_html', 'skolka_variation_price_format', 10, 2 );
function skolka_variation_price_format( $price, $product ) {
  $price_custom_text = skolka_get_option('price_custom_text');
  $prices = array( $product->get_variation_price( 'min', true ), $product->get_variation_price( 'max', true ) );
  $price  = $prices[ 0 ] !== $prices[ 1 ] ? sprintf( wc_price( $prices[ 0 ] ) ) : wc_price( $prices[ 0 ] );
  $regular_prices = array( $product->get_variation_regular_price( 'min', true ), $product->get_variation_regular_price( 'max', true ) );
  $regular_price  = $regular_prices[ 0 ] !== $regular_prices[ 1 ] ? sprintf( wc_price( $regular_prices[ 0 ] ) ) : wc_price( $regular_prices[ 0 ] );
  if ( $price !== $regular_price ) {
    $discount = round(( ( $regular_prices[ 0 ] - $prices[ 0 ] ) / $regular_prices[ 0 ] ) * 100);
    $price = '<span class="price"><del><span class="woocommerce-Price-amount amount">' . $regular_price . $product->get_price_suffix() . '</span></del><span class="discount-percentage">- ' . $discount . ' %</span><span class="clear"></span><ins><span class="woocommerce-Price-amount amount"> ' . $price . $product->get_price_suffix() . ' </span> </ins> <span class="vat-included">' . esc_html($price_custom_text) . '</span></span>';
  }
  return $price;
}






// PRODUCT SINGLE WISHLIST BUTTON
add_action( 'woocommerce_single_product_summary', 'skolka_wishlist_button', 1 );
function skolka_wishlist_button() {
	echo do_shortcode('[yith_wcwl_add_to_wishlist]');
}


// PRODUCT SINGLE TEXT BADGE
add_action( 'woocommerce_before_single_product_summary', 'skolka_text_badge', 2 );
function skolka_text_badge() {
	?>
<?php if ( get_field('badge_label') ) { ?>
<div class="badge-text" <?php global $product; if ( $product->get_sale_price() ) { ?>style="left:75px;"<?php } ?>>
  <div class="label"><?php the_field('badge_label'); ?></div>
</div>
	<?php } ?>
<?php
}



// PRODUCT SINGLE BRAND
add_action( 'woocommerce_single_product_summary', 'skolka_product_single_brand_name', 0 );
function skolka_product_single_brand_name() {
	?>
  <?php if ( get_field('badge_icon') ) { ?>
  <div class="badge-icon">
	<img src="<?php the_field('badge_icon'); ?>" alt="<?php echo esc_attr('Image') ?>">
  </div>
  <?php } ?>
	<div class="product-brand">
	<?php
	$product_brands = get_the_terms( get_the_ID(), 'brand' );
	if ( $product_brands && ! is_wp_error( $product_brands ) ) {
	  echo '<ul class="brands">';
		foreach ( $product_brands as $brand ) {
		  $brand_logo_id = get_term_meta( $brand->term_id, 'brand_logo', true );
		  $brand_link    = get_term_link( $brand );
		  echo '<li>';
		  echo '<a href="' . esc_url( $brand_link ) . '">';
		  echo '<span>' . esc_html($brand->name) . '</span>';
		  echo '</a>';
		  echo '</li>';
		}
	  echo '</ul>';
	}
	?>
</div>

<?php
}




// GROUPED PRODUCTS ITEM COUNT
add_action( 'woocommerce_after_shop_loop_item', 'skolka_product_loop_grouped_items_count', 5 );
function skolka_product_loop_grouped_items_count() {
	global $product;
	if ( $product->is_type( 'grouped' ) ) {
		$child_products = $product->get_children();
		echo '<div class="total-items">' . count($child_products) . ' items</div>';
	}
}



// HIDE PRODUCT LOOP BUTTON
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart');



// HIDE DEFAULT BREADCRUMBS
add_filter( 'woocommerce_before_main_content', 'remove_breadcrumbs');
function remove_breadcrumbs() {
	if (!is_product()) {
		remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0);
	}
}






// ADD THUMBS FOR GROUPPED PRODUCT
add_action( 'woocommerce_grouped_product_list_before_price', 'skolka_grouped_product_thumbnail' );
function skolka_grouped_product_thumbnail( $product ) {
	$image_size    = array( 99, 143 );
	$attachment_id = get_post_meta( $product->get_id(), '_thumbnail_id', true );
	$link          = get_the_permalink( $product->get_id() );
	?>
	<td class="woocommerce-grouped-product-list-item__thumbnail">
		<a href="<?php echo esc_url($link); ?>" > <?php echo wp_get_attachment_image( $attachment_id, $image_size ); ?> </a>
	</td>
	<?php
}


// GROUPED PRODUCT BRAND
add_action( 'woocommerce_grouped_product_list_before_price', 'skolka_grouped_product_brand', 20 );
function skolka_grouped_product_brand( $product ) {

	?>
	<td class="woocommerce-grouped-product-list-item__brand">
	  <?php
	  $product_brands = get_the_terms( get_the_ID(), 'brand' );
		if ( $product_brands && ! is_wp_error( $product_brands ) ) {
		  echo '<ul class="brands">';
			foreach ( $product_brands as $brand ) {
			  $brand_logo_id = get_term_meta( $brand->term_id, 'brand_logo', true );
			  $brand_link    = get_term_link( $brand );
			  echo '<li>';
			  echo '<a href="' . esc_url( $brand_link ) . '">';
			  echo '<span>' . esc_html($brand->name) . '</span>';
			  echo '</a>';
			  echo '</li>';
			}
		  echo '</ul>';
		}
		?>
	</td>
	<?php
}


// ACCOUNT SIDEBAR TOGGLE BUTTON
function my_account_navigation_custom_text() {
    ?>
    <button class="account-toggle"><svg width="27" height="14" viewBox="0 0 27 14" fill="none">
		<rect width="27" height="2" rx="1" fill="var(--color-dark)"></rect>
		<rect y="6" width="18" height="2" rx="1" fill="var(--color-dark)"></rect>
		<rect y="12" width="12" height="2" rx="1" fill="var(--color-dark)"></rect>
  </svg> <?php echo esc_html('Account Menu', 'skolka');?></button>
    <?php
}
add_action( 'woocommerce_account_navigation', 'my_account_navigation_custom_text', 5 );




// ADD ACCOUNT MENU PAYMENT METHODS
add_filter( 'woocommerce_account_menu_items', function ( $items ) {
  $logout = $items[ 'customer-logout' ];
  unset( $items[ 'customer-logout' ] );
  $items[ 'payment-methods' ] = 'Payment Methods';
  $items[ 'customer-logout' ] = $logout;
  return $items;
} );



// ACCOUNT WISHLIST MENU ITEM
add_filter ( 'woocommerce_account_menu_items', 'skolka_wishlist_menu' );
function skolka_wishlist_menu( $menu_links ) {
  $new        = array( 'anyuniquetext123' => 'Wishlist' );
  $menu_links = array_slice( $menu_links, 0, 1, true )
	+ $new
	+ array_slice( $menu_links, 1, null, true );
	return $menu_links;
}


// ACCOUNT WISHLIST MENU URL
add_filter( 'woocommerce_get_endpoint_url', 'skolka_hook_endpoint', 10, 4 );
function skolka_hook_endpoint( $url, $endpoint, $value, $permalink ) {
	if ( 'anyuniquetext123' === $endpoint ) {
		$url = '/wishlist/';
	}
	return $url;
}




// NAVBAR ACCOUNT FUNCTION
function skolka_navbar_account() {
	?>
<?php
	if ( skolka_get_option('display_account') ) {
		  $account_button = skolka_get_option('account_button');
		?>
  <div class="navbar-account">
	<a href="<?php echo esc_url( home_url( '/my-account/' ) ); ?>">
		<?php if ( ! is_user_logged_in() ) { ?>
	  <?php echo esc_html('Account'); ?>
	<?php
		} else {
		  $user = wp_get_current_user();
			?>
		<?php echo esc_html( $user->display_name ); ?>
		<?php } ?>
	</a>
	<svg width="20" height="22" viewBox="0 0 20 22" fill="none">
	<path d="M0.958459 21.5C0.406175 21.5 -0.0462572 21.0514 0.00378979 20.5014C0.0980957 19.4649 0.338862 18.4471 0.719665 17.4818C1.22221 16.2079 1.95881 15.0504 2.88739 14.0754C3.81598 13.1004 4.91837 12.3269 6.13163 11.7993C7.34488 11.2716 8.64524 11 9.95846 11C11.2717 11 12.572 11.2716 13.7853 11.7993C14.9986 12.3269 16.1009 13.1004 17.0295 14.0754C17.9581 15.0504 18.6947 16.2079 19.1973 17.4818C19.5781 18.4471 19.8188 19.4649 19.9131 20.5014C19.9632 21.0514 19.5107 21.5 18.9585 21.5C18.4062 21.5 17.9643 21.0509 17.9018 20.5021C17.8152 19.7418 17.6297 18.9958 17.3495 18.2855C16.9475 17.2663 16.3582 16.3403 15.6153 15.5603C14.8724 14.7803 13.9905 14.1616 13.0199 13.7394C12.0493 13.3173 11.009 13.1 9.95846 13.1C8.90789 13.1 7.8676 13.3173 6.89699 13.7394C5.92639 14.1616 5.04448 14.7803 4.30161 15.5603C3.55874 16.3403 2.96946 17.2663 2.56742 18.2855C2.28719 18.9958 2.10175 19.7418 2.01511 20.5021C1.95258 21.0509 1.51074 21.5 0.958459 21.5Z" fill="var(--color-dark)"/>
	<path d="M13.9585 5C13.9585 7.20914 12.1676 9 9.95846 9C7.74932 9 5.95846 7.20914 5.95846 5C5.95846 2.79086 7.74932 1 9.95846 1C12.1676 1 13.9585 2.79086 13.9585 5ZM1.02154 20.3889C1.02073 20.396 1.01952 20.4028 1.01797 20.4094C1.11426 19.5302 1.32665 18.6682 1.6499 17.8488C2.106 16.6926 2.77333 15.6451 3.61153 14.765C4.44964 13.885 5.44195 13.1897 6.53046 12.7163C7.61884 12.2429 8.78353 12 9.95846 12C11.1334 12 12.2981 12.2429 13.3865 12.7163C14.475 13.1897 15.4673 13.885 16.3054 14.765C17.1436 15.6451 17.8109 16.6926 18.267 17.8488C18.5903 18.6682 18.8027 19.5302 18.8989 20.4094C18.8974 20.4028 18.8962 20.396 18.8954 20.3889C18.7989 19.5423 18.5924 18.711 18.2797 17.9185C17.8312 16.7816 17.1727 15.7456 16.3395 14.8706C15.5061 13.9956 14.5141 13.2988 13.4188 12.8224C12.3233 12.3459 11.1473 12.1 9.95846 12.1C8.7696 12.1 7.59364 12.3459 6.49816 12.8224C5.40281 13.2988 4.41082 13.9956 3.57747 14.8706C2.74422 15.7456 2.08567 16.7816 1.63719 17.9185C1.32455 18.711 1.11801 19.5423 1.02154 20.3889Z" stroke="var(--color-dark)" stroke-width="2"/>
	</svg>
	<div class="account-dropdown">
			<?php if ( ! is_user_logged_in() ) { ?>
		<a href="<?php echo esc_url( home_url( '/my-account/' ) ); ?>" class="button"><?php echo esc_html('Login'); ?></a>
		  <ul>
			<li><a href="<?php echo esc_url( home_url( '/my-account/' ) ); ?>"><?php echo esc_html('Your Account'); ?></a></li>
			<li><a href="<?php echo esc_url( home_url( '/track-your-order/' ) ); ?>"><?php echo esc_html('Order Tracking'); ?></a></li>
			<li><a href="<?php echo esc_url( home_url( '/help-and-support/' ) ); ?>"><?php echo esc_html('Support'); ?></a></li>
		  </ul>
		  <a href="<?php echo esc_url( home_url( '/my-account/' ) ); ?>" class="account-link"><?php echo esc_html('Register Now'); ?></a>
		<?php
			} else {
		  $user = wp_get_current_user();
				?>
		  <ul>
			<li><a href="<?php echo esc_url( home_url( '/my-account/' ) ); ?>"><?php echo esc_html('Your Account'); ?></a></li>
			<li><a href="<?php echo esc_url( home_url( '/my-account/orders/' ) ); ?>"><?php echo esc_html('Orders'); ?></a></li>
			<li><a href="<?php echo esc_url( home_url( '/my-account/edit-address/' ) ); ?>"><?php echo esc_html('Addresses'); ?></a></li>
		  </ul>
		<p><?php echo esc_html('Not'); ?> <b><?php echo esc_html( $user->display_name ); ?></b> ? <a href="<?php echo esc_url('/wp-login.php?action=logout&_wpnonce=<nonce_value>'); ?>"><?php echo esc_html('Log out'); ?></a></p>
		<?php } ?>

	</div>
 </div>
		<?php } ?>
<?php
}



// NAVBAR SEARCH FUNCTION
function skolka_navbar_search() {
	?>
  <?php
	if ( skolka_get_option('display_search') ) {
	  $search_box = skolka_get_option('search_box');
		?>
  <div class="navbar-search">
	<form action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get" autocomplete="off" id="product_search" >
	  <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
	  <g clip-path="url(#clip0_0_480)">
	  <path fill-rule="evenodd" clip-rule="evenodd" d="M11.35 12.4842C9.95003 13.5949 8.17828 14.1281 6.39783 13.9746C4.61738 13.8211 2.96308 12.9924 1.77394 11.6584C0.584813 10.3244 -0.0490893 8.58611 0.00214822 6.79979C0.0533858 5.01347 0.785883 3.31438 2.04952 2.05074C3.31316 0.787104 5.01225 0.0546065 6.79857 0.00336892C8.58489 -0.0478686 10.3232 0.586034 11.6572 1.77517C12.9912 2.9643 13.8198 4.6186 13.9734 6.39905C14.1269 8.1795 13.5937 9.95125 12.483 11.3512L16 14.8592L14.86 16.0002L11.35 12.4852V12.4842ZM12.4 7.00021C12.4 8.43238 11.8311 9.80589 10.8184 10.8186C9.80567 11.8313 8.43216 12.4002 6.99999 12.4002C5.56782 12.4002 4.19431 11.8313 3.18162 10.8186C2.16892 9.80589 1.59999 8.43238 1.59999 7.00021C1.59999 5.56804 2.16892 4.19453 3.18162 3.18184C4.19431 2.16914 5.56782 1.60021 6.99999 1.60021C8.43216 1.60021 9.80567 2.16914 10.8184 3.18184C11.8311 4.19453 12.4 5.56804 12.4 7.00021Z" fill="black"/>
	  </g>
	  <defs>
	  <clipPath id="clip0_0_480">
	  <rect width="16" height="16" fill="white"/>
	  </clipPath>
	  </defs>
	  </svg>
	  <input type="text" placeholder="<?php echo esc_attr('Search'); ?>" value="<?php echo get_search_query(); ?>" name="keyword"  id="keyword" class="input_search" onkeyup="fetcha()">

		<select name="pcat" id="cat" onchange="fetcha()">
	<option value=""><?php echo esc_html('All Categories'); ?></option>
	<?php
		$main_categories = get_terms( array(
		'taxonomy' => 'product_cat',
		'hide_empty' => true,
		'parent' => 0
		) );

	foreach ($main_categories as $main_cat) {
		echo '<option value="' . esc_attr($main_cat->term_id) . '">' . esc_html($main_cat->name) . '</option>';
	}
	?>
</select>
	  <button type="submit">
		<?php echo esc_html('SEARCH'); ?>
	  </button>
	  <input type="hidden" name="post_type" value="product" />
	</form>
	<div class="search_result" id="datafetch">
	  <ul>
		<li><?php echo esc_html('Please wait..'); ?></li>
	  </ul>
	</div>
  </div>
<?php } ?>
<?php
}



// NAVBAR WISHLIST FUNCTION
function skolka_navbar_wishlist() {
	?>
<?php
	if ( skolka_get_option('display_wishlist') ) {
		  $wishlist_button = skolka_get_option('wishlist_button');
		?>
<div class="navbar-wishlist">
<a href="<?php echo esc_url( home_url( '/wishlist/' ) ); ?>"><svg width="24" height="24" viewBox="0 0 24 24" fill="none">
<path d="M21.11 3.99999C20.4815 3.37463 19.7334 2.88231 18.9104 2.55243C18.0875 2.22255 17.2065 2.06188 16.32 2.07999C14.7073 2.08922 13.1601 2.71956 12 3.83999C10.7554 2.68368 9.11134 2.05441 7.41277 2.08424C5.7142 2.11408 4.09322 2.80069 2.89001 3.99999C0.0900146 6.67999 0.440015 11.3 3.77001 14.76L10.61 21.39C10.9752 21.7681 11.4745 21.9872 12 22C12.5087 22.0008 12.9986 21.8077 13.37 21.46L13.57 21.27L14.18 20.7C14.78 20.13 15.6 19.33 16.67 18.29L19.11 15.9L20.2 14.83C23.58 11.28 24 6.72999 21.11 3.99999ZM18.76 13.4L18.51 13.64L17.71 14.43L15.27 16.82C14.27 17.82 13.43 18.61 12.83 19.18L12 20L5.17001 13.32C2.61001 10.66 2.31001 7.31999 4.29001 5.39999C4.70988 4.97911 5.20864 4.64518 5.75775 4.41735C6.30686 4.18951 6.89552 4.07223 7.49001 4.07223C8.08451 4.07223 8.67317 4.18951 9.22228 4.41735C9.77138 4.64518 10.2702 4.97911 10.69 5.39999L10.78 5.47999C10.8968 5.56866 11.004 5.6692 11.1 5.77999L12 6.71999L12.9 5.77999L13.18 5.50999L13.29 5.41999C13.7104 4.99459 14.2111 4.65684 14.7629 4.42633C15.3148 4.19582 15.9069 4.07712 16.505 4.07712C17.1031 4.07712 17.6952 4.19582 18.2471 4.42633C18.799 4.65684 19.2996 4.99459 19.72 5.41999C21.69 7.31999 21.39 10.67 18.76 13.4Z" fill="var(--color-main)"/>
</svg>
</a>
</div>
	<?php } ?>
<?php
}



// NAVBAR CART FUNCTION
function skolka_navbar_cart() {
	?>
<?php
	if ( skolka_get_option('display_bag') ) {
		  $cart_button = skolka_get_option('cart_button');
		?>
<div class="navbar-bag">
  <a href="<?php echo esc_url( home_url( '/cart/' ) ); ?>" class="cart-link">
	  <?php echo esc_html('Bag'); ?>
  <div class="icon-wrapper">
  <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
  <path d="M20 5H4C3.46957 5 2.96086 5.21071 2.58579 5.58579C2.21071 5.96086 2 6.46957 2 7V20C2 20.7956 2.31607 21.5587 2.87868 22.1213C3.44129 22.6839 4.20435 23 5 23H19C19.7956 23 20.5587 22.6839 21.1213 22.1213C21.6839 21.5587 22 20.7956 22 20V7C22 6.46957 21.7893 5.96086 21.4142 5.58579C21.0391 5.21071 20.5304 5 20 5ZM20 20C20 20.2652 19.8946 20.5196 19.7071 20.7071C19.5196 20.8946 19.2652 21 19 21H5C4.73478 21 4.48043 20.8946 4.29289 20.7071C4.10536 20.5196 4 20.2652 4 20V7H20V20Z" fill="var(--color-dark)"/>
  <path d="M8 5.5C7.44771 5.5 6.99144 5.04927 7.08257 4.50456C7.14604 4.1252 7.24576 3.75332 7.3806 3.39524C7.63188 2.72795 8.00017 2.12163 8.46447 1.61091C8.92876 1.10019 9.47996 0.695064 10.0866 0.418663C10.6932 0.142262 11.3434 4.27586e-07 12 5.1656e-07C12.6566 6.05534e-07 13.3068 0.142262 13.9134 0.418663C14.52 0.695064 15.0712 1.10019 15.5355 1.61091C15.9998 2.12164 16.3681 2.72795 16.6194 3.39524C16.7542 3.75332 16.854 4.1252 16.9174 4.50456C17.0086 5.04927 16.5523 5.5 16 5.5V5.5C15.4477 5.5 15.014 5.04385 14.8626 4.51271C14.836 4.41958 14.8057 4.32763 14.7716 4.23715C14.6209 3.83677 14.3999 3.47298 14.1213 3.16655C13.8427 2.86011 13.512 2.61704 13.1481 2.4512C12.7841 2.28536 12.394 2.2 12 2.2C11.606 2.2 11.2159 2.28536 10.8519 2.4512C10.488 2.61704 10.1573 2.86011 9.87868 3.16655C9.6001 3.47298 9.37913 3.83677 9.22836 4.23714C9.19429 4.32763 9.16395 4.41958 9.13741 4.51271C8.98602 5.04384 8.55228 5.5 8 5.5V5.5Z" fill="var(--color-dark)"/>
  <rect opacity="0.3" x="9" y="18" width="8" height="2" rx="1" transform="rotate(-90 9 18)" fill="var(--color-dark)"/>
  <rect opacity="0.3" x="13" y="18" width="6" height="2" rx="1" transform="rotate(-90 13 18)" fill="var(--color-dark)"/>
  </svg>
</div>
</a>
<div class="cart-items-count">
  <span id="cart-count"><?php
        $cart_count = WC()->cart->get_cart_contents_count();
        echo  esc_html($cart_count);
        ?></span>
	<?php
		?>
</div>
<div class="mini-cart-dropdown">
  <div class="widget_shopping_cart_content"><?php woocommerce_mini_cart();?></div>
</div>
</div>
	<?php } ?>
<?php
}


// CART COUNT FRAGMENT
add_filter( 'woocommerce_add_to_cart_fragments', 'refresh_cart_count', 50, 1 );
function refresh_cart_count( $fragments ){
    ob_start();
    ?>
    <span id="cart-count"><?php
    $cart_count = WC()->cart->get_cart_contents_count();
    echo  esc_html($cart_count);
    ?></span>
    <?php
     $fragments['#cart-count'] = ob_get_clean();

    return $fragments;
}


// CUSTOMIZE MINI CART CONTENT
function skolka_custom_empty_cart() {
	?>
  <?php if ( WC()->cart->is_empty() ) : ?>
	<h6 class="mini-cart-title"><?php echo ( 'YOUR BAG IS EMPTY' ); ?></h6>
	<p>
	  <?php echo ( 'Stock your bag with all of your stylish items and needs.' ); ?>
	</p>
	<a href="/shop/" class="button wp-element-button"><?php echo ( 'Browse Products' ); ?></a>
  <?php else : ?>
  <h6 class="mini-cart-title"><?php echo ( 'YOUR BAG' ); ?></h6>
  <?php endif; ?>

<?php
}
add_filter( 'woocommerce_before_mini_cart', 'skolka_custom_empty_cart' );



// CUSTOMIZE MINI CART BUTTONS
remove_action( 'woocommerce_widget_shopping_cart_buttons', 'woocommerce_widget_shopping_cart_proceed_to_checkout', 20 );
function qonto_custom_view_cart_button_label( $translated_text, $text, $domain ) {
	if ('View cart' === $text && 'woocommerce' === $domain) {
		$translated_text = __( 'GO TO BAG', 'skolka' );
	}
	return $translated_text;
}
add_filter( 'gettext', 'qonto_custom_view_cart_button_label', 10, 3 );





// AJAX SEARCH JQUERY
add_action( 'wp_footer', 'ajax_fetch' );
function ajax_fetch() {
?>
<script>
function fetcha(){
    jQuery.ajax({
        url: '<?php echo admin_url('admin-ajax.php'); ?>',
        type: 'post',
        data: { action: 'data_fetch', keyword: jQuery('#keyword').val(), pcat: jQuery('#cat').val() },
        success: function(data) {
            jQuery('#datafetch').html( data );
        }
    });
}
</script>
<?php
}

// AJAX SEARCH FUNCTION
add_action('wp_ajax_data_fetch' , 'data_fetch');
add_action('wp_ajax_nopriv_data_fetch','data_fetch');
function data_fetch(){

  if ( $_POST[ 'pcat' ] ) {
    $product_cat_id = array( esc_attr( $_POST[ 'pcat' ] ) );
  } else {
    $terms = get_terms( 'product_cat' );
    $product_cat_id = wp_list_pluck( $terms, 'term_id' );
  }
	$the_query = new WP_Query(
	    array(
	        'posts_per_page' => 5,
	        's' => esc_attr( $_POST[ 'keyword' ] ),
	        'post_type' => array( 'product' ),
	        'tax_query' => array(
	            array(
	                'taxonomy' => 'product_cat',
	                'field' => 'term_id',
	                'terms' => $product_cat_id,
	                'operator' => 'IN',
	            )
	        )
	    )
	);
  echo '<ul>';
	if ( $the_query->have_posts() ) :

	    while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
	        <li>
	            <a href="<?php echo esc_url( get_permalink() ); ?>">
	                <span><?php the_post_thumbnail('woocommerce_get_image_size_thumbnail') ?></span>
	                <?php the_title();?>
	            </a>
	        </li>
	    <?php endwhile;

	    wp_reset_postdata();
	else:
	    echo '<li>No products found</li>';
	endif;
 echo '</ul>';
	die();
}






// SHIPPING RATE
add_filter( 'woocommerce_package_rates', 'skolka_unset_shipping_when_free_is_available_all_zones', 9999, 2 );
function skolka_unset_shipping_when_free_is_available_all_zones( $rates, $package ) {
  $all_free_rates = array();
	foreach ( $rates as $rate_id => $rate ) {
		if ( 'free_shipping' === $rate->method_id ) {
		  $all_free_rates[ $rate_id ] = $rate;
		  break;
		}
	}
	if ( empty( $all_free_rates ) ) {
	  return $rates;
	} else {
	  return $all_free_rates;
	}
}




// HIDE SHIPPING ESTIMATE
function shipping_estimate_html() {
  return null;
}
add_filter( 'woocommerce_shipping_estimate_html', 'shipping_estimate_html' );



// CUSTOM ORDER BUTTON LABEL
add_filter( 'woocommerce_order_button_text', 'skolka_custom_button_label' );
function skolka_custom_button_label( $button_text ) {
  return 'Proceed to payment';
}



// HIDE CHOICE AND OPTION FROM SELECT BOX
add_filter( 'woocommerce_dropdown_variation_attribute_options_html', 'skolka_filter_dropdown_option_html', 12, 2 );
function skolka_filter_dropdown_option_html( $html, $args ) {
  $show_option_none_text = $args[ 'show_option_none' ] ? $args[ 'show_option_none' ] : esc_attr( 'Choose an option', 'skolka' );
  $show_option_none_html = '<option value="">' . esc_html( $show_option_none_text ) . '</option>';
  $html                  = str_replace( $show_option_none_html, '', $html );
  return $html;
}






/* WOOCOMMERCE IMAGE SIZES */
add_filter( 'woocommerce_get_image_size_thumbnail', function ( $size ) {
  return array(
	'width' => 306,
	'height' => 442,
	'crop' => 1,
  );
} );


add_filter( 'woocommerce_get_image_size_gallery_thumbnail', function ( $size ) {
  return array(
	'width' => 306,
	'height' => 442,
	'crop' => 1,
  );
} );


add_filter( 'woocommerce_get_image_size_single', function ( $size ) {
  return array(
	'width' => 506,
	'height' => 758,
	'crop' => 1,
  );
} );


add_filter( 'swatches_image_size', function ( $size ) {
  return array(
	'width' => 60,
	'height' => 87,
	'crop' => 1,
  );
} );



// CHECKOUT REGISTER CUSTOM HEADING
add_action( 'woocommerce_before_checkout_registration_form', 'skolka_checkout_custom_heading' );
function skolka_checkout_custom_heading() {
  echo '<h3>' . esc_html( 'Create an account' ) . '</h3>';
}



// BUY NOW BUTTON
function skolka_button_after_addtocart() {
  $current_product_id = get_the_ID();
  $product            = wc_get_product( $current_product_id );
  $checkout_url       = wc_get_checkout_url();
	if ( $product->is_type( 'simple' ) ) {
	  echo '<a href="' . esc_attr($checkout_url) . '?add-to-cart=' . esc_attr($current_product_id) . '" class="quick-purchase-button">' . esc_html('QUICK PURCHASE') . '</a>';
	}
}
add_action( 'woocommerce_after_add_to_cart_button', 'skolka_button_after_addtocart' );




// ADDRESS FIELDS
add_filter( 'woocommerce_default_address_fields', 'skolka_override_default_address_fields' );
function skolka_override_default_address_fields( $address_fields ) {
  $address_fields[ 'address_1' ][ 'label' ] = esc_html( 'Address', 'skolka' );
  $address_fields[ 'city' ][ 'label' ]      = esc_html( 'City', 'skolka' );
  return $address_fields;
}



// WOOCOMMERCE CUSTOM ACTIONS
remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
remove_action( 'woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20 );
remove_action( 'woocommerce_cart_contents', 'woocommerce_cart_coupon', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );

add_action( 'woocommerce_after_order_notes', 'woocommerce_checkout_payment', 20 );
add_filter( 'woocommerce_billing_fields', 'remove_company_name_from_checkout', 10, 1 );
add_filter( 'woocommerce_shipping_fields', 'remove_company_name_from_shipping', 10, 1 );
add_filter( 'woocommerce_reset_variations_link', '__return_empty_string', 9999 );
add_filter( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );





// HIDE CHECKOUT FIELDS
function remove_company_name_from_checkout( $fields ) {
  unset( $fields[ 'billing_company' ] );
  unset( $fields[ 'billing_address_2' ] );
  return $fields;
}


// HIDE SHIPPING FIELDS
function remove_company_name_from_shipping( $fields ) {
  unset( $fields[ 'shipping_company' ] );
  unset( $fields[ 'shipping_address_2' ] );
  return $fields;
}



// FIELDS RE-ORDER
add_filter( 'woocommerce_checkout_fields', 'skolka_email_first' );
function skolka_email_first( $checkout_fields ) {
  $checkout_fields[ 'billing' ][ 'billing_first_name' ][ 'priority' ] = 10;
  $checkout_fields[ 'billing' ][ 'billing_last_name' ][ 'priority' ]  = 20;
  $checkout_fields[ 'billing' ][ 'billing_email' ][ 'priority' ]      = 30;
  $checkout_fields[ 'billing' ][ 'billing_phone' ][ 'priority' ]      = 40;
  $checkout_fields[ 'billing' ][ 'billing_address_1' ][ 'priority' ]  = 50;
  $checkout_fields[ 'billing' ][ 'billing_postcode' ][ 'priority' ]   = 1;
  $checkout_fields[ 'billing' ][ 'billing_city' ][ 'priority' ]       = 100;
  $checkout_fields[ 'billing' ][ 'billing_state' ][ 'priority' ]      = 130;
  $checkout_fields[ 'billing' ][ 'billing_country' ][ 'priority' ]    = 120;
  return $checkout_fields;
}



// WOOCOMMERCE CUSTOM BREADCRUMB
add_filter( 'woocommerce_breadcrumb_defaults', 'skolka_woocommerce_breadcrumbs' );
function skolka_woocommerce_breadcrumbs() {
  return array(
	'delimiter' => '',
	'wrap_before' => '<ul class="breadcrumb">',
	'wrap_after' => '</ul>',
	'before' => '<li>',
	'after' => '</li>',
	'home' => _x( 'Home', 'breadcrumb', 'skolka' ),
  );
}



// TOTAL PRICE
if ( !function_exists( 'yith_wcwl_items_total' ) ) {
	function yith_wcwl_items_total( $args ) {

	  $wishlist = isset( $args[ 'wishlist' ] ) ? $args[ 'wishlist' ] : false;

		if ( !$wishlist || !$wishlist instanceof YITH_WCWL_Wishlist ) {
		  return;
		}

	  $total = 0;

		if ( $wishlist->has_items() ) {
			foreach ( $wishlist->get_items() as $item ) {
			  $total += $item->get_product_price();
			}
		}

		if ( $total ) {
		  echo '<div class="total-item-price-bar">' . esc_html( yith_wcwl_count_all_products() ) . ' ' . esc_html('Items Total: ') . '<b>' . wc_price( $total ) . '</b> <div class="goto-cart">
              <a href=" ' . esc_url('/cart/') . ' ">' . esc_html('GO TO CART') . '</a>
            </div></div>';
		}
	}
}
add_action( 'yith_wcwl_wishlist_after_wishlist_content', 'yith_wcwl_items_total', 5, 1 );




// RELATED PRODUCTS OUTPUT
function skolka_related_products_output() {
	remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
	add_action( 'woocommerce_after_single_product_summary', 'skolka_custom_related_products_output', 20 );
}
add_action( 'init', 'skolka_related_products_output' );



// RELATED PRODUCTS CUSTOM OUTPUT
function skolka_custom_related_products_output() {
	global $product, $woocommerce_loop;

	$related = wc_get_related_products( $product->get_id(), 10 );

	if ( count( $related ) == 0 ) {
	return;
	}

	/**
	*
	*	Filters the related products arguments.
	*
	*	@param array $args The arguments for related products.
	*
	*	@return array
	*
	* @since 1.0.0
	*
	*/
	$args = apply_filters( 'woocommerce_related_products_args', array(
		'post_type'            => 'product',
		'ignore_sticky_posts'  => 1,
		'no_found_rows'        => 1,
		'posts_per_page'       => 10,
		'post__in'             => $related,
		'post__not_in'         => array( $product->get_id() )
	) );

	$products = new WP_Query( $args );

	$woocommerce_loop['name'] = 'related';
	/**
	*
	*	Apply the filters for the number of related products columns.
	*
	*	This sets the number of columns for the related products section. The default value is 10.
	*
	*	@var int $woocommerce_loop['columns']
	*
	* @since 1.0.0
	*
	*/
	$woocommerce_loop['columns'] = apply_filters( 'woocommerce_related_products_columns', 10 );

	if ( $products->have_posts() ) :
		?>
	<div class="skolka-related-products">
	<h2><?php
  $related_products_title = skolka_get_option('related_products_title');
  echo esc_html($related_products_title); ?></h2>
	<h5><?php
  $related_products_subtitle = skolka_get_option('related_products_subtitle');
  echo esc_html($related_products_subtitle); ?></h5>
		<div class="swiper-carousel woocommerce" data-column="4" data-loop="false" data-drag="true">
		  <div class="swiper">
			<div class="swiper-wrapper">
				<?php
				while ( $products->have_posts() ) :
$products->the_post();
					?>
					<div class="swiper-slide">
					  <ul class="products">
						<?php wc_get_template_part( 'content', 'product' ); ?>
					  </ul>
					</div>
				<?php endwhile; ?>
			  </div>
			  <div class="slider-button-prev">
		  <svg width="24" height="24" viewBox="0 0 24 24" fill="#ffffff">
			<path d="M14.7105 17.2998C14.3205 17.6898 13.6905 17.6898 13.3005 17.2998L8.71047 12.7098C8.32047 12.3198 8.32047 11.6898 8.71047 11.2998L13.3005 6.70979C13.6905 6.31979 14.3205 6.31979 14.7105 6.70979C15.1005 7.09979 15.1005 7.72978 14.7105 8.11979L10.8305 12.0098L14.7105 15.8898C15.1005 16.2798 15.0905 16.9198 14.7105 17.2998Z"></path>
		  </svg>
		</div>
		<div class="slider-button-next">
		  <svg width="24" height="24" viewBox="0 0 24 24" fill="#ffffff">
			<path d="M8.70995 6.70998C9.09995 6.31998 9.72995 6.31998 10.1199 6.70998L14.7099 11.3C15.0999 11.69 15.0999 12.32 14.7099 12.71L10.1199 17.3C9.72995 17.69 9.09995 17.69 8.70995 17.3C8.31995 16.91 8.31995 16.28 8.70995 15.89L12.5899 12L8.70995 8.11998C8.31995 7.72998 8.32995 7.08998 8.70995 6.70998Z"></path>
		  </svg></div>
			</div>
		  <div class="swiper-pagination"></div>
			  </div>
		  </div>
	<?php
	endif;

	wp_reset_postdata();
}



// SHOP PRODUCTS COLUMN
function loop_columns() {
  $select_product_columns = skolka_get_option('select_product_columns');
  return $select_product_columns;
}
add_filter( 'loop_shop_columns', 'loop_columns', 999 );


// POST PER PAGE
function skolka_products_per_page() {
  return get_option( 'posts_per_page' );
}
add_filter( 'loop_shop_per_page', 'skolka_products_per_page', 20 );






// CUSTOM DISPLAY UPSELL PRODUCTS
function skolka_remove_upsell_products() {
  remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
  add_action( 'woocommerce_after_single_product_summary', 'skolka_custom_upsell_products', 5 );
}
add_action( 'init', 'skolka_remove_upsell_products' );




function skolka_custom_upsell_products( $args ) {
  global $product, $woocommerce_loop;

  $upsell = $product->get_upsell_ids();

	if ( count( $upsell ) == 0 ) {
	  return;
	}
	/**
	*
	*	Filters the arguments for the related products query.
	*
	*	@param array $args An array of arguments for the related products query.
	*
	*	@return array $args
	*
	* @since 1.0.0
	*
	*/
  $args = apply_filters( 'woocommerce_related_products_args', array(
	  'post_type'            => 'product',
	  'ignore_sticky_posts'  => 1,
	  'no_found_rows'        => 1,
	  'posts_per_page'       => 10,
	  'post__in'             => $upsell,
	  'post__not_in'         => array( $product->get_id() )
  ) );

  $products = new WP_Query( $args );

  $woocommerce_loop['name'] = 'upsells';
	/**
	 * Sets the number of columns for upsell products.
	 *
	 * @var int $woocommerce_loop['columns'] Number of columns for upsell products.
	 *
	 * @var int $columns Number of columns for upsell products (default: 10).
	 *
	 * @since 1.0.0
	 */
  $woocommerce_loop['columns'] = apply_filters( 'woocommerce_upsell_display_columns', 10 );

	if ( $products->have_posts() ) :
		?>
  <div class="skolka-related-products">
    <?php
    $upsell_products_title = skolka_get_option('upsell_products_title');
    if ( ! empty( $upsell_products_title ) ) {
      echo '<h2>' . esc_html( $upsell_products_title ) . '</h2>';
    }
    ?>
    <?php
    $upsell_products_subtitle = skolka_get_option('upsell_products_subtitle');
    if ( ! empty( $upsell_products_subtitle ) ) {
      echo '<h5>' . esc_html( $upsell_products_subtitle ) . '</h5>';
    }
    ?>
	  <div class="swiper-carousel woocommerce" data-column="4" data-loop="false" data-drag="true">
		<div class="swiper">
		  <div class="swiper-wrapper">
				<?php
				while ( $products->have_posts() ) :
$products->the_post();
					?>
				  <div class="swiper-slide">
					<ul class="products">
					  <?php wc_get_template_part( 'content', 'product' ); ?>
					</ul>
				  </div>
			  <?php endwhile; ?>
			</div>
			<div class="slider-button-prev">
		<svg width="24" height="24" viewBox="0 0 24 24" fill="#ffffff">
		  <path d="M14.7105 17.2998C14.3205 17.6898 13.6905 17.6898 13.3005 17.2998L8.71047 12.7098C8.32047 12.3198 8.32047 11.6898 8.71047 11.2998L13.3005 6.70979C13.6905 6.31979 14.3205 6.31979 14.7105 6.70979C15.1005 7.09979 15.1005 7.72978 14.7105 8.11979L10.8305 12.0098L14.7105 15.8898C15.1005 16.2798 15.0905 16.9198 14.7105 17.2998Z"></path>
		</svg>
	  </div>
	  <div class="slider-button-next">
		<svg width="24" height="24" viewBox="0 0 24 24" fill="#ffffff">
		  <path d="M8.70995 6.70998C9.09995 6.31998 9.72995 6.31998 10.1199 6.70998L14.7099 11.3C15.0999 11.69 15.0999 12.32 14.7099 12.71L10.1199 17.3C9.72995 17.69 9.09995 17.69 8.70995 17.3C8.31995 16.91 8.31995 16.28 8.70995 15.89L12.5899 12L8.70995 8.11998C8.31995 7.72998 8.32995 7.08998 8.70995 6.70998Z"></path>
		</svg></div>
		  </div>
		<div class="swiper-pagination"></div>
			</div>
		</div>
	<?php
  endif;

  wp_reset_postdata();
}
add_filter( 'woocommerce_upsell_display_args', 'skolka_custom_upsell_products', 20 );





// UPSELL POSITION BELOW CART
remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display' );
add_action( 'woocommerce_after_cart_table', 'woocommerce_cross_sell_display' );


// WOO HIDE INLINE JS
add_filter( 'body_class', function( $classes) {
	if (in_array('woocommerce-no-js', $classes)) {
		remove_action( 'wp_footer', 'wc_no_js' );
		$classes   = array_diff($classes, array('woocommerce-no-js'));
		$classes[] = 'woocommerce-js';
	}
	return array_values($classes);
}, 10, 1);
