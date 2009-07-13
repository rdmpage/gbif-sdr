<?php

include("PolylineEncoder.php");

class SDRServices {
	
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
	
	public function getTaxonomy($parentType,$parentName) {
	    if ($parentType=="animal_class") {
	        $stmt = $this->dbHandle->prepare("select distinct animalgroup from accessexport where animal_class=:parent_name  order by animalgroup");	        
	    } 
	    if ($parentType=="animalgroup") {
	        $stmt = $this->dbHandle->prepare("select distinct scientifiname from accessexport where animalgroup=:parent_name  order by scientifiname");
	    }
	    
        $stmt->bindParam(':parent_name', $parentName, PDO::PARAM_STR, 255);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}
	
	public function getTaxon($parent,$level) {
	    if ($level==0) {
	        $stmt = $this->dbHandle->prepare("select distinct null as id,animal_class as name, true as has_children,null as english,null as spanish,null as french,null as german, icons.icon_url from accessexport left join icons on accessexport.animal_class=icons.species_name order by animal_class");
	    }
	    if ($level==1) {
	        $stmt = $this->dbHandle->prepare("select distinct null as id,animalgroup as name, true as has_children,null as english,null as spanish,null as french,null as german, icons.icon_url from accessexport left join icons on accessexport.animalgroup=icons.species_name where animal_class=:parent  order by animalgroup");
	        $stmt->bindParam(':parent', $parent, PDO::PARAM_STR, 255);
	    }
	    if ($level==2) {
	        $stmt = $this->dbHandle->prepare("select distinct accessexport.species_id as id,scientifiname as name, false as has_children,accessexport.english,accessexport.spanish,accessexport.french,accessexport.german, icons.icon_url from accessexport inner join shapefiles on accessexport.species_id=shapefiles.species_id  left join icons on accessexport.scientifiname=icons.species_name where animalgroup=:parent order by scientifiname");
		$stmt->bindParam(':parent', $parent, PDO::PARAM_STR, 255);        
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);        
        
	}
	
	public function logout() {
	    session_destroy();
	}	    
	
	public function parseWKT() {
	    
	    $wkt = new WKT;
	    
	    $geometry = $wkt->parse("multipolygon","MULTIPOLYGON(((111.734804386065 -6.90149335296094,111.771942138672 -6.90338945388794,112.120468139648 -6.9407696723938,112.478454589844 -7.00991773605347,112.608573913574 -7.11767244338989,112.740226745605 -7.24754667282104,112.704536437988 -7.44796466827393,112.828254699707 -7.65742206573486,112.88208770752 -7.70180368423462,113.276351928711 -7.85984468460083,113.466255187988 -7.89120578765869,113.76237487793 -7.89692878723145,113.965042114258 -7.81385707855225,114.15202331543 -7.80710649490356,114.434432983398 -7.91453838348389,114.455856323242 -8.00347137451172,114.341270446777 -8.1943941116333,114.272827148438 -8.41698265075684,114.532943725586 -8.76599407196045,114.308418273926 -8.6711368560791,113.947479248047 -8.58294200897217,113.678352355957 -8.46912002563477,113.277450561523 -8.33015155792236,113.039443969727 -8.33066654205322,112.774642944336 -8.44564533233643,112.618019104004 -8.47140216827393,112.29704284668 -8.42760467529297,112.118486754367 -8.41064400161469,111.734804386065 -6.90149335296094)))");
	    
	    return $geometry;
	}
	
	
	public function searchTaxonByName($search) {
	    $stmt = $this->dbHandle->prepare("select distinct accessexport.redlist_id,accessexport.cms_status_2,accessexport.cms_description,accessexport.external_url, accessexport.scientifiname as name, accessexport.species_id as id,accessexport.english as common_name from accessexport inner join shapefiles on accessexport.species_id=shapefiles.species_id where accessexport.scientifiname ilike :param or accessexport.english ilike :param order by accessexport.scientifiname limit 8");
	    $search = $search . "%";
	    $stmt->bindParam(':param', $search); 
	    $stmt->execute();
	    
	    
	    return $stmt->fetchAll(PDO::FETCH_ASSOC);
	}	
	
	public function getWorldStats() {
	    $result=array();
	    $result['numberSpecies'] = 1325;
	    return $result;
	}
	
	public function getCountryStatsByISO($iso,$lng,$lat){
	    return 'ES';
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
	
	public function setIcon($name,$iconUrl) {
	    $sql="INSERT INTO icons(icon_url,species_name) VALUES(?,?)";
	    $stmt = $this->dbHandle->prepare($sql);
	    $stmt->execute(array($iconUrl,$name));
	    return;
	    
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
