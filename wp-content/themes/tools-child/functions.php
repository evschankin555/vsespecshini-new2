<?php
add_action( 'widgets_init', 'register_widgets' );
ini_set('display_errors', 1);
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


	"shini_wpseo_title"		=> "#%category_clean%# #%title%# ",//"#%category_clean%# #%title%# цена от #%price%# руб",
	"shini_product_title"		=> "#%title%#",//"#%title%# цена от #%price%# руб",
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

    //новая доктрина заголовков от января 2024г
    if(is_product())
    {
        $categories = get_the_terms($post->ID, 'product_cat');
        $category = reset($categories);
        $category_name = $category->name;
        $product_name = $post->post_title;
        $product_model = $product_name;

        if($variableID > 0){
            $pid = extractPidFromCurrentUrl();
            $product_name = get_post_meta($pid, '_variation_description', true );
        }

        $predmet1 = 'шин';
        $predmet2 = 'шине';
        if($category_name == 'Грузовые диски' || $category_name == 'Грузовые диски на прицеп'){
            $category_name = 'Диски';

            $predmet1 = 'дисков';
            $predmet2 = 'диске';
        }
        $wpseo_replace_vars = $category_name . ' ' . $product_name .
            ' купить в Москве и с доставкой по России. Продажа '.$predmet1
            .' ' . get_first_word($product_model) .' отзывы о '.$predmet2
            .', быстрая доставка';
    }

	return $wpseo_replace_vars;
}
function get_first_word($product_model) {
    // Разбиваем строку на массив слов
    $words = explode(' ', $product_model);

    // Берем первый элемент массива (первое слово)
    $first_word = $words[0];

    // Возвращаем первое слово
    return $first_word;
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

    if (isset($data['filters']) && is_array($data['filters'])) {
        foreach ($data['filters'] as $filters) {
            if (isset($filters['terms']) && is_array($filters['terms'])) {
                foreach ($filters['terms'] as $filter) {
                    if (is_object($filter) && isset($filter->taxonomy) && isset($filter->name)) {
                        $taxonomy = $filter->taxonomy;
                        $attr[$taxonomy] = $filter->name;
                    }
                }
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

    //новая доктрина заголовков от января 2024г
	if(is_product())
	{
        $categories = get_the_terms($post->ID, 'product_cat');
        $category = reset($categories);

        $category_name = $category->name;
        $product_name = $post->post_title;
        $categories = get_the_terms($post->ID, 'product_cat');

        if ($categories && !is_wp_error($categories)) {
            foreach ($categories as $category) {
                if (0 == count(get_ancestors($category->term_id, 'product_cat'))) {
                    if($category->name == 'Шины'){
                        $category_name = $category->name;
                        $product_name = $post->post_title;
                    }
                }
            }
        }

        if($category_name == 'Грузовые диски' || $category_name == 'Грузовые диски на прицеп'){
            $category_name = 'Диски';
        }

        $product = wc_get_product( $post->ID );
        $price = 'от ' . $product->get_price();
        if($variableID > 0){
            $pid = extractPidFromCurrentUrl();
            $product_name = get_post_meta($pid, '_variation_description', true );
            $product = wc_get_product( $pid );
            if ($product && method_exists($product, 'get_price')) {
                $price = $product->get_price();
            }         }

        $title = $category_name . ' ' . $product_name . ' цена ' . $price .' руб';
	}

	$title = esc_html( wp_strip_all_tags( stripslashes( $title ), true ) );

	return $title;
}
function extractPidFromCurrentUrl() {
    // Получаем текущий URL
    $urlParts = explode('/', rtrim($_SERVER['REQUEST_URI'], '/'));

    // Ищем часть, содержащую "pid_"
    $pid = null;
    foreach ($urlParts as $part) {
        $pidIndex = strpos($part, 'pid_');
        if ($pidIndex !== false) {
            // Если нашли, извлекаем часть после "pid_"
            $pidPart = substr($part, $pidIndex + 4);

            // Разбиваем на части через дефис
            $pidParts = explode('-', $pidPart);

            // Извлекаем последнюю часть (после последнего дефиса)
            $pid = end($pidParts);

            // Удаляем возможные тире перед числом и другие символы
            $pid = filter_var($pid, FILTER_SANITIZE_NUMBER_INT);

            // Проверяем, что это действительно число
            if (is_numeric($pid)) {
                return $pid;
            }
        }
    }

    // Если не найдено, возвращаем null
    return $pid;
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
	if (strpos($request_uri, '?attribute_pa_') !== false && isset($parsed_url['query'])
        || !empty($_GET['pid'])
        || strpos($request_uri, 'pid_') !== false
    ) {
        preg_match('/-pid_([0-9]+)\/?$/', $path, $matches);
        $pid = intval($matches[1]);
		parse_str($parsed_url['query'], $query_params);

		$uri_parts = explode('/', trim($request_uri, '/'));
		$path = $parsed_url['path'];
		$slug = trim(str_replace('/product/', '', $path), '/');

        if (isset($query_params['pid']) || !empty($_GET['pid']) || !empty($pid)) {
            if (!empty($pid)){
                $pid =  $pid;
            } elseif (empty($_GET['pid'])){
                $pid =  $query_params['pid'];
            } else {
                $pid =  $_GET['pid'];
            }
            $product = wc_get_product($pid);
            if (!$product || is_wp_error($product)) {
                // Очищаем текущий контент
                $wp->query_vars = array();
                $wp->is_404 = true;

                // Задаем заголовок 404
                status_header(404);

                // Включаем шаблон 404 страницы
                include get_404_template();
                die();
            }
            // Получение текущего URL с учетом атрибутов запроса
            $current_url = home_url($_SERVER['REQUEST_URI']);

            // Получение нового URL с использованием функции
            $new_url = get_custom_product_url($pid);

            // Проверка, нужен ли редирект
            if ($current_url !== $new_url) {
                wp_redirect($new_url, 301);
                exit;
            }

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
        if (!$product || is_wp_error($product)) {
            // Вывести код 404 и отобразить страницу 404
            status_header(404);
            nocache_headers();
            $wp->is_404 = true;
        }else if ($product && $product->is_type('variation')) {
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
    // Проверим, существует ли пост с заданным идентификатором
    $post = get_post($post_id);
    if (!$post) {
        return ''; // или другая логика по умолчанию, в зависимости от ваших требований
    }

    // Отбросить часть URL после (и включая) символа '?'
    $base_url = explode('?', get_permalink($post_id))[0];

    $attributes = array();

    // Проверяем, что $product не равен false и имеет метод get_attributes()
    if ($product && method_exists($product, 'get_attributes')) {
        foreach ($product->get_attributes() as $value) {
            $sanitized_value = sanitize_title($value);
            if (!empty($sanitized_value)) {
                $attributes[] = $sanitized_value;
            }
        }
    }

    // Добавим проверку на пустоту массива атрибутов и наличие вариаций
    if (!empty($attributes) && $product->is_type('variation')) {
        $uri = $base_url . implode('-', $attributes) . "-pid_" . $post_id . '/';
    } else {
        $uri = $base_url;
    }

    return $uri;
}



function is_valid_pid() {
    return isset($_GET['pid']) && is_numeric($_GET['pid']);
}

function get_custom_url_by_pid() {
    if (is_valid_pid()) {
        $custom_url = get_custom_product_url($_GET['pid']);
        if ($custom_url) {
            return $custom_url;
        }
    }
    return false;
}
function is_custom_mask_url($url) {
    $canonical_base = 'product-category/shini/filters/';
    return strpos($url, $canonical_base) !== false;
}

function check_and_redirect_custom_url() {
    if (is_custom_mask_url($_SERVER['REQUEST_URI'])) {
        // Разбиваем строку на части по "/"
        $url_parts = explode('/', trim($_SERVER['REQUEST_URI'], '/'));

        // Массив для хранения параметров
        $params = array(
            'tyre_width'    => '',
            'tyre_profile'  => '',
            'tyre_rim'      => '',
            'page'          => '',
        );

        // Проходим по каждой части URL и определяем параметры
        foreach ($url_parts as $key => $value) {
            switch ($value) {
                case 'tyre_width':
                case 'tyre_profile':
                case 'tyre_rim':
                case 'page':
                    // Если это один из параметров, то устанавливаем значение
                    $params[$value] = $url_parts[$key + 1] ?? '';
                    break;
            }
        }

        // Формирование канонического URL
        $canonical_url = '/product-category/shini/filters/';

        if (!empty($params['tyre_width'])) {
            $canonical_url .= 'tyre_width/' . $params['tyre_width'] . '/';
        }

        if (!empty($params['tyre_profile'])) {
            $canonical_url .= 'tyre_profile/' . $params['tyre_profile'] . '/';
        }

        if (!empty($params['tyre_rim'])) {
            $canonical_url .= 'tyre_rim/' . $params['tyre_rim'] . '/';
        }

        // Добавляем параметр 'page', если он установлен
        if ($params['page'] !== '') {
            $canonical_url .= 'page/' . $params['page'] . '/';
        }

        // Проверка и редирект, если URL не соответствует каноническому
        if (parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) !== $canonical_url) {
            //wp_redirect($canonical_url, 301);
            //exit();
        }
        return $canonical_url;
    }

    return false;
}





function custom_canonical_url($canonical_url) {
    // Проверка наличия параметра страницы и его значения
    if (isset($_GET['page']) && is_numeric($_GET['page']) && intval($_GET['page']) > 1) {
        // Собираем канонический URL без параметра страницы
        $canonical_without_page = preg_replace('/\/page\/\d+/', '', $_SERVER['REQUEST_URI']);
        $canonical_url = 'https://vsespecshini.ru' . $canonical_without_page;
    } else {
        // Получаем URL без параметров запроса
        $url_without_query = strtok($_SERVER['REQUEST_URI'], '?');
        $canonical_url = 'https://vsespecshini.ru' . $url_without_query;
    }

    // Проверка и редирект для маски
    $custom_mask_result = check_and_redirect_custom_url();
    if ($custom_mask_result) {
        return 'https://vsespecshini.ru'.$custom_mask_result;
    }
    // Проверка наличия параметра pid и его значения
    $custom_url_by_pid = get_custom_url_by_pid();
    if ($custom_url_by_pid) {
        return $custom_url_by_pid;
    }

    // По умолчанию
    return $canonical_url;
}

// Применение фильтра
add_filter('wpseo_canonical', 'custom_canonical_url', 10);
//add_filter('canonical', 'custom_canonical_url', 1);
//add_filter('meta_canonical', 'custom_canonical_url', 1);
//add_action('wpseo_head', 'custom_canonical_url', 1);




function custom_og_url($og_url) {
	if (isset($_GET['pid']) && is_numeric($_GET['pid'])) {
		$custom_url = get_custom_product_url($_GET['pid']);
		if ($custom_url) {
			return $custom_url;
		}
	}
	return 'https://vsespecshini.ru'.$_SERVER['REQUEST_URI'];
}
add_filter('wpseo_opengraph_url', 'custom_canonical_url', 20);


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

function generateProductXML($product_id, $doc, $channel, $productParent = false, $data = false)
{

    $product = wc_get_product($product_id);

    $item = $doc->createElement('item');

    $id = $doc->createElement('g:id');
    $variation_sku = $product->get_sku();;
    $id_cdata = $doc->createCDATASection($variation_sku);
    $id->appendChild($id_cdata);
    $item->appendChild($id);

    $title = $doc->createElement('title');
    if($productParent){
        $product_name = get_post_meta($product_id, '_variation_description', true );
    }else{
        $product_post = get_post($product_id);
        $product_name = $product_post->post_title;
    }
    $title_cdata = $doc->createCDATASection($product_name);
    $title->appendChild($title_cdata);
    $item->appendChild($title);

    $description = $doc->createElement('description');
    if($productParent){
        $description_text = $data['description_text'];
    }else{
        $description_text = $product->get_short_description();
        // Используем регулярное выражение для получения текста между тегами <a>
        preg_match_all('/<a.*?>(.*?)<\/a>/', $description_text, $matches);

        // Заменяем теги <a> на текст между ними
        foreach ($matches[0] as $index => $match) {
            $replacement = $matches[1][$index];
            $description_text = str_replace($match, $replacement, $description_text);
        }
    }

    $description_cdata = $doc->createCDATASection($description_text);
    $description->appendChild($description_cdata);
    $item->appendChild($description);

    if ($productParent){
        $item_group_id = $doc->createElement('g:item_group_id');
        $item_group_id_cdata = $doc->createCDATASection($data['product_id']);
        $item_group_id->appendChild($item_group_id_cdata);
        $item->appendChild($item_group_id);
    }

    $link = $doc->createElement('link');
    $link_cdata = $doc->createCDATASection(get_custom_product_url($product_id));
    $link->appendChild($link_cdata);
    $item->appendChild($link);

    $product_type = $doc->createElement('g:product_type');
    if($productParent){
        $product_type_text = $data['product_type_text'];
    }else{
        $product_type_text = get_product_type_hierarchy($product_id);
    }
    $product_type_cdata = $doc->createCDATASection($product_type_text);
    $product_type->appendChild($product_type_cdata);
    $item->appendChild($product_type);

    $google_product_category = $doc->createElement('g:google_product_category');
        // Определяем категорию для основного товара
        if (strpos($product_type_text, '>') !== false) {
            // Разделение строки по символу ">"
            $categories = explode('>', $product_type_text);
            $main_category = trim($categories[0]); // Получаем главную категорию
        } else {
            $main_category = trim($product_type_text); // Если разделения нет, работаем с полным текстом
        }
    // Назначаем ID категории в соответствии с главной категорией
    if (mb_stripos($main_category, 'Шины') !== false) {
        $category_id = '6093';
    } elseif (mb_stripos($main_category, 'диск') !== false) {
        $category_id = '6090';
    } else {
        $category_id = '6093'; // Значение по умолчанию, если не подходит ни под одно условие
    }

    $google_product_category_cdata = $doc->createCDATASection($category_id);
    $google_product_category->appendChild($google_product_category_cdata);
    $item->appendChild($google_product_category);

    $image_id = $product->get_image_id();
    $image_url = wp_get_attachment_image_url($image_id, 'full');
    $image_link = $doc->createElement('g:image_link');
    $image_link_cdata = $doc->createCDATASection(esc_url($image_url));
    $image_link->appendChild($image_link_cdata);
    $item->appendChild($image_link);

    // Добавление дополнительных изображений
    $additional_image_ids = $product->get_gallery_image_ids();
    foreach ($additional_image_ids as $additional_id) {
        $additional_image_url = wp_get_attachment_image_url($additional_id, 'full');
        $additional_image_link = $doc->createElement('g:additional_image_link');
        $additional_image_cdata = $doc->createCDATASection(esc_url($additional_image_url));
        $additional_image_link->appendChild($additional_image_cdata);
        $item->appendChild($additional_image_link);
    }

    $condition = $doc->createElement('g:condition');
    $condition_cdata = $doc->createCDATASection('New');
    $condition->appendChild($condition_cdata);
    $item->appendChild($condition);

    $availability = $doc->createElement('g:availability');
    $availability_cdata = $doc->createCDATASection('in stock');
    $availability->appendChild($availability_cdata);
    $item->appendChild($availability);

    $price_value = number_format($product->get_price(), 2, '.', '') . ' RUB';
    $price_cdata = $doc->createCDATASection($price_value);

    $price = $doc->createElement('g:price');
    $price->appendChild($price_cdata);
    $item->appendChild($price);

    $identifier_exists = $doc->createElement('g:identifier_exists');
    $identifier_exists_cdata = $doc->createCDATASection('yes');
    $identifier_exists->appendChild($identifier_exists_cdata);
    $item->appendChild($identifier_exists);

    $brand = $doc->createElement('g:brand');
    if($productParent){
        $brand_text = $data['brand_text'];
    }else{
        $brand_text = getBrandText($product);
    }
    $brand_cdata = $doc->createCDATASection($brand_text);
    $brand->appendChild($brand_cdata);
    $item->appendChild($brand);

    addProductDetails($product_id, $doc, $item);

    $channel->appendChild($item);
    if($productParent == false){
        return [
                'description_text' => $description_text,
                'product_type_text' => $product_type_text,
                'last_category' => $last_category,
                'brand_text' => $brand_text,
        ];
    }else{
        return false;
    }
}
function generateProductXMLParrent($product_id, $doc)
{
    $product = wc_get_product($product_id);
    $item = $doc->createElement('item');
    $description_text = $product->get_short_description();
    preg_match_all('/<a.*?>(.*?)<\/a>/', $description_text, $matches);
    foreach ($matches[0] as $index => $match) {
        $replacement = $matches[1][$index];
        $description_text = str_replace($match, $replacement, $description_text);
    }
    $product_type_text = get_product_type_hierarchy($product_id);
    if (strpos($product_type_text, '>') !== false) {
        // Разделение строки по символу ">"
        $categories = explode('>', $product_type_text);
        if (is_array($categories) && !empty($categories)) {
            $last_category = trim(end($categories));
        }else{
            $last_category = trim($product_type_text);
        }
    } else {
        $last_category = trim($product_type_text);
    }
    $brand_text = getBrandText($product);
    $product_id = $product->get_id();
    return [
        'description_text' => $description_text,
        'product_type_text' => $product_type_text,
        'last_category' => $last_category,
        'brand_text' => $brand_text,
        'product_id' => $product_id,
    ];
}
function get_product_attributes($product_id) {
    $product = wc_get_product($product_id);
    $attributes = $product->get_attributes();
    $result = [];
    $exclude_attributes = ['pa_load_index', 'pa_speed_index', 'pa_disk_hole']; // Список исключаемых атрибутов

    // Маппинг старых названий атрибутов к новым
    $attribute_names_map = [
        'pa_disk_width' => 'Ширина',
        'pa_disk_rim' => 'Диаметр',
        'pa_disk_pcd' => 'PCD',
        'pa_disk_et' => 'Вылет',
        'pa_disk_dia' => 'Диаметр ступицы',
    ];

    foreach ($attributes as $attribute_key => $attribute_value) {
        // Пропускаем атрибут, если он находится в списке исключений
        if (in_array($attribute_key, $exclude_attributes)) {
            continue;
        }

        $attribute_label = $attribute_key;
        if (isset($attribute_names_map[$attribute_key])) {
            // Переименовываем атрибут, если он есть в маппинге
            $attribute_label = $attribute_names_map[$attribute_key];
        } else {
            // Получаем человекочитаемый лейбл для атрибута, если он не в маппинге
            $attribute_label = wc_attribute_label($attribute_key);
        }

        if ($attribute_value instanceof WC_Product_Attribute) {
            $options = $attribute_value->get_options();
            $values = [];
            foreach ($options as $option) {
                $term = get_term_by('id', $option, $attribute_key);
                if ($term && !is_wp_error($term)) {
                    $values[] = $term->name;
                }
            }
            // Сохраняем человекочитаемые значения атрибута
            $value = implode(', ', $values);
        } else {
            if ($attribute_key && is_string($attribute_value)) {
                $term = get_term_by('slug', $attribute_value, $attribute_key);
                $value = $term ? $term->name : $attribute_value;
            } else {
                $value = $attribute_value;
            }
        }

        // Пропускаем атрибут, если значение пусто или равно '0'
        if (!empty($value) && $value !== '0') {
            $result['Размер'][$attribute_label] = $value;
        }
    }

    return $result;
}


function addProductDetails($product_id, $doc, $item) {
    $attributes = get_product_attributes($product_id); // Получаем атрибуты товара

    foreach ($attributes as $section_name => $attrs) {
        foreach ($attrs as $attribute_name => $attribute_value) {
            // Пропускаем атрибуты с пустыми значениями или значением "0"
            if (empty($attribute_value) || $attribute_value === "0") {
                continue; // Прекращаем обработку текущего атрибута и переходим к следующему
            }

            $product_detail = $doc->createElement('g:product_detail');

            // Добавляем название секции
            $section_name_element = $doc->createElement('g:section_name');
            $section_name_cdata = $doc->createCDATASection($section_name);
            $section_name_element->appendChild($section_name_cdata);
            $product_detail->appendChild($section_name_element);

            // Добавляем название атрибута
            $attribute_name_element = $doc->createElement('g:attribute_name');
            $attribute_name_cdata = $doc->createCDATASection($attribute_name);
            $attribute_name_element->appendChild($attribute_name_cdata);
            $product_detail->appendChild($attribute_name_element);

            // Добавляем значение атрибута
            $attribute_value_element = $doc->createElement('g:attribute_value');
            $attribute_value_cdata = $doc->createCDATASection($attribute_value);
            $attribute_value_element->appendChild($attribute_value_cdata);
            $product_detail->appendChild($attribute_value_element);

            // Добавляем product_detail в item
            $item->appendChild($product_detail);
        }
    }
}

function my_custom_woocommerce_template_loop_product_title() {
	global $product;
	//echo '<h3 class="woocommerce-loop-product__title"><a href="' . esc_url( get_the_permalink() ) . '" title="' . esc_attr( get_the_title() ) . '">' . get_the_title() . '</a></h3>';
}
remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
add_action( 'woocommerce_shop_loop_item_title', 'my_custom_woocommerce_template_loop_product_title', 10 );
function generate_google_merchant_feed() {
    ini_set('display_errors', 1);
    $args = array(
        'post_type' => 'product',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'fields' => 'ids',
        'no_found_rows' => true,
        'update_post_meta_cache' => false,
        'update_post_term_cache' => false,
        'meta_query' => array(
            array(
                'key' => '_stock_status',
                'value' => 'instock',
                'compare' => '=',
            )
        )
    );

    $query = new WP_Query($args);

    if (!$query->have_posts()) {
        log_to_file("No products found");
        return;
    }

    $doc = new DOMDocument('1.0', 'utf-8');
    $doc->formatOutput = true;

    $rss = $doc->createElement('rss');
    $rss->setAttribute('xmlns:g', 'http://base.google.com/ns/1.0');
    $rss->setAttribute('xmlns:c', 'http://base.google.com/cns/1.0');
    $rss->setAttribute('version', '2.0');
    $doc->appendChild($rss);

    $channel = $doc->createElement('channel');
    $rss->appendChild($channel);
    $title = $doc->createElement('title');
    $title_cdata = $doc->createCDATASection('Интернет-магазин шин для спецтехники');
    $title->appendChild($title_cdata);
    $channel->appendChild($title);

    $link = $doc->createElement('link');
    $link_cdata = $doc->createCDATASection('https://vsespecshini.ru');
    $link->appendChild($link_cdata);
    $channel->appendChild($link);

    $description = $doc->createElement('description');
    $description_cdata = $doc->createCDATASection('Продажа грузовых, строительных сельскохозяйственных шин');
    $description->appendChild($description_cdata);
    $channel->appendChild($description);

    $count = 0;
    foreach ($query->posts as $product_id) {
        // Если у продукта есть вариации, обрабатываем каждую
        $product = wc_get_product($product_id);

        $data = generateProductXMLParrent($product_id, $doc);
        if ($product->is_type('variable')) {
            $has_variations = false;
            foreach ($product->get_children() as $variation_id) {
                $variation = wc_get_product($variation_id);
                if ($variation->is_in_stock()) {
                    $has_variations = true;
                    // Обработка вариации с указанием item_group_id равным ID родительского товара
                    generateProductXML($variation_id, $doc, $channel, true, $data);
                    $count++;
                }
            }
            if (!$has_variations) {
                // Если вариаций нет, выводим основной продукт без item_group_id
                generateProductXML($product_id, $doc, $channel, false, null);
                $count++;
            }
        } else {
            // Для простых товаров выводим без указания item_group_id
            generateProductXML($product_id, $doc, $channel, false, null);
            $count++;
        }
    }

    $feed_filename = ABSPATH . 'google-merchant-feed.xml';
    file_put_contents($feed_filename, $doc->saveXML());
    log_to_file("Google Merchant Feed generated successfully. Count products in xml file: " . $count);
    return $count;
}


add_action('rest_api_init', function () {
    register_rest_route('vsespecshini/v1', '/generate-google-merchant-feed', array(
        'methods' => 'GET',
        'callback' => 'generate_google_merchant_feed_api',
        'permission_callback' => '__return_true'  // Отключаем проверку прав пользователя
    ));
});

function generate_google_merchant_feed_api() {
    // Вызываем функцию для генерации Google Merchant Feed
    $count = generate_google_merchant_feed();

    // Возвращаем ответ в формате JSON
    return new WP_REST_Response(array('message' => 'Google Merchant Feed generated successfully. Count products in xml file:'.$count), 200);
}

function get_hierarchy_name($terms, $term_id)
{
    $hierarchy = [];

    while ($term_id !== 0) {
        $found = false;

        foreach ($terms as $term) {
            if ($term->term_id === $term_id) {
                $hierarchy[] = $term->name;
                $term_id = $term->parent;
                $found = true;
                break;
            }
        }

        // Если родительский элемент не найден, прервать цикл
        if (!$found) {
            break;
        }
    }

    return array_reverse($hierarchy);
}

function get_product_type_hierarchy($product_id) {
    ini_set('max_execution_time', 0);
    $categories = wp_get_post_terms($product_id, 'product_cat', array('fields' => 'all'));

    $hierarchy = array();

    $categories_by_id = array();
    foreach ($categories as $category) {
        $categories_by_id[$category->term_id] = $category;
    }

    foreach ($categories as $category) {
        $hierarchy_name = get_hierarchy_name($categories, $category->term_id);
        $hierarchy_string = implode(" > ", $hierarchy_name);
        $result =  $hierarchy_string . "\n";
    }

    // Объединяем массив в строку с разделителем '>'
    return $result;
}

function getBrandText($product) {
    $brand_text = '';

    $terms = wc_get_product_terms($product->get_id(), 'product_brand');

    if (!empty($terms) && !is_wp_error($terms)) {
        // Если есть термины, получаем текст первого
        $first_term = reset($terms);
        $brand_text = $first_term->name;
    }

    return $brand_text;
}
function clean_description($description) {
    // Удаление всех ссылок и их замена на текст ссылки
    preg_match_all('/<a.*?>(.*?)<\/a>/', $description, $matches);
    foreach ($matches[0] as $index => $match) {
        $replacement = $matches[1][$index];
        $description = str_replace($match, $replacement, $description);
    }

    // Удаление оставшихся HTML тегов
    $description = wp_strip_all_tags($description);

    return $description;
}

function shorten_description($description, $max_length = 150) {
    $description = strip_tags($description); // Удаляем HTML теги
    $description = html_entity_decode($description); // Преобразуем HTML-сущности обратно в соответствующие символы
    $description = trim($description); // Удаляем пробелы в начале и конце строки

    if (mb_strlen($description) > $max_length) {
        // Обрезаем текст до заданной длины
        $short_description = mb_substr($description, 0, $max_length);
        // Обрезаем текст до последнего полного слова
        $last_space_position = mb_strrpos($short_description, ' ');
        $short_description = mb_substr($short_description, 0, $last_space_position);
        // Добавляем троеточие
        $short_description .= '...';
        return $short_description;
    }

    return $description;
}
function add_brand_to_product_schema($data) {

    if (!is_array($data)) {
        // Если $data не является массивом, не делаем ничего.
    } elseif (empty($product = wc_get_product())) {
        // Если продукт пустой, не делаем ничего.
    } elseif (!is_array($brands = wp_get_post_terms($product->get_id(), 'product_brand', array('fields' => 'names')))) {
        // Этот продукт не имеет связанных брендов.
    } elseif (empty($brands)) {
        // У этого продукта нет брендов.
    } elseif (count($brands) == 1) {
        // У этого продукта ровно один бренд.
        $data['brand'] = array('@type' => 'Brand', 'name' => $brands[0]);
    } else {
        // У этого продукта несколько брендов.
        $data['brand'] = array();
        foreach ($brands as $brand) {
            $data['brand'][] = array('@type' => 'Brand', 'name' => $brand);
        }
    }

    // Удаление @id
    unset($data['@id']);

    // Получаем текущий URL
    $current_url = home_url( add_query_arg( null, null ) );

// Извлекаем slug продукта из URL
    $slug = basename(parse_url($current_url, PHP_URL_PATH));

// Ищем ID продукта в slug
    $matches = array();
    if (preg_match('/pid_(\d+)/', $slug, $matches)) {
        $product_id = $matches[1];
        $product = wc_get_product($product_id);

    }
   if ($product instanceof WC_Product) {
         if ($product->is_type('variation')) {
            $product_url = get_custom_product_url($product_id);
            $product_name = get_variation_description($product_id);
            $product_sku = $product->get_sku();

             // Получаем родительский продукт вариации
            $parent_id = $product->get_parent_id();

            $parent_product = wc_get_product($parent_id);
            if ($parent_product instanceof WC_Product) {

                // Получаем описание родительского продукта
                $product_description = clean_description($parent_product->get_short_description());
                $product_description = shorten_description($product_description, 250);
                $product_model = $parent_product->get_name();
            }
        } else {
             if ($product->is_type('variable')) {
                 // Теперь получаем URL родительского продукта
                 $product = wc_get_product();
                 $product_id = $product->get_id();
                 $product_price= $product->get_price();
                 $product_url = get_permalink($product_id);
                 $product_name = $product->get_name() .' цена от '.$product_price.' руб';
                 $product_sku = $product->get_sku();
                 $product_model = $product->get_name();

                 // Получаем описание родительского продукта
                 $product_description = clean_description($product->get_short_description());
                 $product_model = $product->get_name();

                 // Получаем вариации вариативного продукта
                 $variations = $product->get_available_variations();
                 $variation_prices = []; // Массив для хранения цен всех вариаций

                 foreach ($variations as $variation) {
                     $variation_obj = wc_get_product($variation['variation_id']);
                     if ($variation_obj->is_in_stock()) {
                         $variation_prices[] = $variation_obj->get_price(); // Добавляем цену в массив
                     }
                 }

                 // Проверяем, не пуст ли массив цен
                 if (!empty($variation_prices)) {
                     $data['offers']['lowPrice'] = min($variation_prices); // Минимальная цена
                     $data['offers']['highPrice'] = max($variation_prices); // Максимальная цена
                     $data['offers']['offerCount'] = count($variation_prices); // Количество вариаций
                     $data['offers']['priceCurrency'] =  'RUB';
                 } else {
                     // Если вариаций в наличии нет, можно установить значения как false или не добавлять их вообще
                     $data['offers']['lowPrice'] = false;
                     $data['offers']['highPrice'] = false;
                     $data['offers']['offerCount'] = 0;
                     $data['offers']['priceCurrency'] =  'RUB';
                 }

                 // Подготавливаем массив offers
                 $offers = [];
                 unset($data['offers'][0]);
                 $data['offers']['type'] = 'AggregateOffer';
                 foreach ($variations as $variation) {
                     // Получаем объект вариации
                     $variation_obj = wc_get_product($variation['variation_id']);
                     if ($variation_obj->is_in_stock()) {
                         // Собираем информацию о каждом оффере
                         $offer = [
                             'type' => 'Offer',
                             'url' =>  get_custom_product_url($variation_obj->get_id()),
                             'name' => get_variation_description($variation_obj->get_id()),
                             'priceCurrency' => 'RUB',
                             'price' => $variation_obj->get_price(),
                             'availability' => 'http://schema.org/InStock',
                             'itemCondition' => 'http://schema.org/NewCondition',
                             'sku' => $variation_obj->get_sku(),
                             // Добавляем информацию о продавце
                             'seller' => [
                                 '@type' => 'Organization',
                                 'name' => 'Всеспецшины - магазин шин для спецтехники и грузовых автомобилей',
                                 'url' => 'https://vsespecshini.ru/',
                             ]
                         ];

                         // Добавляем оффер в массив
                         $data['offers'][] = $offer;
                     }

                 }
             }
         }
    }
    if(!empty($product_name)){
        $data['name'] = $product_name;
    }
    $data['url'] = $product_url;

    if (isset($data['offers']) && count($data['offers']) > 0 && !$product->is_type('variable')) {
        foreach ($data['offers'] as &$offer) {
            $offer['url'] = $product_url;
            // Устанавливаем состояние товара как новый
            $offer['itemCondition'] = 'http://schema.org/NewCondition';
            $offer['seller']['@type'] = 'Organisation';
            $offer['seller']['name'] = 'Всеспецшины - магазин шин для спецтехники и грузовых автомобилей';
            $offer['seller']['url'] = 'https://vsespecshini.ru/';
        }
        unset($offer); // Разорвать ссылку на последний элемент
    }

    if(!empty($product_sku)){
        $data['sku'] = $product_sku;
    }
    if(!empty($product_description)){
        $data['description'] = $product_description;
    }
    if(!empty($product_model)){
        $data['model'] = $product_model;
    }
    //echo '<!--<pre>';print_r($data); echo '</pre>-->';
    return $data;
}
add_filter('woocommerce_structured_data_product', 'add_brand_to_product_schema');

add_action( 'init', 'register_product_model_taxonomy' );

function register_product_model_taxonomy() {
    $labels = array(
        'name'              => _x( 'Модели', 'taxonomy general name', 'textdomain' ),
        'singular_name'     => _x( 'Модель', 'taxonomy singular name', 'textdomain' ),
        'search_items'      => __( 'Искать Модели', 'textdomain' ),
        'all_items'         => __( 'Все Модели', 'textdomain' ),
        'parent_item'       => __( 'Родительская Модель', 'textdomain' ),
        'parent_item_colon' => __( 'Родительская Модель:', 'textdomain' ),
        'edit_item'         => __( 'Изменить Модель', 'textdomain' ),
        'update_item'       => __( 'Обновить Модель', 'textdomain' ),
        'add_new_item'      => __( 'Добавить Новую Модель', 'textdomain' ),
        'new_item_name'     => __( 'Название Новой Модели', 'textdomain' ),
        'menu_name'         => __( 'Модели', 'textdomain' ),
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'model' ),
    );

    register_taxonomy( 'product_model', array( 'product' ), $args );
}



