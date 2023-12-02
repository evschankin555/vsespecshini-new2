;(function ($) {
    "use strict";

    var TOOL_THEME = {
        init: function () {
            this.ovic_backtotop();
            this.ovic_chosen();
            this.woo_quantily();
            this.hover_product_item();
            this.product_countdown();
            this.mobile_block();
            this.header_sticky();
            this.quick_view_slide();
            this.yith_wcwl_ajax_update_count();

        },
        onResize: function () {
            this.ovic_chosen();
        },
        onChange: function () {
        },
        ajaxComplete: function () {
            this.product_countdown();
            this.quick_view_slide();

        },
        scroll: function () {
            if ( $(window).scrollTop() > 1000 ) {
                $('.backtotop').addClass('show');
            } else {
                $('.backtotop').removeClass('show');
            }
        },
        ovic_backtotop: function () {
            $(document).on('click', 'a.backtotop', function () {
                $('html, body').animate({scrollTop: 0}, 800);
                return false;
            });
        },
        ovic_chosen: function () {

            if ( $('.shop-control select').length > 0 ) {
                $('.shop-control select').chosen(
                    {disable_search_threshold: 20}
                );
            }
            if ( $('.categori-search-option').length > 0 ) {
                $('.categori-search-option').chosen(
                    {disable_search_threshold: 20}
                );
            }

        },
        woo_quantily: function () {

            $('body').on('click', '.quantity .quantity-plus', function () {
                var obj_qty  = $(this).closest('.quantity').find('input.qty'),
                    val_qty  = parseInt(obj_qty.val()),
                    min_qty  = parseInt(obj_qty.attr('min')),
                    max_qty  = parseInt(obj_qty.attr('max')),
                    step_qty = parseInt(obj_qty.attr('step'));
                val_qty      = val_qty + step_qty;
                if ( max_qty && val_qty > max_qty ) {
                    val_qty = max_qty;
                }
                obj_qty.val(val_qty);
                obj_qty.trigger("change");
                return false;
            });

            $('body').on('click', '.quantity .quantity-minus', function () {
                var obj_qty  = $(this).closest('.quantity').find('input.qty'),
                    val_qty  = parseInt(obj_qty.val()),
                    min_qty  = parseInt(obj_qty.attr('min')),
                    max_qty  = parseInt(obj_qty.attr('max')),
                    step_qty = parseInt(obj_qty.attr('step'));
                val_qty      = val_qty - step_qty;
                if ( min_qty && val_qty < min_qty ) {
                    val_qty = min_qty;
                }
                if ( !min_qty && val_qty < 0 ) {
                    val_qty = 0;
                }
                obj_qty.val(val_qty);
                obj_qty.trigger("change");
                return false;
            });
        },
        hover_product_item: function () {
            $(document).on('mouseover', '.product-list-owl .product-item, .product-grid .owl-products .product-item', function () {
                $(this).closest('.slick-list').css({
                    'padding-bottom': '300px',
                    'margin-bottom': '-300px',
                    'padding-left': '10px',
                    'margin-left': '-10px',
                    'padding-right': '10px',
                    'margin-right': '-10px',
                    'z-index': '100',
                });
            });
            $(document).on('mouseout', '.product-list-owl .product-item, .product-grid .owl-products .product-item', function () {
                $(this).closest('.slick-list').css({
                    'padding-bottom': '0',
                    'margin-bottom': '0',
                    'padding-left': '0',
                    'margin-left': '0',
                    'padding-right': '0',
                    'margin-right': '0',
                    'z-index': '0',
                });
            });
        },
        product_countdown: function () {
            if ( $('.ovic-countdown').length > 0 ) {
                $('.ovic-countdown').each(function () {
                    var time = $(this).data('datetime');
                    $(this).countdown(time, function (event) {
                        $(this).html(event.strftime('<span class="box-count days"><span class="num">%D</span> <span class="text">' + tools_global_frontend.days_text + '</span></span> <span class="box-count hrs"><span class="num">%H</span><span class="text">' + tools_global_frontend.hrs_text + '</span></span> <span class="box-count min"><span class="num">%M</span><span class="text">' + tools_global_frontend.mins_text + '</span></span> <span class="box-count secs"><span class="num">%S</span><span class="text">' + tools_global_frontend.secs_text + '</span></span>'));
                    })
                })
            }
        },
        yith_wcwl_ajax_update_count: function () {
            $(window).on('added_to_wishlist removed_from_wishlist', function () {
                var counter = $('.block-wishlist .count');
                var data    = {
                    action: 'yith_wcwl_update_wishlist_count',
                };
                $.post(tools_global_frontend.ajaxurl, data, function (response) {
                    if ( response.status == true ) {
                        counter.html(response.count);
                    }

                });
            });

        },
        yith_woocompare_ajax_update_count: function () {
            $(window).on('yith_woocompare_open_popup yith_woocompare_product_removed', function () {
                var counter = $('.block-compare .count');
                var data    = {
                    action: 'yith_compare_update_count',
                };
                $.post(tools_global_frontend.ajaxurl, data, function (response) {
                    if ( response.status == true ) {
                        counter.html(response.count);
                    }

                });
            })
        },
        mobile_block: function () {
            $(document).on('click', '.header-device-mobile .item.has-sub>a', function () {
                $(this).closest('.header-device-mobile').find('.item').removeClass('open');

                $(this).closest('.item').addClass('open');
                return false;
            })
            $(document).on('click', '.header-device-mobile .item .close', function () {
                $(this).closest('.item').removeClass('open');
                return false;
            })
            $(document).on('click', '*', function (event) {
                if ( !$(event.target).closest(".header-device-mobile").length ) {
                    $(".header-device-mobile").find('.item').removeClass('open');
                }
            })


        },
        header_sticky: function () {
            var window_size          = jQuery('body').innerWidth();
            var h                    = $(window).scrollTop();
            var max_h                = $('#header').height();
            var vertical_menu_height = 0;

            if ( $('.block-nav-category').length > 0 ) {
                vertical_menu_height = $('.block-nav-category .block-content').innerHeight();
            }
            var topSpacing = max_h + vertical_menu_height;
            topSpacing     = '-' + topSpacing;

            if ( tools_global_frontend.ovic_sticky_menu == 1 && $(".header-sticky").length > 0 ) {
                if ( window_size > 991 ) {
                    var sticky = $(".header-sticky")
                    sticky.sticky({topSpacing: topSpacing});
                    sticky.on('sticky-start', function () {
                        $('.block-nav-category').removeClass('has-open');
                    });
                }

            }

        },
        quick_view_slide: function () {
            if ( $('.slider-for').length ) {
                $('.slider-for').slick({
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    arrows: false,
                    fade: true,
                    asNavFor: '.slider-nav',
                    slidesMargin: 0
                });
                $('.slider-nav').slick({
                    slidesToShow: 3,
                    slidesToScroll: 1,
                    asNavFor: '.slider-for',
                    focusOnSelect: true,
                    arrows: false,
                    slidesMargin: 5
                });
            }
        },
    };

    $(window).scroll(function () {
        TOOL_THEME.scroll();
    });
    $(document).ajaxComplete(function (event, xhr, settings) {
        TOOL_THEME.ajaxComplete();
    });
    $(window).on('resize', function () {
        TOOL_THEME.onResize();
    });
    $(window).on('change', function () {
        TOOL_THEME.onChange();
    });
    document.addEventListener("DOMContentLoaded", function () {
        TOOL_THEME.init();
    });
})(jQuery, window, document);