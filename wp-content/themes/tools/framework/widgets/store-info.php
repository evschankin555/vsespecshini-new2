<?php

if ( !class_exists( 'Tools_Store_Info_Widget' ) ) {
    class Tools_Store_Info_Widget extends Tools_Widget
    {
        function __construct(){
            $this->widget_cssclass    = 'tool widget_store_info';
            $this->widget_description = __( "Display the Store Info.", 'tools' );
            $this->widget_id          = 'widget_store_info';
            $this->widget_name        = __( 'Ovic: Store Info', 'tools' );
            $this->settings           = array(
                'title'  => array(
                    'type'  => 'text',
                    'std'   => __( 'Contact Us', 'tools' ),
                    'label' => __( 'Title', 'tools' ),
                ),
                'address'  => array(
                    'type'  => 'textarea',
                    'std'   => __( '45 Grand Central Terminal New York,NY', 'tools' ),
                    'label' => __( 'Address', 'tools' ),
                ),
                'email'  => array(
                    'type'  => 'textarea',
                    'std'   => __( 'support@kutethemes.com', 'tools' ),
                    'label' => __( 'Email', 'tools' ),
                ),
                'phone'  => array(
                    'type'  => 'textarea',
                    'std'   => __( '(+800) 123 456 7890', 'tools' ),
                    'label' => __( 'Phone', 'tools' ),
                ),
            );

            parent::__construct();
        }

        function widget( $args, $instance ){
            $this->widget_start( $args, $instance );
            ?>
            <div class="content">
                <?php if( $instance['address'] !=""):?>
                    <div class="item address">
                        <div class="icon"></div>
                        <div class="info">
                            <span class="head"><?php esc_html_e('Адрес:','tools');?></span>
                            <span class="text"><?php echo $instance['address'];?></span>
                        </div>
                    </div>
                <?php endif;?>
                <?php if( $instance['phone'] !=""):?>
                    <div class="item phone">
                        <div class="icon"></div>
                        <div class="info">
                            <span class="head"><?php esc_html_e('Телефон:','tools');?></span>
                            <span class="text"><?php echo $instance['phone'];?></span>
                        </div>
                    </div>
                <?php endif;?>
                <?php if( $instance['email'] !=""):?>
                    <div class="item email">
                        <div class="icon"></div>
                        <div class="info">
                            <span class="head"><?php esc_html_e('Email:','tools');?></span>
                            <span class="text"><?php echo $instance['email'];?></span>
                        </div>
                    </div>
                <?php endif;?>
            </div>

            <?php
            $this->widget_end( $args );
        }
    }
}

add_action( 'widgets_init', 'Tools_Store_Info_Widget' );
if( !function_exists('Tools_Store_Info_Widget')){
    function Tools_Store_Info_Widget() {
        register_widget( 'Tools_Store_Info_Widget' );
    }
}
