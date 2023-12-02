<?php
header("Content-Type: text/html; charset=utf-8");
/*
    Plugin Name: Easy Ads or Text
    Description: Easily add ads, any text or html. On any page without problems, just a few clicks. Everything is very simple and easy, use flexible display filters. You can even limit the display by the number of views.
    Author: Duid from kwork
    Author URI: https://kwork.ru/user/duid
    Version: 1.1.5
    Text Domain: duidPtAds
    Domain Path: /languages/
*/

	if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF']))
	{
		die('You are not allowed');
	}

	if(!class_exists('duidPtAds'))
	{
		class duidPtAds
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
				global $wpdb;

				$this->varPref		= "dadptplugin";
				$this->plugin_url	= trailingslashit(WP_PLUGIN_URL.'/'.dirname(plugin_basename(__FILE__)));
				$this->tbl_data   	= $wpdb->prefix.'plugin_'.$this->varPref.'_stats_visits';
				$this->plugin_name	= plugin_basename(__FILE__);
				$this->period		= "weekly";

				$this->init_variable();


				register_activation_hook($this->plugin_name, array(&$this, 'activate'));


				add_filter('init', 			array(&$this, 'statistic'), 0);
				add_action('init', 			array(&$this, 'duid_ad_ads_post_type'), 0);
				add_filter('manage_edit-ads_columns', 	array(&$this, 'duid_ad_ads_columns'));
				add_action('add_meta_boxes',		array(&$this, 'add_meta_box_content'));
				add_action('save_post',			array(&$this, 'save_meta_box_content'));
				add_action('dynamic_sidebar_after', 	array(&$this, 'add_after_siderbar'));

				add_shortcode('duid-ads', array(&$this, 'makecontent_shortcode'));
				add_action('plugins_loaded', array(&$this, 'load_text_domain'));
			}

			function load_text_domain()
			{
				load_plugin_textdomain( 'duidPtAds', false, basename( dirname( __FILE__ ) ) . '/languages' );
			}

			function init_variable()
			{
				$this->metaBoxCheckPage = array(
					"is_front_page" => esc_html__("Front Page", 'duidPtAds'),
					"is_home"	=> esc_html__("Home Page", 'duidPtAds'),
					"is_category" 	=> esc_html__("Category Page", 'duidPtAds'),
					"is_archive" 	=> esc_html__("Archive Page", 'duidPtAds'),
					"is_single" 	=> esc_html__("Single Page", 'duidPtAds'),
					"is_page" 	=> esc_html__("Permanent Page", 'duidPtAds')
				);
			}

			function makecontent_shortcode($atts = false)
			{
				$post = get_post((int)$atts['id']);
				setup_postdata($post);

				$content = get_the_content(null, false);
				return str_replace(']]>', ']]&gt;', $content);
			}

			function duid_ad_ads_post_type()
			{
				$labels = array(
					'name'                => esc_html_x('Items', 'Post Type General Name', 'dudid-ads'),
					'menu_name'           => esc_html__('Easy Ads or Text', 'dudid-ads'),
					'all_items'           => esc_html__('Items',  'dudid-ads'),
				);

				$args = array(
					'label'               	=> esc_html__('duid-ad-options', 'dudid-ads'),
					'description'         	=> esc_html__('Ad Post Type', 'dudid-ads'),
					'labels'              	=> $labels,
					'supports'            	=> array('title', 'editor'),
					'taxonomies'          	=> array('category'),
					'hierarchical'        	=> false,
					'public'              	=> true,
					'show_ui'             	=> true,
					'show_in_menu'        	=> true,
					'show_in_nav_menus'   	=> false,
					'show_in_admin_bar'   	=> true,
					'menu_position'      	=> 20,
					'menu_icon'		=> 'dashicons-carrot',
					'can_export'          	=> true,
					'has_archive'         	=> false,
					'exclude_from_search' 	=> true,
					'publicly_queryable'  	=> true,
					'capability_type'     	=> 'post',
				);
				register_post_type('duid-ads', $args);

				###
				wp_register_script('custom_js', $this->plugin_url.'js/main.js', null, null, true);

				wp_localize_script('custom_js', $this->varPref, array(
						'varPref'	=> $this->varPref,
						'homeurl'	=> get_home_url(),
					)
				);

				wp_enqueue_script('custom_js');
			}

			function duid_ad_ads_columns($gallery_columns)
			{
				$new_columns['cb']	= '<input type="checkbox" />';
				$new_columns['title'] 	= esc_html__('Title', 'dudid-ads');
				$new_columns['date']	= esc_html__('Date', 'dudid-ads');

				return $new_columns;
			}

			function add_meta_box_content($post_type)
			{
				$post_types = array('duid-ads');

				if(in_array($post_type, $post_types))
				{
		 			add_meta_box(
						'meta_box_content_all'.$this->varPref,
						__('Side Bars'),
						array($this, 'render_meta_box_allone'),
						$post_type, 'side', 'high'
					);

		 			add_meta_box(
						'meta_box_content_is_check'.$this->varPref,
						__('Check page'),
						array($this, 'render_meta_box_is_check'),
						$post_type, 'side', 'high'
					);

		 			add_meta_box(
						'meta_box_content_count'.$this->varPref,
						__('Counts Ad'),
						array($this, 'render_meta_box_count'),
						$post_type, 'side', 'high'
					);

		 			add_meta_box(
						'meta_box_content_stats'.$this->varPref,
						__('Stats Advertising'),
						array($this, 'render_meta_box_stats'),
						$post_type, 'normal', 'high'
					);

					if($id = get_the_ID())
					{
						add_meta_box('meta_box_short'.$this->varPref, __('Short Code'), array($this, 'cd_meta_box_cb'), $post_type, 'normal', 'high');
					}
				}
			}

			function save_meta_box_content($post_id)
			{
				if(!isset($_POST[$this->varPref.'_query_nonce']))
				{
					return;
				}

				$nonce = $_POST[$this->varPref.'_query_nonce'];

				if(!wp_verify_nonce($nonce, $this->varPref."_meta_box_content"))
				{
					return $post_id;
				}

				if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
				{
					return;
				}

				if(isset($_POST[$this->varPref.'_toside']))
				{
					$lpd = array_map('sanitize_text_field', $_POST[$this->varPref.'_toside']);
					update_post_meta($post_id, $this->varPref.'_toside', serialize($lpd));
				}

				if(isset($_POST[$this->varPref.'_tocheckpage']))
				{
					$lpd = array_map('sanitize_text_field', $_POST[$this->varPref.'_tocheckpage']);
					update_post_meta($post_id, $this->varPref.'_tocheckpage', serialize($lpd));
				}

				if(isset($_POST[$this->varPref.'_countactive']))
				{
					$lpd = isset($_POST[$this->varPref.'_countactive']) ? 'true' : 'false';
					update_post_meta($post_id, $this->varPref.'_countactive', $lpd);
				}else	update_post_meta($post_id, $this->varPref.'_countactive', 'false');

				if(isset($_POST[$this->varPref.'_countviews']))
				{
					update_post_meta($post_id, $this->varPref.'_countviews', (int)$_POST[$this->varPref.'_countviews']);
				}
			}

			function render_meta_box_is_check($post, $meta)
			{
				$post_id	= ($post->ID !== false ? (int)$post->ID : get_the_ID());
				$value 		= get_post_meta($post_id, $this->varPref.'_tocheckpage', true);

				if($value)
				{
					$value = unserialize($value);
				}

				require_once("inc/meta_box_is_check.php");
			}

			function render_meta_box_count($post, $meta)
			{
				$post_id	= ($post->ID !== false ? (int)$post->ID : get_the_ID());

				$Data['countactive']	= get_post_meta($post_id, $this->varPref.'_countactive', true);
				$Data['countviews']	= get_post_meta($post_id, $this->varPref.'_countviews', true);
				require_once("inc/meta_box_count.php");
			}

			function set_period($period = "weekly")
			{
				switch($period)
				{
					case "monthly":
						$this->start_date = date('Y-m-d', strtotime("-1 month"));
						$this->end_date   = date('Y-m-d');
					break;
					case "weekly":
						$this->start_date = date('Y-m-d', strtotime("-6 days"));
						$this->end_date   = date('Y-m-d');
					break;
					case "daily":
						$this->start_date = date('Y-m-d');
						$this->end_date   = date('Y-m-d');
					break;
				}

				return $this->period = $period;
			}

			function ajax_listener()
			{
				if(isset($_POST["period"]) && check_ajax_referer($this->varPref.'_query_nonce'))
				{
					$period = stripslashes($_POST["period"]);
					$this->set_period($period);
					$this->render_meta_box_stats(false,false);
				}
				die();
			}

			function render_meta_box_stats($post, $meta)
			{
				global $wpdb;

				$statical_data 	= false;
				$post_id	= ($post->ID !== false ? (int)$post->ID : get_the_ID());

				$sql		= "SELECT COUNT(DISTINCT ip_address) as cnt FROM `{$this->tbl_data}` WHERE `pid` = '{$post_id}' AND `date` BETWEEN '{$this->start_date}' AND '{$this->end_date}'  ORDER BY NULL;";
				$total		=  $wpdb->get_var($sql);


				$sql		= "SELECT COUNT(DISTINCT ip_address) as cnt, date FROM `{$this->tbl_data}` WHERE `pid` = '{$post_id}' AND `date` BETWEEN '{$this->start_date}' AND '{$this->end_date}' GROUP BY `date` ORDER BY NULL;";
				$Data		=  $wpdb->get_results($sql, ARRAY_A);

				foreach($Data as $dat)
				{
					$statical_data[ $dat['date'] ] = array(
						'visits'       => $dat['cnt'],
					);
				}

				wp_enqueue_script('jquery');
				wp_enqueue_script('yandex-metrica-chart', plugins_url("js/Chart.min.js", __FILE__));

				include("inc/meta_box_stats.php");
			}

			function render_meta_box_allone()
			{
				$this->render_meta_box_content();
			}

			function render_meta_box_content($postID = false)
			{
				$post_id	= ($postID !== false ? (int)$postID : get_the_ID());
				$value 		= get_post_meta($post_id, $this->varPref.'_toside', true);

				if($value)
				{
					$value = unserialize($value);
				}

				ob_start();
				require_once("inc/meta_box_content.php");

				$ret = ob_get_clean();


				if($postID !== false)
				{
					return $ret;
				}
				echo $ret;
			}

			function statistic()
			{
				global $wpdb;


				$this->set_period();

				add_action('wp_ajax_'.$this->varPref.'_actions', array($this, 'ajax_listener'));
				add_action('wp_ajax_nopriv_'.$this->varPref.'_actions', array($this, 'ajax_listener'));

				if(!isset($_GET["{$this->varPref}pid"]))
				{
					return;
				}

				$pid	= (int)$_GET["{$this->varPref}pid"];

				if(get_post_meta($pid, $this->varPref.'_countactive', true) == 'true')
				{

					$client  = @$_SERVER['HTTP_CLIENT_IP'];
					$forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
					$remote  = @$_SERVER['REMOTE_ADDR'];

					if(filter_var($client, FILTER_VALIDATE_IP))
					{
						$ip = $client;
					}elseif(filter_var($forward, FILTER_VALIDATE_IP)){
						$ip = $forward;
					}else $ip = $remote;

					$sql		= "SELECT COUNT(ip_address) as cnt FROM `{$this->tbl_data}` WHERE `pid` = '{$pid}' AND `ip_address` = '{$ip}' AND `date` = '".date("Y-m-d")."'  ;";

					if((int)$wpdb->get_var($sql) == 0)
					{
						$countviews	= (int)get_post_meta($pid, $this->varPref.'_countviews', true);
						update_post_meta($pid, $this->varPref.'_countviews', --$countviews);

						$wpdb->insert($this->tbl_data, array("pid" => $pid, "ip_address" => $ip, "date" => date("Y-m-d")), array('%s', '%s', '%s'));
					}
				}
				exit;
			}

			function add_after_siderbar($name)
			{
				global $wpdb;

				if(is_admin())
				{
					return;
				}


				$cat = false;
				$categories = get_the_category();

				if($categories)
				{
					foreach($categories as $ct)
					{
						$cat[] = $ct->cat_ID;
					}
					$cat = implode(",", $cat);
				}

				if($cat)
				{
					$sql = "SELECT {$wpdb->prefix}postmeta.post_id  FROM `{$wpdb->prefix}postmeta`
							LEFT JOIN {$wpdb->prefix}posts ON {$wpdb->prefix}postmeta.post_id = {$wpdb->prefix}posts.ID
							LEFT JOIN {$wpdb->prefix}term_relationships ON {$wpdb->prefix}postmeta.post_id = {$wpdb->prefix}term_relationships.object_id
						WHERE {$wpdb->prefix}posts.`post_status` = 'publish'
							AND {$wpdb->prefix}term_relationships.`term_taxonomy_id` in ({$cat})

							AND `meta_key` LIKE '{$this->varPref}_toside' AND `meta_value` LIKE '%{$name}%'
						GROUP BY {$wpdb->prefix}postmeta.post_id
						ORDER BY {$wpdb->prefix}postmeta.`post_id` ASC";

				}else{
					$sql = "SELECT post_id FROM `{$wpdb->prefix}postmeta`
							LEFT JOIN {$wpdb->prefix}posts ON {$wpdb->prefix}postmeta.post_id = {$wpdb->prefix}posts.ID
						WHERE {$wpdb->prefix}posts.`post_status` = 'publish'
							AND `meta_key` LIKE '{$this->varPref}_toside'
							AND `meta_value` LIKE '%{$name}%'
						GROUP BY post_id
						ORDER BY `post_id` ASC;";
				}



				$result	= $wpdb->get_results($sql);


				if($result)
				{
					foreach($result as $id)
					{
						$countviews	= get_post_meta($id->post_id, $this->varPref.'_countviews', true);

						if($countviews <= 0 AND get_post_meta($id->post_id, $this->varPref.'_countactive', true) == 'true')
						{
							continue;
						}


						if($is_checkpage = get_post_meta($id->post_id, $this->varPref.'_tocheckpage', true))
						{
							$is_checkpage = unserialize($is_checkpage);
							$is = false;

							foreach($is_checkpage as $as_is)
							{
								if(array_key_exists($as_is, $this->metaBoxCheckPage))
								{
									if($as_is())
									{
										$is =  true;
									}
								}
							}

							if($is == false)
							{
								continue;
							}
						}

						$post = get_post($id->post_id);
						setup_postdata($post);

						$content = get_the_content(null, false);
						echo str_replace(']]>', ']]&gt;', $content);

						if(get_post_meta($id->post_id, $this->varPref.'_countactive', true) == 'true')
						{
							echo "<script>document.addEventListener('DOMContentLoaded',function(){rwt(this, {$id->post_id});});</script>";
						}
					}
				}
				return;
			}

			function cd_meta_box_cb($post)
			{?>
			<div class="inside">
				<p class="description">
					<label>Скопируйте этот шорткод и вставьте его в свои записи, страницы или содержимое текстового виджета:</label>
					<span class="wp-ui-highlight">
						<input type="text" onfocus="this.select();" readonly="readonly" class="large-text code" value="[duid-ads id=&quot;<?php echo get_the_ID();?>&quot; title=&quot;<?php echo esc_html(get_the_title());?>&quot;]"/>
					</span>
				</p>
			</div>
<?php
			}

			function activate()
			{
				global $wpdb;

				require_once(ABSPATH . 'wp-admin/upgrade-functions.php');

				$charset_collate = $wpdb->get_charset_collate();

				$sql_tbl = "CREATE TABLE `{$this->tbl_data}` (`visits_id` int(11) NOT NULL, `pid` bigint(20) NOT NULL, `ip_address` varchar(16) CHARACTER SET utf8 NOT NULL, `date` date NOT NULL ){$charset_collate} ENGINE=InnoDB;";

				if(maybe_create_table($this->tbl_data, $sql_tbl))
				{
					$sql_tbl = "ALTER TABLE `{$this->tbl_data}` ADD PRIMARY KEY (`visits_id`), ADD KEY `pid` (`pid`);";
					$wpdb->query($sql_tbl);

					$sql_tbl = "ALTER TABLE `{$this->tbl_data}` MODIFY `visits_id` int(11) NOT NULL AUTO_INCREMENT;";
					$wpdb->query($sql_tbl);
				}

				return true;
			}

			function uninstall(){}
		}

		duidPtAds::get_instance();
	}