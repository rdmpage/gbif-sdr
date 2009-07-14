<?php

class NARServices {
	
	function __construct() {
		$this->dbHandle = new PDO('pgsql:host=ec2-174-129-85-138.compute-1.amazonaws.com port=5432 dbname=sdr user=postgres password=atlas');
	}
    
    public function login($user,$pass) {

        // create page view database table
        $sql = "SELECT * FROM admin WHERE username='$user' AND password='$pass'";        
        $result = $this->dbHandle->query($sql)->fetchAll(PDO::FETCH_COLUMN, 0);    

        if (count($result)<1) {
            $_SESSION['logged']=false;
            throw new Exception("user not logged in");
        } else {
            $_SESSION['logged']=true;
            return true;
        }
	}
	
	public function getReferences() {
	    $sth = $this->dbHandle->prepare("SELECT * FROM named_area_reference");
        $sth->execute();
        
        
        return $sth->fetchAll(PDO::FETCH_ASSOC);
	}


	public function getReferenceCodes($referenceCode) {
	    $stmt = $this->dbHandle->prepare("select id,area_code,area_name,named_area_reference_fk from named_area where named_area_reference_fk=:reference_code");	        
	    
        $stmt->bindParam(':reference_code', $referenceCode, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	
	public function getNamedAreaDetails($referenceCode,$areaCode) {
	    $sth = $this->dbHandle->prepare("select na.id,na.area_code,na.area_name, ".
        "ymin(nag.the_geom) as south,  ".
        "xmin(nag.the_geom) as west, ".
        "xmax(nag.the_geom) as east, ".
        "ymax(nag.the_geom) as north, ".
        "x(centroid(nag.the_geom)) as center_lat, ".
        "y(centroid(nag.the_geom)) as center_lon ".
        "from named_area as na inner join named_area_geom nag on na.named_area_geom_fk=nag.id where na.named_area_reference_fk =:reference_code and na.area_code=area_code");
        
        $stmt->bindParam(':reference_code', $referenceCode, PDO::PARAM_INT);
        $stmt->bindParam(':area_code', $areaCode, PDO::PARAM_STR);
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

}
?>
