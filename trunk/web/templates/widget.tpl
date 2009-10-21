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
		so.addVariable("geoserverEndPoint", "{/literal}{$smarty.const.GEOSERVER_URL}{literal}");
		so.addVariable("dataEndPoint", "{/literal}{$smarty.const.DATA_URL}{literal}");		
		{/literal}{if $smarty.get.source neq ""}{literal}
			so.addVariable("source", "{/literal}{$smarty.get.source}{literal}");
		{/literal}{/if}{literal}
		{/literal}{if $smarty.get.location neq ""}{literal}
			so.addVariable("location", "{/literal}{$smarty.get.location}{literal}");
		{/literal}{/if}{literal}		
		so.addVariable("speciesId", "{/literal}{$smarty.get.id}{literal}");
		so.addVariable("nub_concept_id", "{/literal}{$smarty.get.id}{literal}");
		so.addVariable("wmsProxy", "{/literal}{$smarty.const.WMS_PROXY}{literal}");	
		so.addVariable("ecatServices", "{/literal}{$smarty.const.ECAT_SERVICES}{literal}");	
        so.addVariable("api_key", "{/literal}{$smarty.const.GMAP_KEY}{literal}");	
		so.write("widgetContainer");

	</script>
	{/literal}	
</body>
</html>