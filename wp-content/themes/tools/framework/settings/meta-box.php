<?php
if( !class_exists('Tools_Meta_Box')){
    class Tools_Meta_Box{
        public function  __construct(){
            add_filter('ovic_registered_metabox_settings', array( $this,'set_meta_box'),11,1);
        }

        public function set_meta_box( $options){
            
            return $options;
        }
    }
    new Tools_Meta_Box();
}