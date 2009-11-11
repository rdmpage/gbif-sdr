<?php
require_once($_SERVER['DOCUMENT_ROOT'] ."/config.php");

class NARServices {
	
	function __construct() {
		$this->conn = pg_connect ("host=".DB_HOST." dbname=".DB_NAME." user=".DB_USER." password=".DB_PASSWORD);
	}
	
	public function getReferences() {
	    $sql = "SELECT * FROM named_area_reference";        
        return pg_fetch_all(pg_query($this->conn, $sql));
	}


	public function getReferenceCodes($referenceCode) {
	    $sql = "select na.id,area_code,area_name ".
	    "from named_area as na inner join named_area_reference as nar on na.named_area_reference_fk=nar.id ".
	    "where nar.code ='$referenceCode'";	        
	    
		return pg_fetch_all(pg_query($this->conn, $sql));
	}
	
	public function getNamedAreaDetails($referenceCode,$areaCode) {
	    $sql = "select na.id,na.area_code,na.area_name, ".
        "ST_AsGeoJson(nag.the_geom,4) as geojson, ".
        "ymin(nag.the_geom) as south,  ".
        "xmin(nag.the_geom) as west, ".
        "xmax(nag.the_geom) as east, ".
        "ymax(nag.the_geom) as north, ".
        "x(centroid(nag.the_geom)) as center_lat, ".
        "y(centroid(nag.the_geom)) as center_lon ".
        "from named_area as na inner join named_area_geom nag on na.named_area_geom_fk=nag.id ".
        "inner join named_area_reference as nar on na.named_area_reference_fk=nar.id ".
        "where nar.code ='$referenceCode' and na.area_code='$areaCode'";
		return pg_fetch_assoc(pg_query($this->conn, $sql));
		
	}

}
?>
