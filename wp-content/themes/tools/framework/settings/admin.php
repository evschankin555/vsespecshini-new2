<?php
if( !class_exists('Tools_Admin')){
    class  Tools_Admin{
        public  function __construct(){
            add_filter('ovic_dashboard_support_tab_content',array($this,'ovic_dashboard_support_tab_content'));
        }

        public function ovic_dashboard_support_tab_content( $content ){
            ob_start();
            ?>
            <div class="rp-row support-tabs">
                <div class="rp-col">
                    <div class="support-item">
                        <h3><?php esc_html_e( 'Documentation', 'tools' ); ?></h3>
                        <p><?php esc_html_e( 'Here is our user guide for Tools, including basic setup steps, as well as Tools features and elements for your reference.', 'tools' ); ?></p>
                        <a target="_blank" href="https://help.kutethemes.com/docs/tools/"
                           class="button button-primary"><?php esc_html_e( 'Read Documentation', 'tools' ); ?></a>
                    </div>
                </div>
                <div class="rp-col closed">
                    <div class="support-item">
                        <h3><?php esc_html_e( 'Video Tutorials', 'tools' ); ?></h3>
                        <p class="coming-soon"><?php esc_html_e( 'Video tutorials is the great way to show you how to setup Tools theme, make sure that the feature works as it\'s designed.', 'tools' ); ?></p>
                        <a href="#"
                           class="button button-primary disabled"><?php esc_html_e( 'See Video', 'tools' ); ?></a>
                    </div>
                </div>
                <div class="rp-col">
                    <div class="support-item">
                        <h3><?php esc_html_e( 'Forum', 'tools' ); ?></h3>
                        <p><?php esc_html_e( 'Can\'t find the solution on documentation? We\'re here to help, even on weekend. Just click here to start chatting with us!', 'tools' ); ?></p>
                        <a target="_blank" href="http://kutethemes.com/supports/"
                           class="button button-primary"><?php esc_html_e( 'Request Support', 'tools' ); ?></a>
                    </div>
                </div>
            </div>
            <?php
            $content = ob_get_clean();
            return $content;
        }
    }
    new  Tools_Admin();

}