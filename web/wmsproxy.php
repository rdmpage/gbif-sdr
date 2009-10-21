<?php
require_once($_SERVER['DOCUMENT_ROOT'] ."/config.php");

$savefile = $_REQUEST['x'] . "_" . $_REQUEST['y'] . "_". $_REQUEST['z'] . "_". $_REQUEST['species_id'] . "_". $_REQUEST['resource_id'] . ".png";

if (file_exists(CACHE_FOLDER.$savefile)) {
	Header("Cache-Control: must-revalidate");
	$offset = 60 * 60 * 24 * 300;
	$ExpStr = "Expires: " . gmdate("D, d M Y H:i:s", time() + $offset) . " GMT";
	Header($ExpStr);
	header('Content-type: image/png');
	//ob_clean();
	flush();	
	readfile(CACHE_FOLDER.$savefile);
	exit;		
}

$url=GEOSERVER_URL."wms?transparent=true&WIDTH=256&SRS=EPSG%3A900913&HEIGHT=256&STYLES=&FORMAT=image%2Fpng&TILED=true&TILESORIGIN=-180%2C-90&SERVICE=WMS&VERSION=1.1.1&REQUEST=GetMap&EXCEPTIONS=application%2Fvnd.ogc.se_inimage";


if ($_REQUEST['d_type']=="1") {
	$url.="&LAYERS=gbif%3Asdr_1_view";
} else {
	$url.="&LAYERS=gbif%3Asdr_2_view";
}


//&SLD=http%3A%2F%2Flocalhost%2FdynamicSLD.php%3Fresource%3D".$_REQUEST['resource_id']."%26species_id%3D".$_REQUEST['species_id'];

$url.="&CQL_FILTER=nub_usage_id%3D".$_REQUEST['species_id']."%20and%20resource_id%3D".$_REQUEST['resource_id'];
$url.="&BBOX=".$_REQUEST['bbox'];


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

//only cache if the zoom level is smaller than 7
if ($_REQUEST['z']<6) {
    file_put_contents(CACHE_FOLDER.$savefile, $data);
}


?>