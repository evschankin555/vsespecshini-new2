<?php
add_action( 'widgets_init', 'register_widgets' );
function register_widgets()
{
	register_sidebar( array(
		'name'          => esc_html__( 'Shop Widget Area After Header', 'tools' ),
		'id'            => 'shop-widget-area-after-header',
		'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'tools' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h2 class="widgettitle">',
		'after_title'   => '<span class="arow"></span></h2>',
	));

	register_sidebar( array(
		'name'          => esc_html__( 'Shop Widget Area After Main', 'tools' ),
		'id'            => 'shop-widget-area-after-main',
		'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'tools' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h2 class="widgettitle">',
		'after_title'   => '<span class="arow"></span></h2>',
	));
}

add_action( 'wp_enqueue_scripts', 'tool_child_enqueue_styles' );
function tool_child_enqueue_styles()
{
	wp_enqueue_style( 'parent-style', get_theme_file_uri( '/style.css' ) );
}
if($_SERVER['REQUEST_URI'] == '/')
{
	wp_enqueue_style( 'js_composer_front', get_theme_file_uri( '/css/js_composer.min-for_frontpage.css' ) );
}

wp_register_script( 'base.js', get_theme_file_uri('/js/base.js'), array( 'jquery' ), null, true );
wp_enqueue_script( 'base.js' );

add_filter('wpmeteor_exclude', function($d, $e)
{
	if(is_admin() || current_user_can('manage_options')) { return true; }
	$r = ['wp-includes/js/jquery/jquery.js', 'clearfy', 'ovic_megamenu_frontend', 'ovic-toolkit/includes/extends/megamenu/assets/js/megamenu-frontend.js', 'vsespecshini-search-modul/inc/base/vsespecshini-scripts.js'];
	if(is_front_page()) {
		array_push($r, 'ovic-toolkit/includes/frontend/assets/js/libs/slick.min.js', 'ovic-toolkit/includes/frontend/assets/js/frontend.min.js');
	}
	if(is_product_category() || is_tax() || is_shop()) {
		array_push($r, 'wp-includes/js/jquery/ui/widget.min.js', 'wp-includes/js/jquery/ui/slider.min.js', 'wp-includes/js/jquery/ui/mouse.min.js', 'the_ajax_script', 'woocommerce_price_slider_params', 'woocommerce-ajax-filters/assets/frontend/js/main.min.js', 'woocommerce-ajax-filters/template_styles/js/ion.rangeSlider.min.js', 'woocommerce-ajax-filters/assets/frontend/js/select2.min.js', 'woocommerce/assets/js/accounting/accounting.min.js', 'woocommerce/assets/js/frontend/price-slider.min.js', 'woocommerce/assets/js/jquery-ui-touch-punch/jquery-ui-touch-punch.min.js');
	}
	foreach ($r as $k)
	{
		if(false !== strpos($e, $k)) { return true; }
	}
	return $d;
}, null, 2);

remove_action( 'woocommerce_variable_add_to_cart', 'woocommerce_variable_add_to_cart', 30 );
remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10);
if(is_admin())
{
	remove_action('admin_init', '_maybe_update_core');
	remove_action('admin_init', '_maybe_update_themes');
	remove_action('admin_init', '_maybe_update_plugins');
	remove_action('load-themes.php', 'wp_update_themes');
	remove_action('load-plugins.php', 'wp_update_plugins');
	add_filter('pre_site_transient_browser_'. md5($_SERVER['HTTP_USER_AGENT']), '__return_empty_array');
}
remove_action('wp_head', 'wp_resource_hints', 2);
remove_action('wp_head', 'wp_shortlink_wp_head');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'rest_output_link_wp_head', 10);
remove_action('wp_head', 'wp_oembed_add_discovery_links', 10);
#remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
#add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_title', 5 );

#remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
#add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_title', 5 );

#remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
#add_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_single_title', 5 );


//add_action( 'table_variable', 'DuidTableVariable', 30 );
/*add_filter( 'wpseo_metadesc', 'set_wpseo_metadesc' );
add_filter( 'wpseo_title', 'set_wpseo_title' );
add_filter( 'woocommerce_get_breadcrumb', 'change_breadcrumb' );
*/

add_filter( 'wpseo_metadesc', 'set_wpseo_metadesc' );
add_filter( 'wpseo_title', 'set_wpseo_title' );
add_filter( 'woocommerce_get_breadcrumb', 'change_breadcrumb' );


//remove_filter('woocommerce_page_title', 'woocommerce_page_title', 10, 2);

#            remove_filter('the_title', array($this, 'the_title'), 10, 2);
 #           remove_filter('woocommerce_page_title', array($this, 'woocommerce_page_title'), 10, 2);

add_filter('woocommerce_page_title', 'set_page_title', 10, 2);
add_filter( 'woocommerce_get_breadcrumb', 'woocommerce_get_breadcrumb' );

function woocommerce_get_breadcrumb( $crumbs )
{
	foreach ($crumbs as &$crumb)
	{
		$crumb[0] = set_page_title($crumb[0]);
	}

	return $crumbs;
}



#add_action('woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );

/*function woocommerce_template_loop_product_title()
{
	echo '<h3 class="product-name product_title"><a href="'.get_the_permalink().'">' . get_the_title() . '</a></h3>';
}*/



