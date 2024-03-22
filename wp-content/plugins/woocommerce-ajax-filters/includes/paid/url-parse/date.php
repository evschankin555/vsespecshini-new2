<?php
if( ! class_exists('BeRocket_url_parse_page_date') ) {
    class BeRocket_url_parse_page_date {
        public $main_class;
        function __construct() {
            add_action('bapf_class_ready', array($this, 'init'), 10, 1);
            add_filter('bapf_uparse_func_check_attribute_name', array($this, 'name'), 100, 3);
            add_filter('bapf_uparse_func_check_attribute_values', array($this, 'values'), 100, 6);
            add_filter('bapf_uparse_query_custom_args_each', array($this, 'custom_args'), 100, 4);
        }
        public function init($BeRocket_AAPF) {
            $this->main_class = $BeRocket_AAPF;
        }
        public function name($result, $instance, $attribute_name) {
            $stock_status_taxonomy = apply_filters('bapf_uparse_stock_status_taxonomy', '_date');
            if( $result === null && $attribute_name == $stock_status_taxonomy ) {
                $result = array(
                    'taxonomy' => 'date',
                    'type'     => 'date'
                );
            }
            return $result;
        }
        public function values($result, $instance, $values_line, $taxonomy, $filter, $args) {
            if( $result === null && isset($filter['type']) && $filter['type'] == 'date' ) {
                $error = array(
                    'error' => new WP_Error( 'bapf_uparse', __('Incorrect data for date: ', 'BeRocket_AJAX_domain').$values_line )
                );
                $values = explode('_', $values_line);
                if( count($values) == 2 && strlen($values[0]) == 8 && strlen($values[1]) == 8 && $values[0] <= $values[1] ) {
                    $from_date = substr($values[0], 4, 2).'/'.substr($values[0], 6, 2).'/'.substr($values[0], 0, 4);
                    $to_date = substr($values[1], 4, 2).'/'.substr($values[1], 6, 2).'/'.substr($values[1], 0, 4);
                    $from = date('Y-m-d 00:00:00', strtotime($from_date));
                    $to = date('Y-m-d 23:59:59', strtotime($to_date));
                    $result = array(
                        'values'    => array('from' => $from, 'to' => $to),
                        'operator'  => $instance->func_delimiter_to_operator('_')
                    );
                } else { return $error; }
            }
            return $result;
        }
        public function custom_args($result, $instance, $filter, $query_vars) {
            if( $result === null && isset($filter['type']) && $filter['type'] == 'date' && ! empty($filter['val_arr']['from']) && ! empty($filter['val_arr']['to']) ) {
                $result = array(
                    'date_query' => array(
                        'after'  => $filter['val_arr']['from'],
                        'before' => $filter['val_arr']['to'],
                    )
                );
            }
            return $result;
        }
	}
	new BeRocket_url_parse_page_date();
}