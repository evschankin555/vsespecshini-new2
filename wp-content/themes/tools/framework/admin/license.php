<?php
if( !class_exists('Tools_License')){
    class  Tools_License{
        public $key = 'theme_license';
        public $options = array();
        public $store_url ='http://kutethemes.com/';

        public function __construct(){


        }


    }

    new Tools_License();
}