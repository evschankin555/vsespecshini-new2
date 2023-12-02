jQuery(document).ready(function ($) {

    "use strict";
    $(' .chosen-container').each(function () {
        $(this).addClass('chosen-rtl');
    });

    // Force all full width row Visual Composer
    $(document).on('vc-full-width-row', function () {
        tools_force_vc_full_width_row_rtl();
    });

    function tools_force_vc_full_width_row_rtl() {
        var $elements = $('[data-vc-full-width="true"]');
        $.each($elements, function (key, item) {
            var $this     = $(this);
            var this_left = $this.css('left');

            $this.css({
                'left' : '',
                'right' : this_left
            });

        }), $(document).trigger('lk-force-vc-full-width-row-rtl', $elements);
    }


});