function DuidTableVariable()
{
	global $woocommerce, $product, $post;


		$head_rus = array(
			'tyre_width'	=> 'Ширина',
			'tyre_profile'	=> 'Профиль',
			'tyre_rim'	=> 'Диаметр',
			'load_index' 	=> 'Индекс нагрузки',
			'speed_index' 	=> 'Индекс скорости',
		);

		$temp_head 	= false;
		$temp_body 	= false;

	if($product->is_type( 'variable' ))
	{
		$varattr 	= $product->get_variation_attributes();

		$temp_head['name'] = "Наименование";



		$temp_head['price'] = "Цена";
		$temp_head['tocart'] = "На складе";


		$available_variations = $product->get_children();

		$temp_body		= array();


		foreach ($available_variations as $key => $vid)
		{
			$value['variation_id'] = $vid;

			$product_variation = new WC_Product_Variation($value['variation_id']);
#			$product_variation = new WC_Product_Variation($value);
			$url 		= $product_variation->get_permalink(  );
			$in_stock 	= (bool)$value['is_in_stock'];

			if($in_stock)
			{
				$stock = 'instock';
			}else	$stock = 'outstock';

			$temp_body[$stock][$value['variation_id']]['name'] 		= '<a href="'.$url.'">'.$product_variation->get_description().'</a>';
			$temp_body[$stock][$value['variation_id']]['price'] 		= $product_variation->get_price_html();
			$temp_body[$stock][$value['variation_id']]['is_in_stock'] 	= $value['is_in_stock'];

			if($value['is_in_stock'])
			{
				$temp_body[$stock][$value['variation_id']]['tocart'] = '<form class="cart" action="'.esc_url( $product->add_to_cart_url()).'" method="post" enctype=\'multipart/form-data\'>';

				if(!empty($value['attributes']))
				{
					foreach ($value['attributes'] as $attr_key => $attr_value)
					{
						$temp_body[$stock][$value['variation_id']]['tocart'] .='<input type="hidden" name="'.$attr_key.'" value="'.$attr_value.'">';
					}
				}

				$temp_body[$stock][$value['variation_id']]['tocart'] .= '<button type="submit" class="button product_type_simple  single_add_to_cart_button  add_to_cart_button ">'.esc_html( $product->single_add_to_cart_text() ).'</button>'.
					'<input type="hidden" name="variation_id" value="'.$value['variation_id'].'" />'.
					'<input type="hidden" name="product_id" value="'.$value['variation_id'].'" />'.
					'<input type="hidden" name="add-to-cart" value="'.$value['variation_id'].'" />'.
					'<input type="hidden" name="quantity" value="1" />';
				$temp_body[$stock][$value['variation_id']]['tocart'] .= '</form>';
			} else {
				$temp_body[$stock][$value['variation_id']]['tocart'] = '<p class="stock out-of-stock">'.__( 'Нет в наличии', 'woocommerce' ).'</p>';
			}
		}

		$out .= '<style>
			ul.tabs{
				position: relative;
				display: flex;
				list-style: none;
				width: 100%;
				padding: 12px 0;
			}
		</style>


		<div class="woocommerce-tabs wc-tabs-wrapper">
			<ul class="tabs wc-tabs" role="tablist">
				<li class="instock_tab" id="tab-title-instock" role="tab" aria-controls="tab-instock">
					<a href="#tab-instock">Размеры в наличии('.count($temp_body['instock']).')</a>
				</li>
				<li class="outstock_tab" id="tab-title-outstock" role="tab" aria-controls="tab-outstock">
					<a href="#tab-outstock">Нет в наличии('.count($temp_body['outstock']).')</a>
				</li>
			</ul>

			<div class="woocommerce-Tabs-panel woocommerce-Tabs-panel--instock panel entry-content wc-tab" id="tab-instock" role="tabpanel" aria-labelledby="tab-title-instock">
				<div id="instock">';


		if(isset($temp_body['instock']))
		{
			$out .= '<table id="instock"><thead><tr><td>'.implode("</td><td>", $temp_head).'</td></tr></thead><tbody>';

			$allowed = array_keys($temp_head);

			foreach($temp_body['instock'] as $td)
			{
				uksort($td, function($a, $b) use ($allowed)
				{
					return array_search($a, $allowed) - array_search($b, $allowed);
				});

				$is_in_stock = $td['is_in_stock'];
				unset($td['is_in_stock']);

				if($is_in_stock)
				{
					$out .="<tr><td>".implode("</td><td>", $td)."</td></tr>";
				}else	$out .="<tr style='opacity:0.4;'><td>".implode("</td><td>", $td)."</td></tr>";
			}

			$out .= '</tbody></table>';
		}

		$out .='
			<div class="clear"></div>
		</div>
	</div>

	<div class="woocommerce-Tabs-panel woocommerce-Tabs-panel--outstock panel entry-content wc-tab" id="tab-outstock" role="tabpanel" aria-labelledby="tab-title-outstock">
		<div id="outstock">';



		if(isset($temp_body['outstock']))
		{
			$out .= '<table id="outstock" style=""><thead><tr><td>'.implode("</td><td>", $temp_head).'</td></tr></thead><tbody>';


			$allowed = array_keys($temp_head);

			foreach($temp_body['outstock'] as $td)
			{
				uksort($td, function($a, $b) use ($allowed)
				{
					return array_search($a, $allowed) - array_search($b, $allowed);
				});

				$is_in_stock = $td['is_in_stock'];
				unset($td['is_in_stock']);

				if($is_in_stock)
				{
					$out .="<tr><td>".implode("</td><td>", $td)."</td></tr>";
				}else	$out .="<tr style='opacity:0.4;'><td>".implode("</td><td>", $td)."</td></tr>";
			}

			$out .= '</tbody></table>';
		}

echo		$out .= '<div class="clear"></div>
				</div>
			</div>
		</div>';

	}else
	{
		$variable		= getVariableID($post->ID);

		if($variable == 0)
		{
			$variable		= $post->ID;
		}

		$product	    	= wc_get_product( $post->ID );

			$product_variation	= new WC_Product_Variation($variable);

		$varattr 		= $product_variation->get_variation_attributes();
		$GlobalStock    	= $product->get_manage_stock();

		if($product_variation->stock_status == 'outofstock')
		{
			$stock = 'outstock';
		}else	$stock = 'instock';




		$temp_head['name'] 	= "Наименование";
		$temp_head['price'] 	= "Цена";
		$temp_head['tocart'] 	= "На складе";

		$temp_body		= array();



		$temp_body[$stock][$variable]['name'] = $product_variation->get_description();

		if(empty($temp_body[$stock][$variable]['name']))
		{
			$temp_body[$stock][$variable]['name'] = $product_variation->name;
		}
		$temp_body[$stock][$variable]['price'] = $product_variation->get_price_html();


		if($GlobalStock AND $product_variation->stock_status == 'outofstock')
		{
			$temp_body[$stock][$variable]['is_in_stock'] = false;
		}else
		{
			$temp_body[$stock][$variable]['is_in_stock'] = true;
		}

		if(!$product->managing_stock() AND $product_variation->stock_status == 'outofstock')
		{
			$temp_body[$stock][$variable]['tocart'] = '<p class="stock out-of-stock">'.__( 'Нет в наличии', 'woocommerce' ).'</p>';
		}else
		{
			$temp_body[$stock][$variable]['tocart'] = '<form class="cart" action="'.esc_url( $product->add_to_cart_url()).'" method="post" enctype=\'multipart/form-data\'>';


			$temp_body[$stock][$variable]['tocart'] .= '<button type="submit" class="button product_type_simple  single_add_to_cart_button  add_to_cart_button ">'.esc_html( $product->single_add_to_cart_text() ).'</button>'.
			'<input type="hidden" name="variation_id" value="'.$variable.'" />'.
				'<input type="hidden" name="product_id" value="'.$variable.'" />'.
				'<input type="hidden" name="add-to-cart" value="'.$variable.'" />'.
				'<input type="hidden" name="quantity" value="1" />';
			$temp_body[$stock][$variable]['tocart'] .= '</form>';
		}


$out .= '<style>
ul.tabs{
	position: relative;
	display: flex;
	list-style: none;
	width: 100%;
	padding: 12px 0;
}
</style>


<div class="woocommerce-tabs wc-tabs-wrapper">
	<ul class="tabs wc-tabs" role="tablist">
		<li class="instock_tab" id="tab-title-instock" role="tab" aria-controls="tab-instock">
			<a href="#tab-instock">Размеры в наличии('.count($temp_body['instock']).')</a>
		</li>
		<li class="outstock_tab" id="tab-title-outstock" role="tab" aria-controls="tab-outstock">
			<a href="#tab-outstock">Нет в наличии('.count($temp_body['outstock']).')</a>
		</li>
	</ul>

	<div class="woocommerce-Tabs-panel woocommerce-Tabs-panel--instock panel entry-content wc-tab" id="tab-instock" role="tabpanel" aria-labelledby="tab-title-instock">
		<div id="instock">';


		if(isset($temp_body['instock']))
		{
			$out .= '<table id="instock"><thead><tr><td>'.implode("</td><td>", $temp_head).'</td></tr></thead><tbody>';

			$allowed = array_keys($temp_head);

			foreach($temp_body['instock'] as $td)
			{
				uksort($td, function($a, $b) use ($allowed)
				{
					return array_search($a, $allowed) - array_search($b, $allowed);
				});

				$is_in_stock = $td['is_in_stock'];
				unset($td['is_in_stock']);

				if($is_in_stock)
				{
					$out .="<tr><td>".implode("</td><td>", $td)."</td></tr>";
				}else	$out .="<tr style='opacity:0.4;'><td>".implode("</td><td>", $td)."</td></tr>";
			}
			$out .= '</tbody></table>';
		}

		$out .='
			<div class="clear"></div>
		</div>
	</div>

	<div class="woocommerce-Tabs-panel woocommerce-Tabs-panel--outstock panel entry-content wc-tab" id="tab-outstock" role="tabpanel" aria-labelledby="tab-title-outstock">
		<div id="outstock">';



		if(isset($temp_body['outstock']))
		{
			$out .= '<table id="outstock" style=""><thead><tr><td>'.implode("</td><td>", $temp_head).'</td></tr></thead><tbody>';


			$allowed = array_keys($temp_head);

			foreach($temp_body['outstock'] as $td)
			{
				uksort($td, function($a, $b) use ($allowed)
				{
					return array_search($a, $allowed) - array_search($b, $allowed);
				});

				$is_in_stock = $td['is_in_stock'];
				unset($td['is_in_stock']);

				if($is_in_stock)
				{
					$out .="<tr><td>".implode("</td><td>", $td)."</td></tr>";
				}else	$out .="<tr style='opacity:0.4;'><td>".implode("</td><td>", $td)."</td></tr>";
			}

			$out .= '</tbody></table>';
		}

echo		$out .= '<div class="clear"></div>
				</div>
			</div>
		</div>';
	}
}
###################################

