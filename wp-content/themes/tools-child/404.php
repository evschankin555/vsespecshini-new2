<?php get_header();?>
    <div class="main-container page-404-wapper">
        <div class="container">
            <div class="text-center page-404">
                <h1 class="heading"><?php esc_html_e('Где моя страница?','Всеспецшины.Ру');?></h1>
                <div class="text">
                    <p><?php esc_html_e('Нам очень жаль, но страницы, которую вы ищете, не существует.','Вспеспецшины');?></p>

                    <?php
                    printf( __('Вы могли бы вернуться к <a href="%s">Главная</a> или с помощью поиска!','Вспеспецшины'), esc_url( get_home_url('/') ) );
                    ?>
                </div>
            </div>

        </div>
    </div>
<?php get_footer();?>