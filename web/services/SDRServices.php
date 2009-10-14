<?php

include("PolylineEncoder.php");

class SDRServices {
	
	function __construct() {
		$this->conn = pg_connect ("host=localhost dbname=sdr user=postgres password=atlas");	
	}
    
	
    public function login($email,$pass) {

        $email=pg_escape_string($email);
        $pass=pg_escape_string($pass);
        
        // create page view database table
        $sql = "SELECT * FROM users WHERE (email='$email' AND pass='$pass') OR (username='$email' AND pass='$pass')";        
        $result = pg_query($this->conn, $sql);
        if(pg_num_rows($result)<1) {
            $_SESSION['logged']=false;
            throw new Exception("user not logged in");
        } else {
 
            $_SESSION['logged']=true;
            $res=pg_fetch_assoc($result);
            $_SESSION['user']=$res;
    	    return $res;           
        }
	}	
	
	
	
	public function getComments($speciesId) {
	    $sql="SELECT c.id,commenttext,c.created_when,username from comments as c INNER JOIN users as u ON c.user_fk=u.id  WHERE comment_on_id=$speciesId";
        return pg_fetch_all(pg_query($this->conn, $sql));	    
	}
	
	public function getSpeciesData($speciesId) {
	    
	}
	
	public function registerUser($username,$projectname,$email,$password) {
	    $username=pg_escape_string($username);
	    $projectname=pg_escape_string($projectname);
	    $email=pg_escape_string($email);
	    $password=pg_escape_string($password);
	    
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
	    $sql="SELECT id from users WHERE username='$username'";
	    $result=pg_query($this->conn, $sql);
	    if(pg_num_rows($result)>0) {
	        throw new Exception('Ese nombre de usuario ya esta registrado.',104);
	    }     
	    
	    //Check if username or password are in the DB
	    $sql="SELECT id from users WHERE email='$email'";
	    $result=pg_query($this->conn, $sql);
	    if(pg_num_rows($result)>0) {
	        throw new Exception('Email ya registrado.',105);
	    }	   
	    
	    $sql="INSERT INTO users(username,pass,project_name,email) VALUES('$username','$password','$projectname','$email')";
        $result=pg_query($this->conn, $sql);          
	    
	    //get last ID
	    $sql = "SELECT currval('users_id_seq') AS last_value";
	    $result=pg_query($this->conn, $sql);
	    $res=pg_fetch_assoc($result);
	    
	    
	    /*$user=array();
	    $user['id']=$resultId['last_value'];
	    $user['username']=$username;
	    $user['projectname']=$projectname;
	    $user['email']=$email;*/
	    
	    return $username;
	}
	
	
	public function logout() {	    
	    session_destroy();
	}	    
	
	public function addComment($userId,$comment,$speciesId) {
	    if (!$_SESSION['logged'] or $_SESSION['user']['id']!=$userId) {
	        throw new Exception("user not logged in");
	    }	    
	    
	    $comment=pg_escape_string($comment);
	    
        $sql="INSERT INTO comments(user_fk,commenttext,comment_type_fk,comment_on_id) VALUES($userId,'$comment',1,'$speciesId')";
        $result= pg_query($this->conn, $sql);
        return null;

	    
	}
	
	public function getMostPopularSpecies($limit) {
	    $sql="select nub_usage_id,num_views from species_view_stats order by num_views DESC limit $limit";
        //$result = 
        return pg_fetch_all(pg_query($this->conn, $sql)); 
	    
	    
	}
	
	public function getGbifDetailsByNameId($nameId) {
	    
	    //update the species_view_stats
	    $sql="select update_species_view_stats($nameId)";
        $result= pg_query($this->conn, $sql);


		$rsp = file_get_contents("http://ecat-ws.gbif.org/ws/usage/?id=$nameId");
		$res= json_decode($rsp);
        return $res;

	    
	}
	
	public function getNameById($nameId) {
		$sql="select d.id,resourcename, scientific_name,nub_concept_id, year_start, year_end, spatial_resolution_fk, record_base_fk, ". 
		"spatial_accuracy,confidence_by_source,distribution_type_fk ".
		"from (distribution as d inner join scientific_name as sn on d.name_fk=sn.id) inner join resource as r on d.resource_fk=r.id ".
		"where name_fk=$nameId";
        $result = pg_fetch_all(pg_query($this->conn, $sql));
		$sources=array();
		foreach($result as $data) {
			$source=array();
			$source = $data;
		
			//Add the distribution units
			$sql2="select distinct occurrence_status_fk,nativeness_fk,life_stage_fk from distribution_unit
			where distribution_fk=".$data['id'];		
			$source['units_legend'] = pg_fetch_all(pg_query($this->conn, $sql2));
			$sources[]=$source;
		}
		
		return $sources;
	}
	
