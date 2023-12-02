<?php
if( !class_exists('Tools_VC_Functions')){
    class Tools_VC_Functions{
        public function __construct(){
            add_filter('ovic_add_param_visual_composer',array($this,'add_param'));
            add_filter('Ovic_Shortcode_Tabs',array( $this,'ovic_shortcode_tabs'),10,3);

            // Custom vc_custom_heading
            vc_remove_param( "vc_custom_heading", "text" );
            $attributes = array(
                'type' => 'textarea_raw_html',
                'heading' => __( 'Text', 'tools' ),
                'param_name' => 'text',
                'holder' => 'div',
                'value' => __( 'This is custom heading element', 'tools' ),
                'description' => __( 'Note: If you are using non-latin characters be sure to activate them under Settings/Visual Composer/General Settings.', 'tools' ),
                'dependency' => array(
                    'element' => 'source',
                    'is_empty' => true,
                ),
                'weight' => 1
            );

            vc_add_param( 'vc_custom_heading', $attributes ); // Note: 'vc_message' was used as a base for "Message box" element

            add_filter('shortcode_atts_vc_custom_heading',array( $this,'output_vc_custom_heading'),10,4);

            // param
            $params = array(
                "type" => "attach_image",
                "heading" => __("Image", "tools"),
                "holder" => "div",
                "class" => "",
                "param_name" => "icon_image",
            );

            vc_add_param( 'vc_tta_section', $params );

            //
            add_filter('ovic_url_template_visual_composer',array( $this,'template_visual_composer'),10,1);
        }
        public function output_vc_custom_heading( $out, $pairs, $atts, $shortcode){

            $out['text'] =  rawurldecode( base64_decode( strip_tags( $out['text'] ) ) );
            return $out;

        }
        public function add_param( $params){

            // Tabs Field
            if( isset($params['ovic_tabs']['params'])){
                $params['ovic_tabs']['params'][0] = array(
                    'type'        => 'dropdown',
                    'heading'     => esc_html__( 'Select Layout', 'tools' ),
                    'value'       => array(
                         __('Default','tools') => 'default',
                         __('Layout 01','tools') => 'layout1',
                    ),
                    'default'     => 'default',
                    'admin_label' => true,
                    'param_name'  => 'style',
                );
            }

            //
            if( isset( $params['ovic_progress']['params'])){
                $params['ovic_progress']['params'][] = array(
                    'type'        => 'textfield',
                    'heading'     => esc_html__( 'Title', 'tools' ),
                    'param_name'  => 'title',
                    'admin_label' => true,
                );
            }

            // Icon box
            if( isset( $params['ovic_iconbox']['params'])){
                $params['ovic_iconbox']['params'][0] = array(
                    'type'        => 'dropdown',
                    'heading'     => esc_html__( 'Select style', 'ovic-toolkit' ),
                    'value'       => array(
                        esc_html__( 'Default', 'tools' ) => 'default',
                        esc_html__( 'Layout 1', 'tools' ) => 'layout1',
                        esc_html__( 'Layout 2', 'tools' ) => 'layout2',
                        esc_html__( 'Layout 3', 'tools' ) => 'layout3',
                    ),
                    'default'     => 'default',
                    'admin_label' => true,
                    'param_name'  => 'style',
                );
            }



            return $params;
        }

        public function template_visual_composer( $url ){
            $url = 'http://tools.kutethemes.com/wp-content/uploads/template.txt';
            return  $url;
        }

        public function  ovic_shortcode_tabs($html, $atts, $content){
            $atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( 'ovic_tabs', $atts ) : $atts;
            extract( $atts );
            $css_class    = array( 'ovic-tabs' );
            $css_class[]  = $atts['style'];
            $css_class[]  = $atts['el_class'];
            $class_editor = isset( $atts['css'] ) ? vc_shortcode_custom_css_class( $atts['css'], ' ' ) : '';
            $css_class[]  = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_editor, 'ovic_tabs', $atts );

            $Ovic_Shortcode_Tabs = new  Ovic_Shortcode_Tabs();
            $sections     = $Ovic_Shortcode_Tabs->get_all_attributes( 'vc_tta_section', $content );
            $rand         = uniqid();
            $owl_atts                   = array(
                'owl_loop'         => false,
                'owl_slide_margin' => 2,
                'owl_focus_select' => true,
                'owl_ts_items'     => 2,
                'owl_xs_items'     => 3,
                'owl_sm_items'     => 5,
                'owl_md_items'     => 7,
                'owl_lg_items'     => 9,
                'owl_ls_items'     => 9
            );
            $owl_settings         = apply_filters( 'ovic_carousel_data_attributes', 'owl_', $owl_atts );
            ob_start(); ?>
            <div class="<?php echo esc_attr( implode( ' ', $css_class ) ); ?>">
                <?php if ( $sections && is_array( $sections ) && count( $sections ) > 0 ): ?>
                    <div class="tab-head">
                        <?php if ( $atts['tab_title'] ): ?>
                            <h2 class="ovic-title">
                                <span class="text"><?php echo esc_html( $atts['tab_title'] ); ?></span>
                            </h2>
                        <?php endif; ?>
                        <?php if($atts['style'] =='layout1'):?>
                        <ul class="tab-link owl-slick" <?php echo esc_attr( $owl_settings ); ?>>
                        <?php else:?>
                        <ul class="tab-link">
                        <?php endif;?>
                            <?php foreach ( $sections as $key => $section ) :
                                /* Get icon from section tabs */
                                $section['i_type']  = isset( $section['i_type'] ) ? $section['i_type'] : 'fontawesome';
                                $add_icon           = isset( $section['add_icon'] ) ? $section['add_icon'] : '';
                                $position_icon      = isset( $section['i_position'] ) ? $section['i_position'] : '';
                                if ( isset($section['icon_image']) && $section['icon_image'] > 0){
                                    ob_start();
                                    if( has_filter('ovic_resize_image')){
                                        $image_thumb = apply_filters( 'ovic_resize_image', $section['icon_image'], false, false, false,true );
                                        if( isset($image_thumb['img']) && $image_thumb['img'] !=""){
                                            $thumb = $image_thumb['img'];
                                        }
                                    }else{
                                        if(has_post_thumbnail()){
                                            $thumb = wp_get_attachment_image($section['icon_image'],'full');
                                        }
                                    }
                                    ?>
                                    <span class="icon-image"><?php echo $thumb;?></span>
                                    <?php
                                    $icon_html = ob_get_clean();
                                }else{
                                    $icon_html          = $this->constructIcon( $section );
                                }

                                ?>
                                <li class="<?php if ( $key == $atts['active_section'] ): ?>active<?php endif; ?>">
                                    <a class="<?php echo $key == $atts['active_section'] ? 'loaded' : ''; ?>"
                                       data-ajax="<?php echo esc_attr( $atts['ajax_check'] ) ?>"
                                       data-animate="<?php echo esc_attr( $atts['css_animation'] ); ?>"
                                       data-section="<?php echo esc_attr( $section['tab_id'] ); ?>"
                                       data-id="<?php echo get_the_ID(); ?>"
                                       href="#<?php echo esc_attr( $section['tab_id'] ); ?>-<?php echo esc_attr( $rand ); ?>">
                                        <?php echo ( 'true' === $add_icon && 'right' != $position_icon ) ? $icon_html : ''; ?>
                                        <span class="text"><?php echo esc_html( $section['title'] ); ?></span>
                                        <?php echo ( 'true' === $add_icon && 'right' === $position_icon ) ? $icon_html : ''; ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <div class="tab-container">
                        <?php foreach ( $sections as $key => $section ): ?>
                            <div class="tab-panel <?php if ( $key == $atts['active_section'] ): ?>active<?php endif; ?>"
                                 id="<?php echo esc_attr( $section['tab_id'] ); ?>-<?php echo esc_attr( $rand ); ?>">
                                <?php if ( $atts['ajax_check'] == '1' ) :
                                    echo $key == $atts['active_section'] ? do_shortcode( $section['content'] ) : '';
                                else :
                                    echo do_shortcode( $section['content'] );
                                endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            <?php
            $html = ob_get_clean();
            return $html;
        }
        /* do_action( 'vc_enqueue_font_icon_element', $font ); // hook to custom do enqueue style */
        function constructIcon( $section )
        {
            vc_icon_element_fonts_enqueue( $section['i_type'] );
            $class = 'vc_tta-icon';
            if ( isset( $section['i_icon_' . $section['i_type']] ) ) {
                $class .= ' ' . $section['i_icon_' . $section['i_type']];
            } else {
                $class .= ' fa fa-adjust';
            }

            return '<i class="' . $class . '"></i>';
        }
    }

    new Tools_VC_Functions();
}

add_filter( 'vc_iconpicker-type-fontawesome', 'tools_vc_iconpicker_type_fontawesome' );

function tools_vc_iconpicker_type_fontawesome( $icons ){

    $fonts = array(
        'Font of theme'=>array(
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
        )
    );
    return array_merge( $icons, $fonts );
}