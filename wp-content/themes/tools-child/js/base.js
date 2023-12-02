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
		var wdam1 = $('div.main-content div.shop-widget-area-after-header');
		var wdam2 = $('div.main-content div.shop-widget-area-after-main');

		if($(window).width() <= 768)
		{
			if(wda.length > 0)
			{
				if(wdam1.length > 0)
				{
					wda.hide();
					wdam1.show();
					wdam1.parent().show();

					wdam2.show();
					wdam2.parent().show();


					return;
				}

				wda.hide();
			}
		}else
		{
			if(wda.length > 0)
			{
				wda.show();
				wdam1.hide();
				wdam2.hide();
			}
		}
	}
});