	public function getSpeciesDetailsByNameId($speciesId) {
	    
	    $geoserverUrl="http://ec2-174-129-85-138.compute-1.amazonaws.com:8080/geoserver";
	    
	    $sql="SELECT d.id,code, resourcename,resource_fk as resource_id, (select count(id) from distribution_unit where distribution_fk=d.id) as num_units from distribution as d inner join resource as r on d.resource_fk=r.id where d.clb_usage_id=$speciesId";

        $result = pg_fetch_all(pg_query($this->conn, $sql));
        if(!$result) {
            return array();
        }
		$sources=array();	    
		foreach($result as $data) {	    
			$source=array();
			$source = $data;
			//Add the distribution units
			$sql2="select distinct s.id as id, tag,color from distribution_unit as du inner join status_tags as s on du.status_tags_fk=s.id where distribution_fk=".$data['id'];	
			$legend = pg_fetch_all(pg_query($this->conn, $sql2));
			if($legend) {
			    $source['legend'] = $legend;
			} else {
			    $source['legend'] = array();
			}
			
			$source['png']=$geoserverUrl."/wms?WIDTH=1024&HEIGHT=1024&SRS=EPSG%3A4326&".
            				"STYLES=&SERVICE=WMS&VERSION=1.1.1&REQUEST=GetMap&EXCEPTIONS=application%2Fvnd.ogc.se_inimage".
            				"&LAYERS=sdr%3Adistributions&FORMAT=image%2FpngL&".
            				"CQL_FILTER=id={$data['id']}$&BBOX=-180,-90,180,90";

			$sources[]=$source;
		}
		
		return $sources;	    
	}
	
	/*public function searchForName($name,$limit=10,$offset=0) {
		$name=pg_escape_string($name);
		if($offset=="")
		    $offset=0;
		
	    $sql="select ns.*, n.scientific_name ".
			"from name_summary as ns inner join scientific_name as n on ns.name_fk=n.id ".
			"where n.scientific_name like '$name%' order by n.scientific_name limit 10 offset $offset";
	    
	    
	    return pg_fetch_all(pg_query($this->conn, $sql));		
	}*/
	
	public function searchForName($name,$limit=10,$offset=1) {
		
		$rsp = file_get_contents("http://ecat-ws.gbif.org/ws/usage/?q=".urlencode($name)."&pagesize=$limit&p=$offset&image=thumb&ranks=kpcofg");
		$res= json_decode($rsp);
		
		if(count($res)<1 )
		    return array();
		
		$names=array();
		$namesToQuery=array();
		foreach($res as &$rec) {
		    $id=(int)$rec->taxonID;
		    $namesToQuery[]=$id;
		    $names[$id]=array();
			$names[$id]['id']=$id;
			$names[$id]['name']=$rec->scientificName;
			@$names[$id]['numNicheModels']=$rec->numNicheModels;
			@$names[$id]['numOccurrences']=$rec->numOccurrences;
			@$names[$id]['imageURL']=$rec->imageURL;
		}	
		$sql="select n.nub_usage_id,count(id) as num_distributions, count(resource_fk) as num_resources 
        from  distribution as d inner join name_usage as n on d.clb_usage_id=n.clb_usage_id where n.nub_usage_id in(".implode(",",$namesToQuery).") group by n.nub_usage_id";
		
        error_log($sql);
		$resfromdb=pg_fetch_all(pg_query($this->conn, $sql));
		$finalResult=array();
		if($resfromdb) {
    		foreach($resfromdb as &$r) {
    		    $names[(int)$r['nub_usage_id']]['numDistributions']=(int)$r['num_distributions'];
    		    $names[(int)$r['nub_usage_id']]['numResources']=(int)$r['num_resources'];

    		}
		}
		foreach($names as $name) {
		    if(isset($name['id'])) {
    		    if(!isset($name['numDistributions'])) {
    		        $name['numDistributions']=0;
    		        $name['numResources']=0;
    		    }
    		    $finalResult[]=$name;		        
		    }		    
		}

        return $finalResult;
	}	
	

}


?>
