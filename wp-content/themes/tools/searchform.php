<form role="search" method="get" class="search-form" action="<?php echo home_url( '/' ); ?>">
    <input type="search" class="search-field" placeholder="<?php echo esc_attr_x( 'Search here', 'tools' ) ?>" value="<?php echo get_search_query() ?>" name="s">
    <button><i class="fa fa-search" aria-hidden="true"></i></button>
</form>