<?php
require_once($_SERVER['DOCUMENT_ROOT'] ."/config.php");
//include("PolylineEncoder.php");

class SDRServices {
	
	function __construct() {
		$this->conn = pg_connect ("host=".DB_HOST." dbname=".DB_NAME." user=".DB_USER." password=".DB_PASSWORD);	
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
        return $result;

	    
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


		$rsp = file_get_contents(ECAT_SERVICES."usage/?id=$nameId");
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
	
	public function getSpeciesDetailsByNameId($speciesId,$source="") {
	    
	    $sql="
			SELECT d.id,code,d.distribution_type_fk as d_type, resourcename,resource_fk as resource_id, 
			(select count(id) from distribution_unit where distribution_fk=d.id) as num_units 
			from distribution as d inner join resource as r on d.resource_fk=r.id inner join name_usage as n on d.clb_usage_id=n.clb_usage_id 
			where n.nub_usage_id=$speciesId";
			
		if ($source!="") {
		    $sql.=" AND r.code='$source'";
		}	

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
			
			//$source['png']=$geoserverUrl."/wms?WIDTH=1024&HEIGHT=1024&SRS=EPSG%3A4326&".
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
		
		$rsp = file_get_contents(ECAT_SERVICES."usage/?q=".urlencode($name)."&pagesize=$limit&p=$offset&image=true&ranks=kpcofg&rating=0");
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
			@$names[$id]['genus']=$rec->genus;
			@$names[$id]['class']=$rec->class;
			@$names[$id]['phylum']=$rec->phylum;
			@$names[$id]['kingdom']=$rec->kingdom;
			@$names[$id]['family']=$rec->family;
			@$names[$id]['order']=$rec->order;
		}	
		$sql="select n.nub_usage_id,count(id) as num_distributions, count(resource_fk) as num_resources 
        from  distribution as d inner join name_usage as n on d.clb_usage_id=n.clb_usage_id where n.nub_usage_id in(".implode(",",$namesToQuery).") group by n.nub_usage_id";
		

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
	
	
	
	
	
	
	
	//API
	public function getDistributionUnitsByLatLng($lat,$lng) {
	    $sql="select distinct id,nub_usage_id,map_source,resourcename,code,resource_id,identifier,name,start_day_in_year,end_day_in_year,tag,color,st_asgeojson(the_geom,4) from sdr_2_view where the_geom && setsrid(makepoint($lng,$lat),4326) and nub_usage_id is not null limit 10";
	    $res1=pg_fetch_all(pg_query($this->conn, $sql)); 
	    
/*	    
	    $sql="select distinct id,nub_usage_id,map_source,resourcename,code,resource_id,identifier,name,start_day_in_year,end_day_in_year,tag,color from sdr_1_view where the_geom && setsrid(makepoint($lat,$lng),4326) and nub_usage_id is not null limit 10";
	    $res2=pg_fetch_all(pg_query($this->conn, $sql)); 
	    return $res1;
*/	    
    	    $sql="select id,nub_usage_id,map_source,resourcename,code,resource_id,identifier,name,start_day_in_year,end_day_in_year,tag,color,st_asgeojson(the_geom,4) FROM sdr_1_view where the_geom && setsrid(makepoint($lng,$lat),4326) and nub_usage_id is not null limit 10";	    
	    if ($res1) {	        
	        return array_merge(pg_fetch_all(pg_query($this->conn, $sql)),$res1); 
	    } else {
	        return pg_fetch_all(pg_query($this->conn, $sql)); 
	    }	    
	}
	
	public function getSpeciesDistributionUnitsById($id) {
	    $sql="select id,map_source,resourcename,code,resource_id,identifier,name,start_day_in_year,end_day_in_year,tag,color,st_asgeojson(the_geom,4) as geojson FROM sdr_2_view where nub_usage_id=$id";
	    $res1=pg_fetch_all(pg_query($this->conn, $sql)); 

   	    $sql="select id,map_source,resourcename,code,resource_id,identifier,name,start_day_in_year,end_day_in_year,tag,color,st_asgeojson(the_geom,4) as geojson FROM sdr_1_view where nub_usage_id=$id";
	    if ($res1) {
     	    return array_merge(pg_fetch_all(pg_query($this->conn, $sql)),$res1);	     
	    } else {
	        return pg_fetch_all(pg_query($this->conn, $sql)); 
	    }
	}
	
	public function getDistributionsBySource($sourceCode,$offset) {
	    $sourceCode=pg_escape_string($sourceCode);
	    $sql="select s.resourcename, s.code,s.id from resource as s where s.code='$sourceCode'";
        $source = pg_fetch_assoc(pg_query($this->conn, $sql));
        if(!$source) {
            return null;
        }

        $species=array();
        $sql="select d.clb_usage_id,n.nub_usage_id from distribution as d inner join name_usage as n on d.clb_usage_id=n.clb_usage_id where d.resource_fk={$source['id']} limit 10 offset $offset";
        $result = pg_fetch_all(pg_query($this->conn, $sql));
        foreach($result as &$rec) {
    		$sp=array();
    		$rsp = file_get_contents(ECAT_SERVICES ."usage/?id=".$rec['clb_usage_id']);
    		$res= json_decode($rsp);   
    		$sp['nub_usage_id']=(int)$rec['nub_usage_id'];
    		$sp['scientificName']=$res->scientificName;
    		//$sp['commonName']=$res->vernacularNames[0];
    		$sp['commonName']="";
    		$sp['imageURL']=$res->imageURL;
    		
    		$species[]=$sp;
        }
        $result=array();
        $result['species']=$species;
        $result['sourceId']=$source['id'];
        $result['source']=$source['resourcename'];
        
		return $result;
		
	}
	
	public function getSources() {
	    $sql="select r.resourcename, r.code,r.id, count(d.id) as num_distributions from resource as r inner join distribution as d on r.id=d.resource_fk group by r.id,resourcename,code";
	    return pg_fetch_all(pg_query($this->conn, $sql)); 
	}
	
	
	
	
	

}


?>
