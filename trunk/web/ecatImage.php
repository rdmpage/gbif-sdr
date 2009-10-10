<?php


$savefile = $_REQUEST["id"] . ".jpg";

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


$url = "http://ecat-ws.gbif.org/ws/usage/?id=".$_REQUEST['id'];

$rsp = file_get_contents($url);
$rsp_obj = json_decode($rsp);

#
# display the photo title (or an error if it failed)
#

if (count($rsp_obj->images)>0) {
    $pic = file_get_contents($rsp_obj->images[0]); 
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