$TemplateMeta = [
	"variable_shini_wpseo_title"	=> "#%category_clean%# #%title%# #%page%# размер: #%pa_tyre_width%#/#%pa_tyre_profile%# #%pa_tyre_rim%# #%pa_load_index%# #%pa_speed_index%# цена #%price%# руб",
	"variable_shini_product_title"	=> "#%title%# #%pa_tyre_width%#/#%pa_tyre_profile%# #%pa_tyre_rim%# #%pa_load_index%# #%pa_speed_index%#",
	"variable_shini_wpseo_desc"	=> "#%category_clean%# #%title%#  размер: #%pa_tyre_width%#/#%pa_tyre_profile%# #%pa_tyre_rim%#  #%pa_load_index%# #%pa_speed_index%#  для спецтехники. Цена  #%title%# , отзывы о шине. Купить шины #%title%# в Москве и доставкой в города России.",


	"shini_wpseo_title"		=> "#%category_clean%# #%title%# цена от #%price%# руб",
	"shini_product_title"		=> "#%title%# цена от #%price%# руб",
	"shini_wpseo_desc"		=> "#%category_clean%# #%title%#  для спецтехники. Цена от #%title%# , отзывы о шине. Купить шины #%title%# в Москве и доставкой в города России.",

	"shini_wpseo_page_title_sort"	=> " #%pa_tyre_width%#/#%pa_tyre_profile%# #%pa_tyre_rim%# #%pa_load_index%# #%pa_speed_index%#",




	"variable_camera_wpseo_title"	=> "Камеры #%title%# размер: #%pa_tyre_width%#/#%pa_tyre_profile%# #%pa_tyre_rim%# цена #%price%# руб",
	"variable_camera_product_title"	=> "#%title%# #%pa_tyre_width%#/#%pa_tyre_profile%# #%pa_tyre_rim%# #%pa_load_index%# #%pa_speed_index%#",
	"variable_camera_wpseo_desc"	=> "#%category_clean%# #%title%#  размер: #%pa_tyre_width%#/#%pa_tyre_profile%# #%pa_tyre_rim%# #%pa_load_index%# #%pa_speed_index%# для спецтехники. Цена  #%title%# , отзывы о шине. Купить шины #%title%# в Москве и доставкой в города России.",

	"camera_wpseo_title"	=> "Камеры #%title%# размер: #%pa_tyre_width%#/#%pa_tyre_profile%# #%pa_tyre_rim%# цена #%price%# руб",
	"camera_product_title"	=> "#%title%# #%pa_tyre_width%#/#%pa_tyre_profile%# #%pa_tyre_rim%# #%pa_load_index%# #%pa_speed_index%#",
	"camera_wpseo_desc"	=> "#%category_clean%# #%title%#  размер: #%pa_tyre_width%#/#%pa_tyre_profile%# #%pa_tyre_rim%# #%pa_load_index%# #%pa_speed_index%# для спецтехники. Цена  #%title%# , отзывы о шине. Купить шины #%title%# в Москве и доставкой в города России."

];


function find_matching_product_variation_id($product_id, $match_attributes )
{
	if(isset($_GET['pid']) AND is_numeric($_GET['pid']) AND $_GET['pid'] > 0)
	{
		return (int)$_GET['pid'];
	}

	return (new \WC_Product_Data_Store_CPT())->find_matching_product_variation(new \WC_Product($product_id), $match_attributes);
}


		/**
		 * Hook: woocommerce_single_product_summary.
		 *
		 * @hooked woocommerce_template_single_title - 5
		 * @hooked woocommerce_template_single_rating - 10
		 * @hooked woocommerce_template_single_price - 10
		 * @hooked woocommerce_template_single_excerpt - 20
		 * @hooked woocommerce_template_single_add_to_cart - 30
		 * @hooked woocommerce_template_single_meta - 40
		 * @hooked woocommerce_template_single_sharing - 50
		 * @hooked WC_Structured_Data::generate_product_data() - 60
		 */
#		do_action( 'woocommerce_single_product_summary' );
function woocommerce_template_single_price()
{
	global $post, $product;

	$attr = [];

	if($_GET)
	{
		foreach($_GET as $key => $value)
		{
			if(strpos($key, 'attribute_pa_') === 0)
			{
				$attr[$key] = sanitize_text_field($value);
			}
		}
	}

	$Data = find_matching_product_variation_id($post->ID, $attr);

	if($Data)
	{
		$Data = is_array($Data)?$Data:array($Data);
		$product = wc_get_product( $Data[0] );
	}
?>
	<p class="<?php echo esc_attr( apply_filters( 'woocommerce_product_price_class', 'price' ) );?>"><?php echo $product->get_price_html(); ?></p>
<?php
}
function woocommerce_template_single_title()
{
	global $post, $TemplateMeta;

	if(is_product())
	{
		$prefix 	= '';
		$variableID 	= getVariableID($post->ID);


		if($variableID > 0)
		{
			$prefix = 'variable_';
		}
	}


	$title = stripslashes( $post->post_title );

	if( is_product() AND has_term( 'shini', 'product_cat' ) )
	{
		$title = du_product_title($TemplateMeta[$prefix.'shini_product_title']);
	}

	if( is_product() AND has_term( 'camera', 'product_cat' ) )
	{
		$title = du_product_title($TemplateMeta[$prefix.'camera_product_title']);
	}

	echo "<h1 class=\"product_title entry-title\">{$title}</h1>";
}

function set_wpseo_metadesc($wpseo_replace_vars)
{
	global $TemplateMeta, $post;

	if(is_product())
	{
		$prefix 	= '';
		$variableID 	= getVariableID($post->ID);


		if($variableID > 0)
		{
			$prefix = 'variable_';
		}
	}

	if( is_product() AND has_term( 'shini', 'product_cat' ) )
	{
		$wpseo_replace_vars = du_wpseo_metadesc($TemplateMeta[$prefix.'shini_wpseo_desc']);
	}

	if( is_product() AND has_term( 'camera', 'product_cat' ) )
	{
		$wpseo_replace_vars = du_wpseo_metadesc($TemplateMeta[$prefix.'camera_wpseo_desc']);
	}

	if(is_category() OR is_archive())
	{
		$wpseo_replace_vars = set_page_title($wpseo_replace_vars);
	}

	return $wpseo_replace_vars;
}

function du_wpseo_metadesc($template)
{
	global $post;

	$replacements 	= [];
#	$product 	= wc_get_product( $post );

	$replacements = du_Replace($template, $post);


	if ( is_array( $replacements ) && $replacements !== [] ) {
		$template = str_replace( array_keys( $replacements ), array_values( $replacements ), $template );
	}

	return $template;
}

function getVariableID($ID)
{
	$attr = [];

	if($_GET)
	{
		foreach($_GET as $key => $value)
		{
			if(strpos($key, 'attribute_pa_') === 0)
			{
				$attr[$key] = sanitize_text_field($value);
			}
		}
	}

	return	find_matching_product_variation_id($ID, $attr);
}

