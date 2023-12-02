<?php
if( !class_exists('Tools_Theme_Functions')){
    class Tools_Theme_Functions{
        /**
         * @var Tools_Theme_Functions The one true Tools_Theme_Functions
         * @since 1.0
         */
        private static $instance;

        public static function instance(){

            if ( !isset( self::$instance ) && !( self::$instance instanceof Tools_Theme_Functions ) ) {
                self::$instance = new Tools_Theme_Functions;
            }
            add_filter('breadcrumb_trail_args', array(self::$instance,'breadcrumb_settings'));
            add_filter( 'body_class', array(self::$instance,'body_class'),10,1);
            add_filter( 'wp_nav_menu_items', array(self::$instance,'add_user_links'), 999, 2 );

            add_action('tools_header_mobile_search_block',array( self::$instance,'header_mobile_search_form'));
            add_action('tools_header_mobile_control_block',array( self::$instance,'header_mobile_language'));
            add_action('tools_header_mobile_control_block',array( self::$instance,'header_mobile_currency'));

            add_filter( 'script_loader_src',array(self::$instance,'remove_script_version'), 15, 1 );
            add_filter( 'style_loader_src',array(self::$instance,'remove_script_version'), 15, 1 );
            if( !is_admin()){
                add_filter( 'clean_url', array(self::$instance,'defer_parsing_of_js'), 11, 1 );
            }

            add_filter('ovic_menu_icons_setting',array(self::$instance,'ovic_menu_icons_setting'));




            return self::$instance;
        }


        /* Pagination */
        public static function pagination(){
            global $wp_query, $wp_rewrite;
            // Don't print empty markup if there's only one page.
            if ( $wp_query->max_num_pages < 2 ) {
                return;
            }
            echo get_the_posts_pagination( array(
                'screen_reader_text' => '&nbsp;',
                'before_page_number' => '',
                'prev_text'          => '<i class="fa fa-angle-left" aria-hidden="true"></i>',
                'next_text'          => '<i class="fa fa-angle-right" aria-hidden="true"></i>',
            ) );
        }
        public static  function comment_pagination() {
            // Are there comments to navigate through?
            if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :
                ?>
                <nav class="navigation comment-navigation" role="navigation">
                    <h2 class="screen-reader-text"><?php esc_html_e( 'Comment navigation', 'tools' ); ?></h2>
                    <div class="nav-links">
                        <?php
                        if ( $prev_link = get_previous_comments_link( esc_html__( 'Older Comments', 'tools' ) ) ) :
                            printf( '<div class="nav-previous">%s</div>', $prev_link );
                        endif;
                        if ( $next_link = get_next_comments_link( esc_html__( 'Newer Comments', 'tools' ) ) ) :
                            printf( '<div class="nav-next">%s</div>', $next_link );
                        endif;
                        ?>
                    </div><!-- .nav-links -->
                </nav><!-- .comment-navigation -->
                <?php
            endif;
        }

        public static function  custom_comment( $comment, $args, $depth ){
            if ( 'div' === $args[ 'style' ] ) {
                $tag       = 'div ';
                $add_below = 'comment';
            } else {
                $tag       = 'li ';
                $add_below = 'div-comment';
            }
            ?>
            <<?php echo esc_attr( $tag ); ?><?php comment_class( empty( $args[ 'has_children' ] ) ? '' : 'parent' ) ?> id="comment-<?php comment_ID() ?>">
            <?php if ( 'div' != $args[ 'style' ] ) : ?>
                <div id="div-comment-<?php comment_ID() ?>" class="comment-body">
            <?php endif; ?>

            <div class="comment-content">
                <div class="comment-avatar">
                   <?php if ( $args[ 'avatar_size' ] != 0 ) echo get_avatar( $comment, $args[ 'avatar_size' ] ); ?>
                </div>
                <div class="comment-text">
                    <div class="head">
                        <span class="author"><?php echo get_comment_author(); ?></span>
                        <span class="date">
                            <?php
                            /* translators: 1: date, 2: time */
                            printf( esc_html__( '%1$s at %2$s', 'tools' ), get_comment_date(), get_comment_time() ); ?>
                        </span>
                        <?php comment_reply_link( array_merge( $args, array( 'add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args[ 'max_depth' ], 'reply_text' => esc_html__( 'Reply', 'tools' ) ) ) ); ?>
                    </div>
                    <?php if ( $comment->comment_approved == '0' ) : ?>
                        <em class="comment-awaiting-moderation"><?php echo esc_html__( 'Your comment is awaiting moderation.', 'tools' ); ?></em>
                        <br/>
                    <?php endif; ?>
                    <?php comment_text(); ?>
                </div>
            </div>
            <?php if ( 'div' != $args[ 'style' ] ) : ?>
                </div>
            <?php endif; ?>
            <?php
        }
        

        public static function post_thumb(){
            $tools_blog_layout = Tools_Functions::get_option('ovic_sidebar_blog_layout','left');
            $ovic_sidebar_single_layout = Tools_Functions::get_option('ovic_sidebar_single_layout','left');
            if( $tools_blog_layout =='full' ){
                $width = 1170;
                $height = 700;
            }else{
                $width = 870;
                $height = 540;
            }

            if( is_single()){
                $width = 870;
                $height = 540;

                if( $ovic_sidebar_single_layout =='full'){
                    $width = 1170;
                    $height = 700;
                }
            }
            $thumb = '';

            $crop = true;
            if( has_filter('ovic_resize_image')){
                $image_thumb = apply_filters( 'ovic_resize_image', get_post_thumbnail_id(), $width, $height, $crop );
                if( isset($image_thumb['img']) && $image_thumb['img'] !=""){
                    $thumb = $image_thumb['img'];
                }
            }else{
                if(has_post_thumbnail()){
                    $thumb = get_the_post_thumbnail();
                }
            }

            if( $thumb!=""){
                ?>
                <div class="post-thumb">
                    <?php
                    if( is_single()){
                        echo $thumb;
                    }else{
                    ?>
                        <a href="<?php the_permalink();?>"><?php echo $thumb;?></a>
                    <?php
                    }
                    ?>
                </div>
                <?php

            }

        }
        
        public static function  get_logo( $type ='main'){

            $logo_url = get_template_directory_uri() . '/assets/images/logo.png';

            $logo_id = Tools_Functions::get_option('ovic_logo',0);
            if( $type =='mobile'){
                $logo_id = Tools_Functions::get_option('ovic_logo_mobile',0);
            }

            if( $logo_id > 0){
                $logo_url = wp_get_attachment_url($logo_id);
            }
    
            $html = '<a href="'.esc_url( get_home_url() ).'"><img alt="'.esc_attr( get_bloginfo('name') ).'" src="'.esc_url($logo_url).'" class="_rw" /></a>';
            echo apply_filters( 'tools_site_logo', $html );
        }


        /* Breadcrumb Settings */
        public function breadcrumb_settings( $args ){
            $args['show_browse'] = false;
            return $args;
        }

        public  function body_class( $classes ){
            if( class_exists('Breadcrumb_Trail')){
                $classes[] ='has-breadcrumb';
            }
            return $classes;
        }

        function add_user_links ( $items, $args ) {

            if ( $args->theme_location == 'top_right_menu') {
                ob_start();
                get_template_part( 'template-parts/header','userlink');
                $html = ob_get_clean();
                $items = $this->header_language().$items;
                $items = $items.$this->userlink();
            }
            return $items;
        }

        public function userlink(){
            ob_start();
            get_template_part( 'template-parts/header','userlink');
            $html = ob_get_clean();
            return $html;
        }

        function header_language(){
            $current_language = '';
            $list_language    = '';
            $menu_language    = '';
            $languages        = apply_filters( 'wpml_active_languages', NULL, 'skip_missing=0' );
            if ( !empty( $languages ) ) {
                foreach ( $languages as $l ) {
                    if ( !$l['active'] ) {
                        $list_language .= '
						<li class="menu-item">
                            <a href="' . esc_url( $l['url'] ) . '">
                                <img class="icon" src="' . esc_url( $l['country_flag_url'] ) . '"
                                     alt="' . esc_attr( $l['language_code'] ) . '" />
								' . esc_html( $l['native_name'] ) . '
                            </a>
                        </li>';
                    } else {
                        $current_language = '
						<a href="' . esc_url( $l['url'] ) . '" data-electronics="electronics-dropdown">
                            <img class="icon" src="' . esc_url( $l['country_flag_url'] ) . '"
                                 alt="' . esc_attr( $l['language_code'] ) . '" />
							' . esc_html( $l['native_name'] ) . '
                        </a>
                        <span class="toggle-submenu"></span>';
                    }
                }
                $menu_language = '
                 <li class="ovic-dropdown block-language menu-item-has-children">
                    ' . $current_language . '
                    <ul class="sub-menu">
                        ' . $list_language . '
                    </ul>
                </li>';
            }
            ob_start();
            ?>
            <li class="menu-item-has-children menu-item">
                <?php do_action( 'wcml_currency_switcher', array( 'format' => '%code%', 'switcher_style' => 'wcml-dropdown' ) );?>
            </li>
            <?php

            echo htmlspecialchars_decode( $menu_language );
            $html = ob_get_clean();
            return apply_filters('tool_header_laguage_html',$html);
        }

        public function header_mobile_language(){
            $current_language = '';
            $list_language    = '';
            $menu_language    = '';
            $languages        = apply_filters( 'wpml_active_languages', NULL, 'skip_missing=0' );
            if( !empty($languages)){
                ?>
                <div class="block-sub-item">
                    <h5 class="block-item-title"><?php esc_html_e('Languages','tools');?></h5>
                    <ul>
                    <?php
                    foreach ( $languages as $l ) {
                        ?>
                        <li>
                            <a href="<?php echo esc_url($l['url'] ) ?>">
                                <img class="icon" src="<?php echo esc_url( $l['country_flag_url']);?>" alt="">
                                <span class="text"><?php echo $l['native_name'];?></span>
                            </a>
                        </li>
                        <?php
                    }
                    ?>
                    </ul>
                </div>
                <?php
            }
        }

        public function header_mobile_currency(){
            ?>
            <div class="block-sub-item">
                <h5 class="block-item-title"><?php esc_html_e('Currency','tools');?></h5>
                <?php do_action( 'wcml_currency_switcher', array( 'format' => '%code%', 'switcher_style' => 'wcml-vertical-list' ) );?>
            </div>
            <?php
        }

        public function header_mobile_search_form(){
            ?>
            <form method="get" action="<?php echo esc_url( home_url( '/' ) ) ?>" class="form-search">

                <div class="serach-box results-search">
                    <input autocomplete="off" type="text" class="serchfield txt-livesearch"  name="s" value ="<?php echo esc_attr( get_search_query() );?>"  placeholder="<?php esc_html_e('Search entire store here...','tools');?>">
                    <?php if( class_exists( 'WooCommerce' ) ): ?>
                        <input type="hidden" name="post_type" value="product" />
                    <?php endif; ?>
                </div>

            </form>
            <?php
        }
        public function remove_script_version( $src ){
            if( strpos($src,'?ver=')){
                $parts = explode( '?ver=', $src );
                return $parts[0];
            }elseif (strpos($src,'?version=')){
                $parts = explode( '?version=', $src );
                return $parts[0];
            }else{
                return $src;
            }

        }
        function defer_parsing_of_js ( $url ) {
            if ( FALSE === strpos( $url, '.js' ) ) return $url;
            if ( strpos( $url, 'jquery.js' ) ) return $url;

            return $url."' defer='defer";
        }
        public function  ovic_menu_icons_setting( $icons ){

            $fonts = array(
                array( 'font-icon-tools-01' => 'Font Icon Tools 01' ),
                array( 'font-icon-tools-02' => 'Font Icon Tools 02' ),
                array( 'font-icon-tools-03' => 'Font Icon Tools 03' ),
                array( 'font-icon-tools-04' => 'Font Icon Tools 04' ),
                array( 'font-icon-tools-05' => 'Font Icon Tools 05' ),
                array( 'font-icon-tools-06' => 'Font Icon Tools 06' ),
                array( 'font-icon-tools-07' => 'Font Icon Tools 07' ),
                array( 'font-icon-tools-08' => 'Font Icon Tools 08' ),
                array( 'font-icon-tools-09' => 'Font Icon Tools 09' ),
                array( 'font-icon-tools-10' => 'Font Icon Tools 10' ),
            );

            $icons = array_merge($icons,$fonts);
            return $icons;
        }

    }
}

if( !function_exists('Tools_Theme_Functions')){
    function Tools_Theme_Functions(){
        return Tools_Theme_Functions::instance();
    }
    Tools_Theme_Functions();
}