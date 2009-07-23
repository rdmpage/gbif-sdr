<?php

function preparar_nom_archivo($nom_archivo) 
{ 
    $arr_busca = array(' ','á','à','â','ã','ª','Á','À', 
    'Â','Ã', 'é','è','ê','É','È','Ê','í','ì','î','Í', 
    'Ì','Î','ò','ó','ô', 'õ','º','Ó','Ò','Ô','Õ','ú', 
    'ù','û','Ú','Ù','Û','ç','Ç','Ñ','ñ'); 
    $arr_susti = array('-','a','a','a','a','a','A','A', 
    'A','A','e','e','e','E','E','E','i','i','i','I','I', 
    'I','o','o','o','o','o','O','O','O','O','u','u','u', 
    'U','U','U','c','C','N','n'); 
    $nom_archivo = trim(str_replace($arr_busca, $arr_susti, $nom_archivo)); 
    return ereg_replace('[^A-Za-z0-9\_\.\-]', '', $nom_archivo); 
}

$savefile = preparar_nom_archivo($_REQUEST["q"]) . ".jpg";

if (file_exists("cache/".$savefile)) {
	Header("Cache-Control: must-revalidate");
	$offset = 60 * 60 * 24 * 300;
	$ExpStr = "Expires: " . gmdate("D, d M Y H:i:s", time() + $offset) . " GMT";
	Header($ExpStr);
	header('Content-type: image/jpg');
	flush();	
	readfile("cache/".$savefile);
	exit;		
}



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
	
	file_put_contents("cache/$savefile", $pic);
	
}else{
	//echo "Call failed!";
	$pic = file_get_contents("images/noPicture.jpg"); 
}

Header("Cache-Control: must-revalidate");
$offset = 60 * 60 * 24 * 300;
$ExpStr = "Expires: " . gmdate("D, d M Y H:i:s", time() + $offset) . " GMT";
Header($ExpStr);

header('Content-Type: image/jpeg');

echo($pic);





?>