function set_page_title($title)
{
	global $berocket_parse_page_obj, $TemplateMeta;


	$data = $berocket_parse_page_obj->get_current();

	$terms_name 	= [];
	$replacements 	= [];


	if(isset($data['filters']) && is_array($data['filters']))
	{
				foreach($data['filters'] as $filters)
		{
			foreach($filters['terms'] as $filter)
			{
				$taxonomy = $filter->taxonomy;
				$attr[$taxonomy] = $filter->name;
			}
		}
	}

	$template = $title;

//	$template = $TemplateMeta['shini_wpseo_page_title_sort'];

	if ( preg_match_all( '`#%(.*)%#`isU', $template, $matches ) )//(%%single)?
	{
		foreach($matches[1] as $k => $var)
		{
			$varExp = explode("%", $var);
			$val	= $varExp[0];
			//$var = $varExp[0];

			if(isset($attr[$val]))
			{
				$replacements[ "#%".$var."%#" ] = $attr[$val].(isset($varExp[1])?$varExp[1]:'');
				$replacement = $attr[$val];

			}else $replacements[ "#%".$var."%#" ] = '';

//var_dump($replacements);

		}
	}

#var_dump($replacements);

	if(is_array( $replacements ) && $replacements !== [])
	{
		$template = str_replace( array_keys( $replacements ), array_values( $replacements ), $template );

/*		preg_match("#(.*)/ *?$#isu", $template, $match);

		if(isset($match[1]))
		{
			$template = $match[1];
		}*/
	}

	return $template;
}

function set_wpseo_title($title)
{
	global $TemplateMeta, $post;


	if(is_product())
	{
		$prefix 	= '';
		$variableID 	= getVariableID($post->ID);


		if($variableID > 0)
		{
			$prefix = 'variable_';
		}
	}


#	$product 	= wc_get_product( $post );

	if($variableID > 0)
	{
		$prefix = 'variable_';
	}


	if( is_product() AND has_term( 'shini', 'product_cat' ) )
	{
		$title = du_wpseo_title($TemplateMeta[$prefix.'shini_wpseo_title']);
	}


	if( is_product() AND has_term( 'camera', 'product_cat' ) )
	{
		$title = du_wpseo_title($TemplateMeta[$prefix.'camera_wpseo_title']);
	}

	if(is_category() OR is_archive())
	{
		$title = set_page_title($title);
	}

	$title = esc_html( wp_strip_all_tags( stripslashes( $title ), true ) );

	return $title;
}

function du_wpseo_title($template)
{
	global $post;

	$replacements 	= [];
#	$product 	= wc_get_product( $post );

	$replacements = du_Replace($template, $post);

	if ( is_array( $replacements ) && $replacements !== [] ) {
		$template = str_replace( array_keys( $replacements ), array_values( $replacements ), $template );
	}

	return $template;
}

function du_product_title($template)
{
	global $post;

	$replacements = [];
#	$product 	= wc_get_product( $post );

	$replacements = du_Replace($template, $post);

	if ( is_array( $replacements ) && $replacements !== [] ) {
		$template = str_replace( array_keys( $replacements ), array_values( $replacements ), $template );
	}

	return $template;
}

function du_Replace($template, $post)
{
	$postID 	= $post->ID;
	$variableID 	= getVariableID($post->ID);

	if($variableID > 0)
	{
		$postID = $variableID;
	}


	if ( preg_match_all( '`#%(.*)%#`isU', $template, $matches ) )//(%%single)?
	{
		foreach($matches[1] as $k => $var)
		{
			switch($var)
			{
				case 'title':
					$replacement = stripslashes( $post->post_title );
				break;
				case 'category':
					$terms = get_the_terms ( $postID, 'product_cat' );
					$replacement = stripslashes( $terms[1]->name );
				break;
				case 'category_clean':
					$terms = get_the_terms ( $postID, 'product_cat' );
					$replacement = cleanReplace(stripslashes( $terms[1]->name ));
				break;
				case 'price':
					$product = wc_get_product( $postID );
					$replacement = $product->get_price();
				break;


				default:
					$terms = get_the_terms( $postID, $var );

					if( !is_wp_error( $terms ) )
					{
						if($terms[0]->name)
						{
							$replacement = $terms[0]->name;
						}else	$replacement = '';

					}else	$replacement = '';
				break;

			}
#				$replacements[ "%%".$var."%%" ] = $replacement;

			if ( isset( $replacement ) ) {
				$replacements[ "#%".$var."%#" ] = $replacement;
			}
			unset( $replacement);
		}
	}
	return $replacements;
}

function cleanReplace($string)
{
	return trim(preg_replace('`#%(.*)%#`isU', '', $string));
}

function change_breadcrumb( $crumbs )
{
	if(!is_product())
	{
		return $crumbs;
	}

	end($crumbs);
	$key = key($crumbs);
	reset($crumbs);

	$crumbs[$key][0] = du_product_title($crumbs[$key][0]);

	return $crumbs;
}

// remove the filter 
/*remove_filter( 'woocommerce_products_widget_query_args', 'filter_woocommerce_products_widget_query_args', 10, 1 );

add_filter( 'woocommerce_products_widget_query_args', 'filter_function_name_4175', 10, 1 );

function filter_function_name_4175( $query_args )
{
#echo "";
	$product_visibility_term_ids = wc_get_product_visibility_term_ids();
echo "$$$$###";
var_dump($product_visibility_term_ids);
#       $query_args['tax_query'][] = array(array('taxonomy' => 'product_visibility', 'field' => 'term_taxonomy_id', 'terms' => $product_visibility_term_ids['outofstock'], 'operator' => 'NOT IN'));
#echo "$$$$###";var_dump($query_args);

		$product_ids_on_sale = wc_get_product_ids_on_sale();
#             $product_ids_on_sale[] = 0;
#             $query_args['post__in'] = $product_ids_on_sale;
	$query_args['post__in'] = 8043;

var_dump($query_args);

	return $query_args;
}
   */
/*add_filter( 'woocommerce_products_widget_query_args', function( $query_args ){
	// Set HERE your product category slugs
	$categories = array( 'music', 'posters' );

	$query_args['tax_query'] = array( array(
		'taxonomy' => 'product_cat',
		'field'    => 'slug',
		'terms'    => $categories,
	));

	return $query_args;
}, 10, 1 );*/

#*remove_filter('woocommerce_product_query_tax_query', '', 10);
/*	add_filter('woocommerce_product_query_tax_query', function($tax_query, $_this)
	{
		$product_visibility_term_ids = wc_get_product_visibility_term_ids();
#var_dump($product_visibility_term_ids);

				foreach ($tax_query as $key => $tax)
		{
			if (isset($tax['taxonomy']) AND $tax['taxonomy'] == 'product_visibility')
			{
							unset($tax_query[$key]);

#       $tax_query[$key] = array('taxonomy' => 'product_visibility', 'field' => 'term_taxonomy_id', 'terms' => $product_visibility_term_ids['outofstock'], 'operator' => 'NOT IN');


			}
		}




#       $tax_query['product_visibility'] = array('taxonomy' => 'product_visibility', 'field' => 'term_taxonomy_id', 'terms' => $product_visibility_term_ids['outofstock'], 'operator' => 'NOT IN');

#var_dump($tax_query);


#                $tax_query = $this->product_visibility_not_in($tax_query, $this->generate_visibility_keys(true));
				return $tax_query;
	}, 10, 2);*/


/*add_action( 'pre_get_posts', 'iconic_hide_out_of_stock_products2' );

function iconic_hide_out_of_stock_products2( $q ) {

	if ( ! $q->is_main_query() ) return;
	if ( ! $q->is_post_type_archive() ) return;
	if ( is_admin() ) return;

	$meta_query = (array) $q->get('meta_query');

	$meta_query[] = array(
		'key'       => '_stock_status',
		'value'     => 'outofstock',
		'compare'   => 'NOT IN'
	);

	$q->set( 'meta_query', $meta_query );

	remove_action( 'pre_get_posts', 'iconic_hide_out_of_stock_products' );

}*/


#add_action( 'pre_get_posts', 'iconic_hide_out_of_stock_products' );

/*function iconic_hide_out_of_stock_products( $q )
{
	$continue = false;

	if($_GET)
	{
		foreach($_GET as $key => $value)
		{
			if(strpos($key, 'filter_tyre_') === 0)
			{
				$continue = true;
			}
		}
	}
#echo '5555';

#	if($continue == false)
#	{
#		return;
#	}

#	if ( ! $q->is_main_query() ) return;
#	if ( ! $q->is_post_type_archive() ) return;
#	if ( is_admin() ) return;

	$meta_query = (array) $q->get('meta_query');

	$meta_query[] = array(
		'key'       => '_stock_status',
		'value'     => 'outofstock',
		'compare'   => 'NOT IN'
	);

	$q->set( 'meta_query', $meta_query );

	remove_action( 'pre_get_posts', 'iconic_hide_out_of_stock_products' );
}


															   */
