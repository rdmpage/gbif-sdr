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
	    if (count($stmt->fetchAll()) > 0) {
	        throw new Exception('Username already registered',104);
	    }	     
	    
	    //Check if username or password are in the DB
	    $sql="SELECT id from users WHERE email=:email";
		$stmt = $this->dbHandle->prepare($sql);
	    $stmt->bindParam(':email', $email);	     
	    $stmt->execute();
	    if (count($stmt->fetchAll()) > 0) {
	        throw new Exception('Email already registered',105);
	    }	   
	    
	    $sql="INSERT INTO users(username,pass,project_name,email) VALUES(:username,:password,:projectname,:email)";
		$stmt = $this->dbHandle->prepare($sql);
        $stmt->bindParam(':email', $email);	     
	    $stmt->bindParam(':username', $username);	     
	    $stmt->bindParam(':password', $password);	     
	    $stmt->bindParam(':projectname', $projectname);	        	    
	    $stmt->execute();	       
	    
	    //get last ID
	    $sql = "SELECT currval('users_id_seq') AS last_value";
	    $resultId = $this->dbHandle->query($sql)->fetch(PDO::FETCH_ASSOC); 
	    
	    
	    $user=array();
	    $user['id']=$resultId['last_value'];
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
	
	public function getMostPopularSpecies($limit) {
	    $sql="select num_views,nub_concept_id, scientific_name,id from species_view_stats as svs inner join scientific_name as s on svs.species_fk=s.id order by num_views DESC limit :limit";
		$stmt = $this->dbHandle->prepare($sql);
		$stmt->bindParam(':limit', $limit);	  
	    $stmt->execute(); 
		return $stmt->fetchAll(PDO::FETCH_ASSOC);	    
	    
	    
	}
	
	public function getGbifDetailsByNameId($nameId) {
	    
	    //update the species_view_stats
	    $sql="select update_species_view_stats(:nameId)";
	    $stmt = $this->dbHandle->prepare($sql);
	    $stmt->bindParam(':nameId', $nameId);	     
	    $stmt->execute();
	    
	    $sql="select nub_concept_id,scientific_name from scientific_name where id=:nameId";
		$stmt = $this->dbHandle->prepare($sql);
	    $stmt->bindParam(':nameId', $nameId); 
	    $stmt->execute();
	    

	    
	    
		return $stmt->fetch(PDO::FETCH_ASSOC);	    
	    
	}
	
	public function getNameById($nameId) {
		$sql="select d.id,resourcename, scientific_name,nub_concept_id, year_start, year_end, spatial_resolution_fk, record_base_fk, ". 
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
	
	public function getSpeciesDetailsByNameId($speciesId) {
	    
	}
	
	public function searchForName($name,$limit=10,$offset=0) {
		//$time_start = microtime_float();
		/* $conn = pg_pconnect("host=ec2-174-129-85-138.compute-1.amazonaws.com port=5432 dbname=sdr user=postgres password=atlas",PGSQL_CONNECT_FORCE_NEW);
		$result = pg_query_params($conn, "select ns.*, n.scientific_name ".
			"from name_summary as ns inner join scientific_name as n on ns.name_fk=n.id ".
			"where n.scientific_name like $1  order by n.scientific_name limit $2 offset $3",array($name.'%',$limit,$offset));
		
		return pg_fetch_all($result); */

		
		
	    $stmt = $this->dbHandle->prepare("select ns.*, n.scientific_name ".
			"from name_summary as ns inner join scientific_name as n on ns.name_fk=n.id ".
			"where n.scientific_name like :param  order by n.scientific_name limit 10 offset 0");
	    //$name = $name . "%";
	    $stmt->bindParam(':param', $name, PDO::PARAM_STR,255); 
		//$stmt->bindParam(':limit', $limit2, PDO::PARAM_INT); 
		//$stmt->bindParam(':offset', $offset2, PDO::PARAM_INT); 
		
		//return($stmt->getSQL(array(':param' => $name.'%')) );
		
	    $stmt->execute(array(':param' => $name.'%'));
	    
	    
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







class PDOTester extends PDO {
	public function __construct($dsn, $username = null, $password = null, $driver_options = array())
	{
		parent::__construct($dsn, $username, $password, $driver_options);
		$this->setAttribute(PDO::ATTR_STATEMENT_CLASS, array('PDOStatementTester', array($this)));
	}
}

class PDOStatementTester extends PDOStatement {
	const NO_MAX_LENGTH = -1;

	protected $connection;
	protected $bound_params = array();

	protected function __construct(PDO $connection)
	{
		$this->connection = $connection;
	}

	public function bindParam($paramno, &$param, $type = PDO::PARAM_STR, $maxlen = null, $driverdata = null)
	{
		$this->bound_params[$paramno] = array(
			'value' => &$param,
			'type' => $type,
			'maxlen' => (is_null($maxlen)) ? self::NO_MAX_LENGTH : $maxlen,
			// ignore driver data
		);

		$result = parent::bindParam($paramno, $param, $type, $maxlen, $driverdata);
	}

	public function bindValue($parameter, $value, $data_type = PDO::PARAM_STR)
	{
		$this->bound_params[$parameter] = array(
			'value' => $value,
			'type' => $data_type,
			'maxlen' => self::NO_MAX_LENGTH
		);
		parent::bindValue($parameter, $value, $data_type);
	}

	public function getSQL($values = array())
	{
		$sql = $this->queryString;

		if (sizeof($values) > 0) {
			foreach ($values as $key => $value) {
				$sql = str_replace($key, $this->connection->quote($value), $sql);
			}
		}

		if (sizeof($this->bound_params)) {
			foreach ($this->bound_params as $key => $param) {
				$value = $param['value'];
				if (!is_null($param['type'])) {
					$value = self::cast($value, $param['type']);
				}
				if ($param['maxlen'] && $param['maxlen'] != self::NO_MAX_LENGTH) {
					$value = self::truncate($value, $param['maxlen']);
				}
				if (!is_null($value)) {
					$sql = str_replace($key, $this->connection->quote($value), $sql);
				} else {
					$sql = str_replace($key, 'NULL', $sql);
				}
			}
		}
		return $sql;
	}

	static protected function cast($value, $type)
	{
		switch ($type) {
			case PDO::PARAM_BOOL:
				return (bool) $value;
				break;
			case PDO::PARAM_NULL:
				return null;
				break;
			case PDO::PARAM_INT:
				return (int) $value;
			case PDO::PARAM_STR:
			default:
				return $value;
		}
	}

	static protected function truncate($value, $length)
	{
		return substr($value, 0, $length);
	}
}


?>
