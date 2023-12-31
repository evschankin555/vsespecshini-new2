<?php
if ( !defined( 'ABSPATH' ) ) {
    die( '-1' );
}
/**
 * Shortcode attributes
 * @var $atts
 * Shortcode class
 * @var $this "Ovic_Progress"
 */
if ( !class_exists( 'Ovic_Shortcode_Progress' ) ) {
    class Ovic_Shortcode_Progress extends Ovic_Shortcode
    {
        /**
         * Shortcode name.
         *
         * @var  string
         */
        public $shortcode = 'progress';

        public function output_html( $atts, $content = null )
        {
            $atts = function_exists( 'vc_map_get_attributes' ) ? vc_map_get_attributes( 'ovic_progress', $atts ) : $atts;
            $atts = $this->convertAttributesToNewProgressBar( $atts );

            wp_enqueue_script( 'waypoints' );
            extract( $atts );
            $css_class    = array( 'ovic-progress vc_progress_bar wpb_content_element' );
            $css_class[]  = $atts['el_class'];
            $class_editor = isset( $atts['css'] ) ? vc_shortcode_custom_css_class( $atts['css'], ' ' ) : '';
            $css_class[]  = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $class_editor, 'ovic_progress', $atts );
            /* START CONTENT */
            $values           = (array)vc_param_group_parse_atts( $atts['values'] );
            $max_value        = 0.0;
            $graph_lines_data = array();
            ob_start(); ?>
            <div class="<?php echo esc_attr( implode( ' ', $css_class ) ); ?>">
                <?php if( isset($atts['title']) && $atts['title']!=""):?>
                    <h3 class="title"><span><?php echo esc_html($atts['title']);?></span></h3>
                <?php endif;?>
                <?php
                foreach ( $values as $data ) {
                    $new_line = $data;
                    if ( $max_value < (float)$new_line['percent'] )
                        $max_value = $new_line['percent'];
                    $graph_lines_data[] = $new_line;
                }
                foreach ( $graph_lines_data as $line ) :
                    ?>
                    <div class="item">
                    <?php
                    $percentage_value = $line['percent'];
                    if ( $max_value > 100.00 )
                        $percentage_value = (float)$line['percent'] > 0 && $max_value > 100.00 ? round( (float)$line['percent'] / $max_value * 100, 4 ) : 0;
                    if ( isset( $data['title'] ) ): ?>
                        <h4><?php echo esc_html( $line['title'] ); ?></h4>
                    <?php endif; ?>
                    <div class="vc_general vc_single_bar">
                        <p class="vc_bar"
                           data-percentage-value="<?php echo esc_html( $percentage_value ); ?>"
                           data-value="<?php echo esc_html( $line['percent'] ); ?>">
                            <span class="vc_label_units"><?php echo esc_html( $line['percent'] ); ?>%</span>
                        </p>
                        <span class="vc_bar animated"
                              data-percentage-value="<?php echo esc_html( $percentage_value ); ?>"
                              data-value="<?php echo esc_html( $line['percent'] ); ?>">
                        </span>
                    </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php
            $html = ob_get_clean();

            return apply_filters( 'Ovic_Shortcode_Progress', $html, $atts, $content );
        }
    }

    new Ovic_Shortcode_Progress();
}