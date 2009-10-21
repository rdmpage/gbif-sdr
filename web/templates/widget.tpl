<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">

<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Species distribution repository</title>

<script type="text/javascript" src="/js/swfobject.js" ></script>
</head>
<body bottommargin="0" leftmargin="0" marginheight="0" marginwidth="0" rightmargin="0" topmargin="0">
	<div class="span-24 widgetContainer" id="widgetContainer">
		Alternative content...
	</div>
	
	{literal}
	<script type="text/javascript">	
		

		var so = new SWFObject("swf/SDRwidget.swf", "swf", "100%", "500", "9"); 
		so.addParam("allowFullScreen", "true");
		so.addVariable("swf", "");
		{/literal}{if $smarty.get.source neq ""}{literal}
			so.addVariable("source", "{/literal}{$smarty.get.source}{literal}");
		{/literal}{/if}{literal}
		{/literal}{if $smarty.get.location neq ""}{literal}
			so.addVariable("location", "{/literal}{$smarty.get.location}{literal}");
		{/literal}{/if}{literal}		
		so.addVariable("speciesId", "{/literal}{$smarty.get.id}{literal}");
		so.addVariable("nub_concept_id", "{/literal}{$smarty.get.id}{literal}");
		
		//Use the correct Google API Key
		var host=window.location.host;
		if(host.search(/localhost/)>=0) {
		    so.addVariable("api_key", "ABQIAAAAtDJGVn6RztUmxjnX5hMzjRTb-vLQlFZmc2N8bgWI8YDPp5FEVBTeJc72_716EfYqx-s8UGt88XqC9w");
		}
		if(host.search(/amazonaws/)>=0) {
		    so.addVariable("api_key", "ABQIAAAAtDJGVn6RztUmxjnX5hMzjRSMhkig1Gd5B_2j4H1Xz7hsATFBFhR-0p1pzKNnQNC5NFHIaSiCY6Tc4g");
		}
		if(host.search(/sdr/)>=0) {
		    so.addVariable("api_key", "ABQIAAAAtDJGVn6RztUmxjnX5hMzjRRB_s88KYOYB5xSJeaJsw9Y1UYVbxQ2juVNsqar69w_XH822Y5oqQ3HvA");
		}		
		
		so.write("widgetContainer");

	</script>
	{/literal}	
</body>
</html>