<?php

#
# build the API URL to call
#

$params = array(
	'api_key'	=> '35479722da32ce91e47ae1147c41cfd9',
	'method'	=> 'flickr.photos.search',
	//'machine_tags'	=> 'taxonomy:*="'.$_REQUEST["q"].'"',
	'text'	=> $_REQUEST["q"],
	'format'	=> 'php_serial',
);

$encoded_params = array();

foreach ($params as $k => $v){
	$encoded_params[] = urlencode($k).'='.urlencode($v);
}


#
# call the API and decode the response
#

$url = "http://api.flickr.com/services/rest/?".implode('&', $encoded_params);

$rsp = file_get_contents($url);
$rsp_obj = unserialize($rsp);


#
# display the photo title (or an error if it failed)
#

if ($rsp_obj['stat'] == 'ok' and isset($rsp_obj["photos"]["photo"][0]["id"])){
	$server= $rsp_obj["photos"]["photo"][0]["server"];
	$farm= $rsp_obj["photos"]["photo"][0]["farm"];
	$secret= $rsp_obj["photos"]["photo"][0]["secret"];
	$id= $rsp_obj["photos"]["photo"][0]["id"];
	
	$url = "http://farm$farm.static.flickr.com/$server/{$id}_{$secret}_s.jpg";
	$pic = file_get_contents($url); 
}else{
	//echo "Call failed!";
	$pic = file_get_contents("images/noPicture.jpg"); 
}

header('Content-Type: image/jpeg');
echo($pic);

?>