/*add_action( 'pre_get_posts', 'iconic_hide_out_of_stock_products' );

function iconic_hide_out_of_stock_products( $q ) {

	if ( ! $q->is_main_query() || is_admin() ) {
		return;
	}

	if ( $outofstock_term = get_term_by( 'name', 'outofstock', 'product_visibility' ) ) {

		$tax_query = (array) $q->get('tax_query');

		$tax_query[] = array(
			'taxonomy' => 'product_visibility',
			'field' => 'term_taxonomy_id',
			'terms' => array( $outofstock_term->term_taxonomy_id ),
			'operator' => 'NOT IN'
		);

		$q->set( 'tax_query', $tax_query );

	}

	remove_action( 'pre_get_posts', 'iconic_hide_out_of_stock_products' );

} */

add_filter( 'woocommerce_product_query_meta_query', 'filter_product_query_meta_query', 10, 2 );

function filter_product_query_meta_query( $meta_query, $query )
{
	$continue = false;

	if($_GET)
	{
		foreach($_GET as $key => $value)
		{
			if(strpos($key, 'filter_tyre_') === 0)
			{
				$continue = true;
			}
		}
	}

	if($continue === true)
	{
			$meta_query[] = array(
				'key'     => '_stock_status',
				'value'   => 'outofstock',
				'compare' => '!=',
			);
	}


	return $meta_query;
}


/*if($_POST['action'] == 'yith_load_product_quick_view')
{
$_POST['product_id'] = "12703";
var_dump($_POST);
}
  */

#			remove_action( 'woocommerce_product_thumbnails', 'woocommerce_show_product_thumbnails', 20 );



#add_action( 'wp_ajax_yith_load_product_quick_view', 'tytyty' );
#add_action( 'wp_ajax_nopriv_yith_load_product_quick_view', 'tytyty' );
#add_action( 'wp_ajax_nopriv_yith_load_product_quick_view', array( $this, 'yith_load_product_quick_view_ajax' ) );

add_action('wp_head', function() {
	if (is_front_page()) { ?>
		<link rel="preload" as="image" href="/wp-content/uploads/2019/12/1.jpg?id=3211">
	<?php } ?>
		<link rel="preload" as="image" href="/wp-content/uploads/2023/06/logo8-1-e1688117707815.png">
<?php
}, 1);

class iWC_Orderby_Stock_Status {
public function __construct() {
	if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
		add_filter('posts_clauses', array($this, 'order_by_stock_status'), 2000);
	}
}
public function order_by_stock_status($posts_clauses) {
	global $wpdb;
	if (is_woocommerce() && (is_shop() || is_product_category() || is_product_tag())) {
		$posts_clauses['join'] .= " INNER JOIN $wpdb->postmeta istockstatus ON ($wpdb->posts.ID = istockstatus.post_id) ";
		$posts_clauses['orderby'] = " istockstatus.meta_value ASC, " . $posts_clauses['orderby'];
		$posts_clauses['where'] = " AND istockstatus.meta_key = '_stock_status' AND istockstatus.meta_value <> '' " . $posts_clauses['where'];
	}
	return $posts_clauses;
	}
}
new iWC_Orderby_Stock_Status;



/*add_filter( 'woocommerce_product_tabs', 'sb_woo_remove_reviews_tab', 98);
function sb_woo_remove_reviews_tab($tabs) {

unset($tabs['reviews']);

return $tabs;
} */

add_filter( 'woocommerce_product_tabs', 'woo_new_product_tab' );

function woo_new_product_tab( $tabs )
{
	global $woocommerce, $product, $post;

	$return = false;

	if($product->is_type( 'variable' ))
	{
		$varattr 		= $product->get_variation_attributes();
		$available_variations	= $product->get_children();

		foreach($available_variations as $key => $vid)
		{
			$value['variation_id'] = $vid;

			$product_variation 	= new WC_Product_Variation($value['variation_id']);

			if(($product_variation->managing_stock() AND $product_variation->stock_status !== 'outofstock') OR !$product_variation->managing_stock())
			{
				$return = true;
			}
		}
	}else
	{
		$variable		= getVariableID($post->ID);

		if($variable == 0)
		{
			$variable		= $post->ID;
		}

			$product_variation	= new WC_Product_Variation($variable);


		if(($product_variation->managing_stock() AND $product_variation->stock_status != 'outofstock') OR !$product_variation->managing_stock())
		{
			$return = true;
		}
	}



	if($return === true)
	{
		$tabs['instock'] = array(
			'title' 	=> __( 'В наличии', 'woocommerce' ),
			'priority' 	=> 10,
			'callback' 	=> 'woo_instock_product_tab_content'
		);

		$tabs['outstock'] = array(
			'title' 	=> __( 'Нет в наличии', 'woocommerce' ),
			'priority' 	=> 20,
			'callback' 	=> 'woo_outstock_product_tab_content'
		);

	}else
	{
		$tabs['outstock'] = array(
			'title' 	=> __( 'Нет в наличии', 'woocommerce' ),
			'priority' 	=> 10,
			'callback' 	=> 'woo_outstock_product_tab_content'
		);

		$tabs['instock'] = array(
			'title' 	=> __( 'В наличии', 'woocommerce' ),
			'priority' 	=> 20,
			'callback' 	=> 'woo_instock_product_tab_content'
		);
	}

/*		$tabs['outstock'] = array(
			'title' 	=> __( 'Нет в наличии', 'woocommerce' ),
			'priority' 	=> 20,
			'callback' 	=> 'woo_outstock_product_tab_content'
		);*/


	return $tabs;
}

