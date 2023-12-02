jQuery(document).ready(function()
{
	if(iduidOneItemFilter.page == "options-general.php")
	{
		var errorNext 	= 0;
		var globalNext	= 1;
		var buttonm	= "div#regen_" + iduidOneItemFilter.varPref + " input[name=regen]";

		jQuery(buttonm).prop('disabled', false);


         	jQuery(buttonm).click(function()
		{
			var $current = jQuery(this);

			if(jQuery(this).is(':disabled'))
			{
				return;
			}

			ajReg(globalNext, $current);

			return false;
		});
	}

	function ajReg(globalNext, $current, addition)
	{
		if(jQuery($current).data('action') == 'stop')
		{
			refresh_button($current);
			return false;
		}

		refresh_button1($current);

		var post = jQuery.post(
			iduidOneItemFilter.ajaxurl,
			{
				action:		iduidOneItemFilter.varPref + 'ajax-rgen',
				nextNonce:	iduidOneItemFilter.nonce,
				nextStep:	globalNext
			}
		);

		post.done(function(data)
		{
			var aData = jQuery.parseJSON(data);

			if(aData.status == "ok")
			{
				jQuery("span#" + iduidOneItemFilter.varPref + "_status").html("Отремонтировано - " + (parseInt(globalNext) * 10) + " / Осталось - " + aData.total);

				refresh_button1($current);

				if(aData.next)
				{
					globalNext = globalNext + 1;

					ajReg(globalNext, $current);
				}
			}

			if(aData.status == "bad")
			{
				jQuery("span#" + iduidOneItemFilter.varPref + "_status").html("Произошла ошибка");
				refresh_button($current);
			}

			if(aData.status == "error")
			{
				jQuery("span#" + iduidOneItemFilter.varPref + "_status").html("Массовый ремонт завершен");
				refresh_button($current);
			}

	  	});

		post.error(function()
		{
			errorNext++;
			jQuery("span#" + iduidOneItemFilter.varPref + "_status").html("Произошла ошибка/Таймаут ("+errorNext+")");

			ajReg(globalNext, $current);

			if(parseInt(errorNext) > 10)
			{
				refresh_button2($current);
			}//else 	refresh_button($current);
  		});
	}

	function refresh_button($current)
	{
		jQuery($current).prop('disabled', false);
		jQuery($current).val("Запустить ремонт вариаций");

		jQuery($current).data('action', '');
	}

	function refresh_button1($current)
	{
		jQuery($current).prop('disabled', true);
		jQuery($current).val("Идет ремонт вариаций...");

		jQuery($current).data('action', '');
	}

	function refresh_button2($current)
	{
		jQuery($current).prop('disabled', false);
		jQuery($current).val("остановить...");
		jQuery($current).data('action', 'stop');
	}
});