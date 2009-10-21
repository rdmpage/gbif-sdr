<?php
require_once($_SERVER['DOCUMENT_ROOT'] ."/config.php");

$savefile = $_REQUEST['id'] . "_".$_REQUEST['resource_id']."_big.png";

if (file_exists(CACHE_FOLDER."cache/".$savefile)) {
	Header("Cache-Control: must-revalidate");
	$offset = 60 * 60 * 24 * 300;
	$ExpStr = "Expires: " . gmdate("D, d M Y H:i:s", time() + $offset) . " GMT";
	Header($ExpStr);
	header('Content-type: image/png');
	//ob_clean();
	flush();	
	readfile(CACHE_FOLDER."cache/".$savefile);
	exit;		
}

$url=GEOSERVER_URL."wms?transparent=true&WIDTH=1024&SRS=EPSG%3A900913&HEIGHT=1024&STYLES=" . 
						"&FORMAT=image%2Fpng&TILED=true&TILESORIGIN=-180%2C-90&SERVICE=WMS&VERSION=1.1.1&REQUEST=GetMap" . 
						"&EXCEPTIONS=application%2Fvnd.ogc.se_inimage&LAYERS=gbif%3Asdr_1_view,gbif%3Asdr_2_view" .
						"&CQL_FILTER=nub_usage_id%3D".$_REQUEST['id']."%20and%20resource_id%3D".$_REQUEST['resource_id'].
						";nub_usage_id%3D".$_REQUEST['id']."%20and%20resource_id%3D".$_REQUEST['resource_id']."&BBOX=-20037508.3428,-20037508.3428,20037508.3428,20037508.3428";


$data=file_get_contents($url);	

if(strlen($data)<10) {
	die();
}
			
Header("Cache-Control: must-revalidate");
$offset = 60 * 60 * 24 * 300;
$ExpStr = "Expires: " . gmdate("D, d M Y H:i:s", time() + $offset) . " GMT";
Header($ExpStr);
			
header( "content-type:image/png" );
header( "content-length:" .strlen($data) );

echo $data;	

file_put_contents(CACHE_FOLDER.$savefile, $data);


?>