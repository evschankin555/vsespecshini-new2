window.rwt = function(a, b, m)
{
	var IE	= false;
	var ua	= window.navigator.userAgent;

	if(ua.indexOf('MSIE ') || ua.indexOf('Trident/') || ua.indexOf('Edge/'))
	{
		IE = true;
	}

	try
	{
		var e = encodeURIComponent || escape;
		var xhr = new XMLHttpRequest();
		var l = [dadptplugin.homeurl + "/?" + dadptplugin.varPref + "pid=" + e(b)].join("");
//console.log(l);

		xhr.open('GET', l);
		xhr.onload = function() 
		{
			if(xhr.status === 200) {}
		};
		xhr.send();
        } catch(o) {}
	return ! 0
};