function woo_instock_product_tab_content()
{
	global $woocommerce, $product, $post;


	$head_rus = array(
		'tyre_width'	=> 'Ширина',
		'tyre_profile'	=> 'Профиль',
		'tyre_rim'	=> 'Диаметр',
		'load_index' 	=> 'Индекс нагрузки',
		'speed_index' 	=> 'Индекс скорости',
	);

	$temp_head 	= false;
	$temp_body 	= array();

	$temp_head['name'] 	= "Наименование";
	$temp_head['price'] 	= "Цена";
	$temp_head['tocart'] 	= "На складе";



	if($product->is_type( 'variable' ))
	{
		$varattr 		= $product->get_variation_attributes();
		$available_variations	= $product->get_children();

		foreach($available_variations as $key => $vid)
		{
			$value['variation_id'] = $vid;

			$product_variation 	= new WC_Product_Variation($value['variation_id']);
			$url 			= add_query_arg ('pid', $value['variation_id'], $product_variation->get_permalink());


//			if($value['is_in_stock'])
#var_dump($product_variation);
#var_dump($product_variation->stock_status());

			if(($product_variation->managing_stock() AND $product_variation->stock_status !== 'outofstock') OR !$product_variation->managing_stock())
			{
				$temp_body[$value['variation_id']]['name'] = '<a href="'. get_custom_product_url($value['variation_id']) .'">'. $product_variation->get_description() .'</a>';
				$temp_body[$value['variation_id']]['price'] 		= $product_variation->get_price_html();
				$temp_body[$value['variation_id']]['is_in_stock'] 	= $value['is_in_stock'];

				$temp_body[$value['variation_id']]['tocart'] = '<form class="cart" action="'.esc_url( $product->add_to_cart_url()).'" method="post" enctype=\'multipart/form-data\'>';

				if(!empty($value['attributes']))
				{
					foreach ($value['attributes'] as $attr_key => $attr_value)
					{
						$temp_body[$value['variation_id']]['tocart'] .='<input type="hidden" name="'.$attr_key.'" value="'.$attr_value.'">';
					}
				}

				$temp_body[$value['variation_id']]['tocart'] .= '<a href="?add-to-cart='.$value['variation_id'].'" class="button product_type_simple add_to_cart_button ajax_add_to_cart">'.esc_html( $product->single_add_to_cart_text() ).'</a>'.
					'<input type="hidden" name="variation_id" value="'.$value['variation_id'].'" />'.
					'<input type="hidden" name="product_id" value="'.$value['variation_id'].'" />'.
					'<input type="hidden" name="add-to-cart" value="'.$value['variation_id'].'" />'.
					'<input type="hidden" name="quantity" value="1" />';
				$temp_body[$value['variation_id']]['tocart'] .= '</form>';
			}
		}
	}else
	{
//echo '555';

		$variable		= getVariableID($post->ID);

		if($variable == 0)
		{
			$variable		= $post->ID;
		}

		$product	    	= wc_get_product( $post->ID );
			$product_variation	= new WC_Product_Variation($variable);

		$varattr 		= $product_variation->get_variation_attributes();
		$GlobalStock    	= $product->get_manage_stock();

		if(($product_variation->managing_stock() AND $product_variation->stock_status != 'outofstock') OR !$product_variation->managing_stock())
		{
			$temp_body[$variable]['name'] = $product_variation->get_description();

			if(empty($temp_body[$variable]['name']))
			{
				$temp_body[$variable]['name'] = $product_variation->name;
			}

			$temp_body[$variable]['price'] = $product_variation->get_price_html();


			if($GlobalStock AND $product_variation->stock_status == 'outofstock')
			{
				$temp_body[$variable]['is_in_stock'] = false;
			}else	$temp_body[$variable]['is_in_stock'] = true;

			$temp_body[$variable]['tocart'] = '<form class="cart" action="'.esc_url( $product->add_to_cart_url()).'" method="post" enctype=\'multipart/form-data\'>';


			$temp_body[$variable]['tocart'] .= '<button type="submit" class="button product_type_simple  single_add_to_cart_button  add_to_cart_button ">'.esc_html( $product->single_add_to_cart_text() ).'</button>'.
			'<input type="hidden" name="variation_id" value="'.$variable.'" />'.
				'<input type="hidden" name="product_id" value="'.$variable.'" />'.
				'<input type="hidden" name="add-to-cart" value="'.$variable.'" />'.
				'<input type="hidden" name="quantity" value="1" />';
			$temp_body[$variable]['tocart'] .= '</form>';
		}
	}

	if($temp_body)
	{
		$out = '<table id="instock"><thead><tr><td>'.implode("</td><td>", $temp_head).'</td></tr></thead><tbody>';

		$allowed = array_keys($temp_head);

		foreach($temp_body as $td)
		{
			uksort($td, function($a, $b) use ($allowed)
			{
				return array_search($a, $allowed) - array_search($b, $allowed);
			});

			$is_in_stock = $td['is_in_stock'];
			unset($td['is_in_stock']);

			if($is_in_stock)
			{
				$out .="<tr><td>".implode("</td><td>", $td)."</td></tr>";
			}else	$out .="<tr><td>".implode("</td><td>", $td)."</td></tr>";
		}

		echo $out .= '</tbody></table>';
	}else
	{
		echo "<h2>Наличие</h2><p>Товаров нет в наличии</p>";
		echo '<script>jQuery(document).ready(function(){jQuery(\'a[href="#tab-outstock"]\').trigger(\'click\')});</script>';
	}
######################
}

function woo_outstock_product_tab_content()
{
	global $woocommerce, $product, $post;


	$head_rus = array(
		'tyre_width'	=> 'Ширина',
		'tyre_profile'	=> 'Профиль',
		'tyre_rim'	=> 'Диаметр',
		'load_index' 	=> 'Индекс нагрузки',
		'speed_index' 	=> 'Индекс скорости',
	);

	$temp_head 	= false;
	$temp_body 	= false;

	$temp_head['name'] 	= "Наименование";
	$temp_head['price'] 	= "Цена";
	$temp_head['tocart'] 	= "На складе";



	if($product->is_type( 'variable' ))
	{
		$varattr 		= $product->get_variation_attributes();
		$available_variations	= $product->get_children();

		foreach($available_variations as $key => $vid)
		{
			$value['variation_id'] = $vid;

			$product_variation 	= new WC_Product_Variation($value['variation_id']);
//			$url 			= $product_variation->get_permalink();
			$url 			= add_query_arg ('pid', $value['variation_id'], $product_variation->get_permalink());
//var_dump($product_variation->managing_stock);
//var_dump($product_variation->managing_stock());
//stock_status
// В вашем шаблоне


//			if(!$value['is_in_stock'])
//$product_variation->managing_stock
			if($product_variation->managing_stock() AND $product_variation->stock_status == 'outofstock')
			{
				$temp_body[$value['variation_id']]['name'] = '<a href="'. get_custom_product_url($value['variation_id']) .'">'. $product_variation->get_description() .'</a>';
#				$temp_body[$value['variation_id']]['name'] 		= $product_variation->get_description();
				$temp_body[$value['variation_id']]['price'] 		= $product_variation->get_price_html();
				$temp_body[$value['variation_id']]['is_in_stock'] 	= $value['is_in_stock'];

				$temp_body[$value['variation_id']]['tocart'] = '<p class="stock out-of-stock">'.__( 'Нет в наличии', 'woocommerce' ).'</p>';
			}
		}

	}else
	{
		$variable		= getVariableID($post->ID);

		if($variable == 0)
		{
			$variable		= $post->ID;
		}

		$product	    	= wc_get_product( $post->ID );
			$product_variation	= new WC_Product_Variation($variable);

		$varattr 		= $product_variation->get_variation_attributes();
		$GlobalStock    	= $product->get_manage_stock();
#var_dump($product_variation->stock_status);

		if($product_variation->managing_stock() AND $product_variation->stock_status == 'outofstock')
#		if(!$product->managing_stock() AND $product_variation->stock_status == 'outofstock')
		{
			$temp_body[$variable]['name'] = $product_variation->get_description();

			if(empty($temp_body[$variable]['name']))
			{
				$temp_body[$variable]['name'] = $product_variation->name;
			}

			$temp_body[$variable]['price'] = $product_variation->get_price_html();


			if($GlobalStock AND $product_variation->stock_status == 'outofstock')
			{
				$temp_body[$variable]['is_in_stock'] = false;
			}else	$temp_body[$variable]['is_in_stock'] = true;

			$temp_body[$variable]['tocart'] = '<p class="stock out-of-stock">'.__( 'Нет в наличии', 'woocommerce' ).'</p>';
		}
	}

	if($temp_body)
	{
		$out = '<table id="outstock"><thead><tr><td>'.implode("</td><td>", $temp_head).'</td></tr></thead><tbody>';

		$allowed = array_keys($temp_head);

		foreach($temp_body as $td)
		{
			uksort($td, function($a, $b) use ($allowed)
			{
				return array_search($a, $allowed) - array_search($b, $allowed);
			});

			$is_in_stock = $td['is_in_stock'];
			unset($td['is_in_stock']);

			if($is_in_stock)
			{
				$out .="<tr><td>".implode("</td><td>", $td)."</td></tr>";
			}else	$out .="<tr style='opacity:0.4;'><td>".implode("</td><td>", $td)."</td></tr>";
		}

		echo $out .= '</tbody></table>';
	}else echo "<h2>Пусто</h2>";
}


/*add_filter( 'woocommerce_product_categories_widget_args', 'widget_arguments' );

function widget_arguments( $args ) {

var_dump($args);

return $args;
} */


add_filter( 'wp_list_categories', 'filter_function_name_7181', 10, 2 );

function filter_function_name_7181( $output, $args )
{
	$output = set_page_title($output);

	return $output;
}

