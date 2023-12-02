<?php
header("Content-Type: text/html; charset=utf-8");
/*
    Plugin Name: Viribles One Item Filter
    Description: Make One item Variable
    Author: duid from kwork
    Author URI: https://kwork.ru/user/duid
    Version: 1.0
*/

	if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF']))
	{
		die('You are not allowed');
	}

	if(!class_exists('iduidOneItemFilter'))
	{
		class iduidOneItemFilter
		{
			protected static $single_instance	= null;

			public static function get_instance()
			{
				if(null === self::$single_instance)
				{
					self::$single_instance = new self();
				}
				return self::$single_instance;
			}

			function __construct()
			{
				$this->varPref		= "iduidOneItemFilter";
				$this->plugin_name	= plugin_basename(__FILE__);
				$this->plugin_url	= trailingslashit(WP_PLUGIN_URL.'/'.dirname(plugin_basename(__FILE__)));


				register_activation_hook($this->plugin_name, array(&$this, 'activate'));
#				register_deactivation_hook($this->plugin_name, array(&$this, 'deactivate'));

				if(is_admin())
				{
					if(!function_exists('get_plugin_data'))
					{
						require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
					}

					$this->PluginName	= get_plugin_data(__FILE__)['Name'];

					add_action('admin_menu', array(&$this, 'admin_generate_menu'));
					add_action('admin_init', array(&$this, 'initAjax'));
				}

				add_action('init', array(&$this, 'woo_init'), 0);
				add_action( 'woocommerce_product_query', array(&$this, 'woocommerce_product_query') );
			}

			function admin_generate_menu()
			{
				add_options_page('Обновить Вариации', $this->PluginName, 'manage_options', $this->varPref, array(&$this, 'admin_plugin_settings'));
			}

			function woo_init()
			{
				add_action( 'pre_get_posts', function( $query )
				{
					if(!is_admin() && $query->is_main_query() && !is_product() && is_woocommerce() && (is_shop() || is_product_category() || is_product_tag()))
					{
						add_filter( 'posts_clauses', array(&$this, 'posts_clauses'), 10, 2);

//						remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
						remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );

						remove_action( 'woocommerce_shop_loop_item_title', 'ovic_template_loop_product_title', 10 );
						remove_action( 'woocommerce_before_shop_loop_item_title', 'ovic_template_loop_product_thumbnail', 10 );

						add_action( 'woocommerce_before_shop_loop_item_title',  array(&$this, 'ovic_template_loop_product_thumbnail'), 10 );

//remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
//add_action( 'woocommerce_before_shop_loop_item_title', 'ovic_template_loop_product_thumbnail', 10 );



//						remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
//						remove_action( 'ovic_function_shop_loop_item_quickview', 'woocommerce_template_loop_product_thumbnail', 10 );

//						remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
//						remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
//						remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
//						remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );





						add_action( 'woocommerce_shop_loop_item_title', array(&$this, 'woocommerce_template_loop_product_title'), 10 );
//						add_action( 'woocommerce_before_shop_loop_item_title_2', array(&$this, 'woocommerce_template_loop_product_thumbnail'), 10);
					}
				});

				#woocommerce_update_product#woocommerce_new_product#woocommerce_delete_product_variation
				add_action( 'woocommerce_new_product',  array(&$this, 'product_save'), 10, 1 );
				add_action( 'woocommerce_update_product',  array(&$this, 'product_save'), 10, 1 );
				add_action( 'woocommerce_before_delete_product_variation',  array(&$this, 'product_delete'), 10, 1 );
#				add_action( 'woocommerce_shop_loop_item_title', array(&$this, 'woocommerce_template_loop_product_title'), 10, 1 );
				#add_filter( 'woocommerce_show_page_title', function() { return false; } );

#				if(!is_admin() && !is_product() && is_woocommerce() && (is_shop() || is_product_category() || is_product_tag()))
#				{
#					remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
#					remove_action( 'woocommerce_shop_loop_item_title', 'ovic_template_loop_product_title', 10 );
#					add_action( 'woocommerce_shop_loop_item_title', array(&$this, 'woocommerce_template_loop_product_title'), 10 );
#				}
			}

			function product_save( $product_id )
			{
				global $wpdb;

				$product	= wc_get_product( $product_id );
				$children_ids	= $product->get_children();

				foreach($children_ids as $variation_id)
				{
					$wpdb->query( $wpdb->prepare("DELETE FROM $wpdb->term_relationships WHERE object_id = %d", $variation_id) );

					$taxonomies = array('product_cat','product_tag');

					foreach( $taxonomies as $taxonomy )
					{
				                $terms = (array) wp_get_post_terms( $product_id, $taxonomy, array("fields" => "ids") );
				                wp_set_post_terms( $variation_id, $terms, $taxonomy );


						$productVariation = wc_get_product( $variation_id );

						foreach( $productVariation->get_variation_attributes() as $taxonomya => $terms_sluga )
						{
							wp_set_post_terms( $variation_id, $terms_sluga, ltrim($taxonomya,'attribute_') );
						}
					}
				}
			}

			function product_delete( $product_id )
			{
				$product	= wc_get_product( $product_id );
				$children_ids	= $product->get_children();

				foreach($children_ids as $variation_id)
				{
					$taxonomies = array('product_cat','product_tag');

					foreach( $taxonomies as $taxonomy )
					{
				                $terms = (array) wp_get_post_terms( $product_id, $taxonomy, array("fields" => "ids") );
						wp_remove_object_terms( $variation_id, $terms, $taxonomy );
					}
				}
			}

			public function admin_plugin_settings()
			{
				global $pagenow;

				if(!current_user_can('manage_options'))
				{
					$this->showMessage("Не положено ".$this->PluginName, true);
					return;
				}

				wp_register_script('custom_js', $this->plugin_url.'_inc/js/metabox.js', array('jquery'), '', true);

				wp_localize_script('custom_js', $this->varPref, array(
						'ajaxurl'	=> admin_url('admin-ajax.php'),
						'varPref'	=> $this->varPref,
						'nonce' 	=> wp_create_nonce($this->varPref.'_ajquery_nonce'),
						'page'		=> $pagenow
					)
				);

				wp_enqueue_script('custom_js');


				include_once('_inc/tpl/settings.php');
			}

			function woocommerce_product_query($q)
			{
				$q->set( 'post_type', array('product','product_variation') );
				return $q;
			}

			function posts_clauses($clauses, $query)
			{
				global $wpdb;

				$clauses['where'] .= " AND  0 = (select count(*) as totalpart from {$wpdb->posts} as oc_posttb where oc_posttb.post_parent = {$wpdb->posts}.ID and oc_posttb.post_type= 'product_variation') ";
#				$clauses['join'] .= " LEFT JOIN {$wpdb->postmeta} as  oc_posttba ON ({$wpdb->posts}.post_parent = oc_posttba.post_id)";
#				$clauses['where'] .= " AND  ( oc_posttba.meta_value IS NULL OR oc_posttba.meta_value!='yes') ";*/

                        	return $clauses;
			}

			function initAjax()
			{
				add_action('wp_ajax_'.$this->varPref.'ajax-rgen', array(&$this, 'rgenAjax2'));
				add_action('wp_ajax_nopriv_'.$this->varPref.'ajax-rgen', array(&$this, 'rgenAjax2'));
			}

			function rgenAjax2()
			{
				global $wpdb;

				$nonce = $_POST['nextNonce'];
				if(!wp_verify_nonce($nonce, $this->varPref.'_ajquery_nonce'))
				{
					echo json_encode(array("status" => "error", "content" => "nonce"));
					exit();
				}


				$offset = 0;
				$step	= (int)$_POST['nextStep'];

				if($step > 1)$offset=$step*10;

				$xfd		= new WP_Query(array('post_type' => 'product_variation', 'posts_per_page' => -1));
				$post_count	= $xfd->post_count;
				unset($xfd);


				$args = array(
				   	'post_type'		=> 'product_variation',
				   	'posts_per_page' 	=> 10,
					'offset'		=> $offset
				);


				$the_query		= new WP_Query($args);


				if($the_query->have_posts())
				{
					while($the_query->have_posts())
					{
						$the_query->the_post();
						global $post;

						$variation_id = $post->ID;
						$parent_product_id = wp_get_post_parent_id( $variation_id );

						$productId = $variation_id;
						$producta = wc_get_product( $productId );

						$wpdb->query( $wpdb->prepare("DELETE FROM $wpdb->term_relationships WHERE object_id = %d", $variation_id) );


						foreach( $producta->get_variation_attributes() as $taxonomya => $terms_sluga )
						{
							wp_set_post_terms( $productId, $terms_sluga, ltrim($taxonomya,'attribute_') );
						}

						if($parent_product_id)
						{
							$taxonomies = array('product_cat','product_tag');

							foreach( $taxonomies as $taxonomy )
							{
								$terms = (array) wp_get_post_terms( $parent_product_id, $taxonomy, array("fields" => "ids") );
								wp_set_post_terms( $variation_id, $terms, $taxonomy );
							}
						}
					}

					echo json_encode(array("status" => "ok", "next" => "next", "total" => $post_count));
					exit();
				}

				echo json_encode(array("status" => "error", "content" => "unknown"));
				exit();
			}

			function woocommerce_template_loop_product_title()
			{
				global $post;

				$desc = get_post_meta( $post->ID, '_variation_description', true );


				if(empty($desc))
				{
					$desc = get_the_title();
				}



/*				$pa_tyre_width = get_the_terms($post->ID, 'pa_tyre_width' )[0]->name;

				$pa_tyre_width = $pa_tyre_width?$pa_tyre_width:0;

				$pa_tyre_profile = get_the_terms( $post->ID, 'pa_tyre_profile' )[0]->name;

				$pa_tyre_profile = $pa_tyre_profile?$pa_tyre_profile:0;

				$pa_tyre_rim = 	get_the_terms( $post->ID, 'pa_tyre_rim' )[0]->name;

				$pa_tyre_rim = $pa_tyre_rim?$pa_tyre_rim:0;
				echo "<p>{$pa_tyre_width}/{$pa_tyre_profile}/{$pa_tyre_rim}</p>";
the_permalink()
*/
				$uri = add_query_arg ('pid', $post->ID, get_permalink ()) ;

				echo '<h3 class="product-name product_title"><a href="'.$uri.'">'.$desc.'</a></h3>';
			}

			function woocommerce_template_loop_product_thumbnail()
			{
				global $product, $post;

				#$link=get_permalink( $poduct->ID);
				$title = $poduct->post_title;
				$uri = add_query_arg ('pid', $post->ID, get_permalink ()) ;

				echo '<a href="'.$uri.'" title="'.$title.'">'.woocommerce_get_product_thumbnail().'</a>';
			}

			function ovic_template_loop_product_thumbnail()
			{
				global $product, $post;

				$width  = 300;
				$height = 300;
				$crop   = true;
				$size   = wc_get_image_size( 'shop_catalog' );

				if ( $size ) {
					$width  = $size['width'];
					$height = $size['height'];
					if ( !$size['crop'] ) {
						$crop = false;
					}
				}

				$lazy_load          = true;
				$thumbnail_id       = $product->get_image_id();
				$default_attributes = $product->get_default_attributes();
				$width              = apply_filters( 'ovic_shop_product_thumb_width', $width );
				$height             = apply_filters( 'ovic_shop_product_thumb_height', $height );

				if ( !empty( $default_attributes ) )
					$lazy_load = false;
				$image_thumb = apply_filters( 'ovic_resize_image', $thumbnail_id, $width, $height, $crop, $lazy_load );


				$uri = add_query_arg('pid', $post->ID, get_permalink());
				?>
				        <a class="thumb-link woocommerce-product-gallery__image" href="<?php echo $uri; ?>">
				            <figure>
						<?php echo wp_specialchars_decode( $image_thumb['img'] ); ?>
				            </figure>
				        </a>
				<?php
			}


			function activate()
			{
			}
		}

		iduidOneItemFilter::get_instance();
	}