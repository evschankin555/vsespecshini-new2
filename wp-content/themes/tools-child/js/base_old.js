jQuery(document).ready(function($)
{
	setFilterMob();

	$(window).resize(function() 
	{
		setFilterMob();
	});

	function setFilterMob()
	{
		var wda = $('div.sidebar div#widget-area').parent();
		var wdam1 = $('div.main-content header.woocommerce-products-header div#widget-area');

		if($(window).width() <= 768)
		{
			if(wda.length > 0)
			{
				if(wdam1.length > 0)
				{
					wda.hide();
					wdam1.show();
					wdam1.parent().show();

					return;
				}

				var clone 	= wda.clone();

				clone.find('div.widget_product_brand').remove();
				clone.find('div.widget_product_categories').remove();
				clone.find('div.widget_product_tag_cloud').remove();



				clone.find('span.select2').each(function(index){$(this).remove()});

				clone.find('select').each(function(index)
				{
					if ($(this).data('select2'))
					{
						$(this).select2('destroy');
					}
				});


//				var cloneHTML	= clone.html();
				wda.hide();
				clone.appendTo($('div.main-content header.woocommerce-products-header'));
				$('.select2-hidden-accessible').select2({width:'100%'});


//				$(cloneHTML).appendTo($('div.main-content header.woocommerce-products-header'));
//				$(cloneHTML).appendTo($('div.main-content header.woocommerce-products-header'));
			}
		}else
		{
			if(wda.length > 0)
			{
				wda.show();
				wdam1.hide();
			}
		}
	}
});