/**Дополнительное описание для категорий Woocommerce **/
add_action( 'product_cat_edit_form_fields', 'wpm_taxonomy_edit_meta_field', 10, 2 );
function wpm_taxonomy_edit_meta_field($term) {
	$t_id = $term->term_id;
	$term_meta = get_option( "taxonomy_$t_id" );
	$content = $term_meta['custom_term_meta'] ? wp_kses_post( $term_meta['custom_term_meta'] ) : '';
	$settings = array( 'textarea_name' => 'term_meta[custom_term_meta]' );
	?>
	<tr class="form-field">
		<th scope="row" valign="top"><label for="term_meta[custom_term_meta]">Дополнительное описание</label></th>
		<td>
			<?php wp_editor( $content, 'product_cat_details', $settings ); ?>
		</td>
	</tr>
	<?php
}
add_action( 'edited_product_cat', 'save_taxonomy_custom_meta', 10, 2 );
add_action( 'create_product_cat', 'save_taxonomy_custom_meta', 10, 2 );
function save_taxonomy_custom_meta( $term_id )
{
	if ( isset( $_POST['term_meta'] ) )
	{
		$t_id = $term_id;
		$term_meta = get_option( "taxonomy_$t_id" );
		$cat_keys = array_keys( $_POST['term_meta'] );

		foreach ( $cat_keys as $key )
		{
			if ( isset ( $_POST['term_meta'][$key] ) )
			{
				$term_meta[$key] = wp_kses_post( stripslashes($_POST['term_meta'][$key]) );
			}
		}
		update_option( "taxonomy_$t_id", $term_meta );
	}
}
/**Вывод второго описания категорий Woocommerce **/
add_action( 'woocommerce_after_shop_loop', 'wpm_product_cat_archive_add_meta', 50 );
function wpm_product_cat_archive_add_meta()
{
	$t_id = get_queried_object()->term_id;
	$term_meta = get_option( "taxonomy_$t_id" );
	$term_meta_content = $term_meta['custom_term_meta'];

	if ( $term_meta_content != '' )
	{
		if ( is_tax( array( 'product_cat', 'product_tag' ) ) && 0 === absint( get_query_var( 'paged' ) ) )
		{
			echo '<div class="woo-sc-box normal rounded full">';
			echo apply_filters( 'the_content', $term_meta_content );
			echo '</div>';
		}
	}
}
add_action( 'woocommerce_single_product_summary', 'wc_brands_add_brand_name', 1 );

function wc_brands_add_brand_name() {
	global $product;
	$brands =  implode(', ', wp_get_post_terms($product->get_id(), 'product_brand', ['fields' => 'names']));
	echo "<p>Бренд: " . $brands . "</p>";
}



/*add_filter( 'woocommerce_structured_data_product_offer', 'true_remove_schema_prices', 25, 2 );

function true_remove_schema_prices( $markup_offer, $product ){

	$brands =  implode(', ', wp_get_post_terms($product->get_id(), 'product_brand', ['fields' => 'names']));
	$markup_offer['brand'] = $brands;


var_dump($markup_offer);
	return $markup_offer;
}*/

function add_brand_to_product_schema($data) {
   if (!is_array($data)) {
	  // If $data isn't an array, don't do anything.
   } elseif (empty($product = wc_get_product())) {
	  // Don't do anything.
//   } elseif (!is_array($brands = wc_get_product_terms($product->get_id(), WPT_PBS_BRAND_ATTRIBUTE, array('fields' => 'names')))) {
	} elseif (!is_array($brands = wp_get_post_terms($product->get_id(), 'product_brand', array('fields' => 'names')))) {
	  // This product has no brands associated with it.
   } elseif (empty($brands)) {
	  // This product has zero brands associated with it.
   } elseif (count($brands) == 1) {
	  // This product has exactly one brand associated with it.
	  $data['brand'] = array('@type' => 'Brand', 'name' => $brands[0]);
   } else {
	  // This product has multiple brands associated with it.
	  $data['brand'] = array();
	  foreach ($brands as $brand) {
		 $data['brand'][] = array('@type' => 'Brand', 'name' => $brand);
	  }
   }
   return $data;
}
add_filter('woocommerce_structured_data_product', 'add_brand_to_product_schema');
function get_product_id_by_slug($slug) {
	$args = array(
		'name'        => $slug,
		'post_type'   => 'product',
		'post_status' => 'publish',
		'numberposts' => 1
	);
	$products = get_posts($args);
	if ($products) {
		return $products[0]->ID;
	}

	return 0;
}
function get_variation_id_by_attributes($slug, $attributes) {
	// Получение товара по его slug
	$product_id = get_product_id_by_slug($slug);

	// Если продукт не найден, вернем false
	if (!$product_id) {
		return false;
	}

	$product = wc_get_product($product_id);

	if (!$product || 'variable' !== $product->get_type()) {
		return false;
	}

	// Получение всех вариаций товара
	$variation_ids = $product->get_children();

	foreach ($variation_ids as $variation_id) {
		$variation = wc_get_product($variation_id);
		$variation_attributes = $variation->get_variation_attributes();

		$match = true;
		foreach ($attributes as $attribute_name => $attribute_value) {
			if (isset($variation_attributes['attribute_' . $attribute_name])) {
				if ($attribute_value !== null && $variation_attributes['attribute_' . $attribute_name] !== $attribute_value) {
					$match = false;
					break;
				}
			} else {
				$match = false;
				break;
			}
		}

		if ($match) {
			return $variation_id;
		}
	}

	return false;
}

/**
 * делаем чтобы новый формат url мог открывать вариации по pid_число
 */
function custom_parse_request($wp) {
	$request_uri = $_SERVER['REQUEST_URI'];

	$parsed_url = parse_url($request_uri);
	$path = $parsed_url['path'];

	if (strpos($request_uri, '?attribute_pa_') !== false && isset($parsed_url['query'])) {
		parse_str($parsed_url['query'], $query_params);

		$uri_parts = explode('/', trim($request_uri, '/'));
		$path = $parsed_url['path'];
		$slug = trim(str_replace('/product/', '', $path), '/');

		if (isset($query_params['pid'])) {
			$new_url = get_custom_product_url($query_params['pid']);
			wp_redirect($new_url, 301);
			exit;
		}
		if (isset($query_params['variation_id'])) {
			$new_url = get_custom_product_url($query_params['variation_id']);
			wp_redirect($new_url, 301);
			exit;
		}

		// Если pid и variation_id отсутствуют, ищем по атрибутам
		$attributes = [
			'pa_tyre_width' => rtrim($query_params['attribute_pa_tyre_width'], '/'),
			'pa_tyre_profile' => rtrim($query_params['attribute_pa_tyre_profile'], '/'),
			'pa_tyre_rim' => rtrim($query_params['attribute_pa_tyre_rim'], '/'),
			'pa_load_index' => rtrim($query_params['attribute_pa_load_index'], '/'),
			'pa_speed_index' => rtrim($query_params['attribute_pa_speed_index'], '/')
		];

		$variation_id = get_variation_id_by_attributes($slug, $attributes);
		if ($variation_id) {
			$new_url = get_custom_product_url($variation_id);
			wp_redirect($new_url, 301);
			exit;
		}
	}

	// Ищем PID в конце URL
	if (preg_match('/-pid_([0-9]+)\/?$/', $path, $matches)) {
		$pid = intval($matches[1]);
		$_GET['pid'] = $pid;
		// Проверим, является ли PID вариацией
		$product = wc_get_product($pid);
		if ($product && $product->is_type('variation')) {
			// Получим родительский продукт вариации
			$parent_product = wc_get_product($product->get_parent_id());

			// Устанавливаем переменные запроса для страницы вариации
			$wp->query_vars = array(
				'product' => $parent_product->get_slug(),
				'post_type' => 'product',
				'name' => $parent_product->get_slug(),
				'page' => ''
			);

			// Устанавливаем параметры страницы, чтобы избежать 404 ошибки
			$wp->is_404 = false;
			$wp->is_single = true;
			$wp->is_archive = false;
			$wp->is_page = false;
			$wp->is_home = false;
		} else {
			$wp->is_404 = true; // Если PID не существует или не является вариацией, переадресуйте на страницу 404
		}
	}
}
add_action('parse_request', 'custom_parse_request');


