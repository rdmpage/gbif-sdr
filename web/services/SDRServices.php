<?php

include("PolylineEncoder.php");

class SDRServices {
	
	function __construct() {
		$this->dbHandle = new PDO('pgsql:host=ec2-174-129-85-138.compute-1.amazonaws.com port=5432 dbname=sdr user=postgres password=atlas');
		//$this->dbHandle = new PDO('pgsql:host=localhost port=5432 dbname=postgres user=postgres password=postgres');
		
	}
    
    public function login($email,$pass) {

        // create page view database table
        $sql = "SELECT * FROM users WHERE email='$email' AND pass='$pass'";        
        $result = $this->dbHandle->query($sql)->fetch(PDO::FETCH_ASSOC);    

        if (strlen($result['username'])<1) {
            $_SESSION['logged']=false;
            throw new Exception("user not logged in");
        } else {
            $_SESSION['logged']=true;
            $_SESSION['user']=$result;
    	    return $result;            
        }
	}
	
	public function getComments($speciesId) {
	    $sql="SELECT c.id,commenttext,c.created_when,username from comments as c INNER JOIN users as u ON c.user_fk=u.id  WHERE comment_on_id=:speciesId";
        $stmt = $this->dbHandle->prepare($sql);
        $stmt->bindParam(':speciesId', $speciesId);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);	    
	}
	
	public function getSpeciesData($speciesId) {
	    
	}
	
	public function registerUser($username,$projectname,$email,$password) {
	    
	    if(strlen($username)<5) {
	        throw new Exception('Username with not enough characters',101);
	    }
	    if(strlen($password)<5) {
	        throw new Exception('Password with not enough characters',102);
	    }
	    if(strlen($email)<5) {
	        throw new Exception('Email with not enough characters',103);
	    }	 
	    
	    //Check if username or password are in the DB
	    $sql="SELECT id from users WHERE username=:username";
		$stmt = $this->dbHandle->prepare($sql);
	    $stmt->bindParam(':username', $username);	     
	    $stmt->execute();
	    if($stmt->rowCount>0) {
	        throw new Exception('Username already registered',104);
	    }	     
	    
	    //Check if username or password are in the DB
	    $sql="SELECT id from users WHERE email=:email";
		$stmt = $this->dbHandle->prepare($sql);
	    $stmt->bindParam(':email', $email);	     
	    $stmt->execute();
	    if($stmt->rowCount>0) {
	        throw new Exception('Email already registered',105);
	    }	   
	    
	    $sql="INSERT INTO users(username,pass,project_name,email) VALUES(:username,:password,:projectname,:email)";
	    $stmt->bindParam(':email', $email);	     
	    $stmt->bindParam(':username', $username);	     
	    $stmt->bindParam(':password', $password);	     
	    $stmt->bindParam(':projectname', $projectname);	        	    
		$stmt = $this->dbHandle->prepare($sql);
	    $stmt->execute();	       
	    
	    //get last ID
	    /* $sql = "SELECT currval('users_id_seq') AS last_value";
	    $lastId = $this->dbHandle->query($sql)->fetchAll(PDO::FETCH_ASSOC);  */
	    
	    
	    $user=array();
	    //$user['id']=$lastId['last_value'];
	     $user['id']=1;
	    $user['username']=$username;
	    $user['projectname']=$projectname;
	    $user['email']=$email;
	    return $user;
	}
	
	
	public function logout() {
	    session_destroy();
	}	    
	
	public function addComment($userId,$comment,$speciesId) {
	    try {
	        $sql="INSERT INTO comments(user_fk,commenttext,comment_type_fk,comment_on_id) VALUES(:user_fk,:comment,1,:comment_on_id)";
    		$stmt = $this->dbHandle->prepare($sql);    
    	    $stmt->bindParam(':user_fk', $userId);	     
    	    $stmt->bindParam(':comment', $comment);	        
    	    $stmt->bindParam(':comment_on_id', $speciesId);	        	    
    	    $stmt->execute();
    	    return true;
	    } catch(Exception $e) {
	        return false;
	    }

	    
	}
	
	public function getNameById($nameId) {
		$sql="select d.id,resourcename, scientific_name, year_start, year_end, spatial_resolution_fk, record_base_fk, ". 
		"spatial_accuracy,confidence_by_source,distribution_type_fk ".
		"from (distribution as d inner join scientific_name as sn on d.name_fk=sn.id) inner join resource as r on d.resource_fk=r.id ".
		"where name_fk=:nameId";
		$stmt = $this->dbHandle->prepare($sql);
	    $stmt->bindParam(':nameId', $nameId); 
	    $stmt->execute();
		$res= $stmt->fetchAll(PDO::FETCH_ASSOC);
		$sources=array();
		foreach($res as $data) {
			$source=array();
			$source = $data;
		
			//Add the distribution units
			$sql2="select distinct occurrence_status_fk,nativeness_fk,life_stage_fk from distribution_unit
			where distribution_fk=:distributionId";
			$stmt2 = $this->dbHandle->prepare($sql2);
		    $stmt2->bindParam(':distributionId', $data['id']); 
		    $stmt2->execute();
			$res2= $stmt2->fetchAll(PDO::FETCH_ASSOC);			
			$source['units_legend'] = $res2;
			$sources[]=$source;
		}
		
		return $sources;
	}
	
	
	public function getTaxonById($id) {
	    
	    
	$sql="select DISTINCT accessexport.redlist_id,accessexport.cms_status_2,accessexport.cms_description,accessexport.external_url,accessexport.scientifiname as name, accessexport.species_id as id,gbif_id,mapsource as source,english, german,spanish,french, null as genus,animalgroup as group, animal_class as classname,migration as migrationtype, cms_status as cms, cms as cms_link, redlist_codes.description as red_list, cites from accessexport left join redlist_codes on accessexport.rl2k_code=redlist_codes.rl2k_id left join species_names on accessexport.species_id=species_names.species_id where accessexport.species_id=:param";	    
	    $stmt = $this->dbHandle->prepare($sql);
	    $stmt->bindParam(':param', $id); 
	    $stmt->execute();	    
	    
	    $res= $stmt->fetch(PDO::FETCH_ASSOC);
	    
	    $taxon=array();
	    $taxon['name'] = $res['name'];
	    $taxon['id'] = (int)$id;
	    $taxon['gbif_id'] = $res['gbif_id'];
	    $taxon['source'] = $res['source'];
	    $taxon['commonNameEnglish'] =$res['english'];
	    $taxon['commonNameGerman'] = $res['german'];
	    $taxon['commonNameSpanish'] = $res['spanish'];
	    $taxon['commonNameFrench'] =$res['french'];
	    $taxon['genus'] = $res['genus'];
	    $taxon['group'] = $res['group'];
	    $taxon['className'] = $res['classname'];
	    $taxon['migrationType'] =$res['migrationtype'];
	    $taxon['cms'] =$res['cms'];
	    $taxon['cms_link'] = $res['cms_link'];
	    $taxon['red_list'] = $res['red_list'];
	    $taxon['cites'] =$res['cites'];
	$taxon['redlist_id'] =$res['redlist_id'];
	$taxon['cms_status_2'] =$res['cms_status_2'];
	$taxon['cms_description'] =$res['cms_description'];
	$taxon['external_url'] =$res['external_url'];	    
	    $taxon['charts']=array();
	    
	    
	    //$stmt = $this->dbHandle->prepare("select gid,s.monthstart,s.monthend,status, astext((ST_Dump(the_geom)).geom) as the_geom from accessexport as a inner join shapefiles as s on a.shape_id=s.shapefile_id where species_id=:param order by gid");
	    
	    $stmt = $this->dbHandle->prepare("select distinct s.monthstart,s.monthend,status from accessexport as a inner join shapefiles as s on a.shape_id=s.shapefile_id where a.species_id=:param");	    
	    
	    $stmt->bindParam(':param', $id); 
	    $stmt->execute();
	    
	    
	    $taxon['charts']= $stmt->fetchAll(PDO::FETCH_ASSOC); 	    
	    
	    return $taxon;
	    
        
	}
	
	
	public function searchForName($name,$limit=10,$offset=0) {
		

		
	    $stmt = $this->dbHandle->prepare("select ns.*, n.scientific_name ".
			"from name_summary as ns inner join scientific_name as n on ns.name_fk=n.id ".
			"where n.scientific_name like :param limit :limit offset :offset");
	    $name = $name . "%";
	    $stmt->bindParam(':param', $name); 
		$stmt->bindParam(':limit', $limit); 
		$stmt->bindParam(':offset', $offset); 
	    $stmt->execute();
	    
	    
	    return $stmt->fetchAll(PDO::FETCH_ASSOC);		
	}
	
	
	/*public function getAllItems() {
	    if (!$_SESSION['logged']) {
	        throw new Exception("user not logged in");
	    }
	    $sth = $this->dbHandle->prepare("SELECT * FROM item");
        $sth->execute();
        
        return $sth->fetchAll(PDO::FETCH_ASSOC);
	}
	
	public function getItemDetail($id) {
	    $sth = $this->dbHandle->prepare("SELECT * FROM item where id=$id");
        $sth->execute();
        
        return $sth->fetch(PDO::FETCH_ASSOC);
	}
	
	public function getItemList($maxItems) {
	    $sth = $this->dbHandle->prepare("SELECT * FROM item limit $maxItems");
        $sth->execute();
        
        return $sth->fetchAll(PDO::FETCH_ASSOC);
	}	*/	

}
?>
