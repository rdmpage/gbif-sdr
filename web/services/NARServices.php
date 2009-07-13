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

}
?>