function get_custom_product_url($post_id) {
	$product = wc_get_product($post_id);

	// Отбросить часть URL после (и включая) символа '?'
	$base_url = explode('?', get_permalink($post_id))[0];

	$attributes = array_map(function ($value) {
		return sanitize_title($value);
	}, $product->get_attributes());
	$uri = $base_url . implode('-', $attributes) . "-pid_" . $post_id . '/';

	return $uri;
}
function custom_canonical_url($canonical_url) {
	if (isset($_GET['pid']) && is_numeric($_GET['pid'])) {
		$custom_url = get_custom_product_url($_GET['pid']);
		if ($custom_url) {
			return $custom_url;
		}
	}
	return 'https://vsespecshini.ru'.$_SERVER['REQUEST_URI'];
}
add_filter('wpseo_canonical', 'custom_canonical_url', 9999);
add_filter('canonical', 'custom_canonical_url', 9999);
add_filter('meta_canonical', 'custom_canonical_url', 9999);
add_action( 'wpseo_head', 'custom_canonical_url', 21 );

function custom_og_url($og_url) {
	if (isset($_GET['pid']) && is_numeric($_GET['pid'])) {
		$custom_url = get_custom_product_url($_GET['pid']);
		if ($custom_url) {
			return $custom_url;
		}
	}
	return 'https://vsespecshini.ru'.$_SERVER['REQUEST_URI'];
}
add_filter('wpseo_opengraph_url', 'custom_og_url', 20);


function custom_woocommerce_breadcrumbs($crumbs, $class) {
	// Проверяем, является ли товар вариативным по pid
	if (isset($_GET['pid']) && is_numeric($_GET['pid'])) {
		$product_id = $_GET['pid']; // Присваиваем PID переменной product_id
		$product = wc_get_product($product_id); // Получаем товар по его ID

		if ($product) {
			$product_name = $product->get_name();

			// Получаем атрибуты
			$tyre_width = get_post_meta($product_id, 'attribute_pa_tyre_width', true);
			$tyre_profile = get_post_meta($product_id, 'attribute_pa_tyre_profile', true);
			$tyre_rim = get_post_meta($product_id, 'attribute_pa_tyre_rim', true);
			$load_index = get_post_meta($product_id, 'attribute_pa_load_index', true);
			$speed_index = get_post_meta($product_id, 'attribute_pa_speed_index', true);

			// Формируем строку с атрибутами и преобразуем её в верхний регистр
			$attributes_string = mb_strtoupper("$tyre_width/$tyre_profile $tyre_rim $load_index $speed_index");
			$desc = $attributes_string;

			// Изменяем хлебные крошки
			if (isset($crumbs[4])) { // Если существует 4-й элемент
				$crumbs[4][0] = $product_name; // Обновляем только название продукта, не трогая URL
			}

			// Добавляем новый элемент в конец массива хлебных крошек
			$crumbs[] = array($desc);
		}
	}

	return $crumbs;
}
add_filter('woocommerce_get_breadcrumb', 'custom_woocommerce_breadcrumbs', 20, 2);

function log_to_file($message) {
	$log_file = ABSPATH . 'sitemap.errors.txt';
	$current_date = date('Y-m-d H:i:s');
	file_put_contents($log_file, "$current_date - $message\n", FILE_APPEND);
}

function generate_variation_sitemap() {
	ini_set('display_errors', 1);

	$args = array(
		'post_type' => 'product_variation',
		'posts_per_page' => -1,
		'post_status' => 'publish',
		'fields' => 'ids',
		'no_found_rows' => true,
		'update_post_meta_cache' => false,
		'update_post_term_cache' => false,
		'meta_query' => array(  // Добавим запрос для мета-данных
			array(
				'key' => '_stock_status',  // Проверяем поле статуса наличия на складе
				'value' => 'instock',  // Только товары в наличии
				'compare' => '=',  // Должно соответствовать
			)
		)
	);


	$query = new WP_Query($args);

	if (!$query->have_posts()) {
		log_to_file("No variations found");
		return;
	}

	$doc = new DOMDocument();
	$doc->formatOutput = true;

	$urlset = $doc->createElement('urlset');
	$urlset->setAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');

	foreach ($query->posts as $variation_id) {
		$variation_url = get_custom_product_url($variation_id);

		$url = $doc->createElement('url');
		$loc = $doc->createElement('loc', esc_url($variation_url));
		$lastmod = $doc->createElement('lastmod', date(DATE_W3C));
		$changefreq = $doc->createElement('changefreq', 'weekly');
		$priority = $doc->createElement('priority', '0.8');

		$url->appendChild($loc);
		$url->appendChild($lastmod);
		$url->appendChild($changefreq);
		$url->appendChild($priority);
		$urlset->appendChild($url);
	}

	$doc->appendChild($urlset);

	$sitemap_filename = ABSPATH . 'sitemap-variations.xml';
	file_put_contents($sitemap_filename, $doc->saveXML());

	log_to_file("Sitemap generated successfully");
}


add_action('rest_api_init', function () {
	register_rest_route('vsespecshini/v1', '/generate-sitemap-variations', array(
		'methods' => 'GET',
		'callback' => 'generate_variation_sitemap_api',
		'permission_callback' => '__return_true'  // отключаем проверку прав пользователя
	));
});

function generate_variation_sitemap_api($request) {
	generate_variation_sitemap();

	$response = new WP_REST_Response(array(
		'success' => true,
		'message' => 'Sitemap generated successfully.'
	), 200);

	// Добавляем заголовок, чтобы предотвратить кеширование
	$response->headers['X-LiteSpeed-Cache-Control'] = 'no-cache, no-store, must-revalidate, max-age=0';

	return $response;
}

function schedule_variation_sitemap_generation() {
	if (!wp_next_scheduled('generate_daily_variation_sitemap')) {
		// Устанавливаем задачу на 4 утра по Московскому времени (UTC+3)
		$scheduled_time = strtotime('Tomorrow 4:00am +3 hours');
		wp_schedule_event($scheduled_time, 'daily', 'generate_daily_variation_sitemap');
	}
}
add_action('wp', 'schedule_variation_sitemap_generation');

add_action('generate_daily_variation_sitemap', 'generate_variation_sitemap');
include_once 'inc/cards.php';

function custom_wc_optional_checkout_fields($fields) {
	// Убираем обязательность для полей счета (billing)
	$fields['billing']['billing_postcode']['required'] = false;
	$fields['billing']['billing_state']['required'] = false;
	$fields['billing']['billing_city']['required'] = false;
	$fields['billing']['billing_address_1']['required'] = false;
	$fields['billing']['billing_email']['required'] = false;

	// Убираем обязательность для полей доставки (shipping)
	$fields['shipping']['shipping_postcode']['required'] = false;
	$fields['shipping']['shipping_state']['required'] = false;
	$fields['shipping']['shipping_city']['required'] = false;
	$fields['shipping']['shipping_address_1']['required'] = false;

	return $fields;
}
add_filter('woocommerce_checkout_fields', 'custom_wc_optional_checkout_fields');

function custom_enqueue_scripts() {
	wp_enqueue_script('custom-checkout', get_stylesheet_directory_uri() . '/js/custom-checkout.js?v=17', array('jquery', 'wc-checkout'), '1.0', true);
}

add_action('wp_enqueue_scripts', 'custom_enqueue_scripts');

function get_variation_description($variation_id) {
	$product = wc_get_product($variation_id);

	// Проверяем, является ли объект продукта экземпляром WC_Product_Variation
	if ($product instanceof WC_Product_Variation) {
		// Получаем описание вариации
		$description = $product->get_description();

		// Если описание найдено, вернуть его
		if (!empty($description)) {
			return $description;
		}
	}

	return '';
}

function my_custom_woocommerce_template_loop_product_title() {
	global $product;
	//echo '<h3 class="woocommerce-loop-product__title"><a href="' . esc_url( get_the_permalink() ) . '" title="' . esc_attr( get_the_title() ) . '">' . get_the_title() . '</a></h3>';
}
remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
add_action( 'woocommerce_shop_loop_item_title', 'my_custom_woocommerce_template_loop_product_title', 10 );

