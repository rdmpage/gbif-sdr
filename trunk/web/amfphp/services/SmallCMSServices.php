<?php

class SmallCMSServices {
	
	function __construct() {
		$this->dbHandle = new PDO('sqlite:'.$_SERVER['DOCUMENT_ROOT'].'/smallcmsfiles/data/smallcms.sqlite');
	}
    
    public function login($user,$pass) {

        // create page view database table
        $sql = "SELECT * FROM admin WHERE username='$user' AND password='$pass'";        
        $result = $this->dbHandle->query($sql);    
        
        if (!$result->fetch()) {
            $_SESSION['logged']=false;
            throw new Exception("user not logged in");
        } else {
            $_SESSION['logged']=true;
            return true;
        }
	}
	
	public function logout() {
	    session_destroy();
	}
	
	public function updateItem($id,$title,$description,$pubdate,$content,$link,$source,$published,$category=1) {

	   
	    if (!$_SESSION['logged']) {
	        throw new Exception("user not logged in");
	    }
	    
	    $this->dbHandle->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING); 
	    
	    $params =array($title,$description,$pubdate,$content,$link,$source,$published,$category,$id);
	    
	    error_log(print_r($params,true));
	    
	    if($id>0) {
	        $sql="UPDATE item set title=?,description=?,pubdate=?,content=?,link=?,source=?,published=?,category=? WHERE id=?";
	        $sth = $this->dbHandle->prepare($sql);
	        $sth->execute($params);
	        return $id ;
	    } else {
	        $sql="INSERT INTO item(title,description,pubdate,content,link,source,category,published) VALUES('$title','$description','$pubdate','$content','$link','$source',$category,'$published')";
	        $this->dbHandle->exec($sql);	       	        
	        return (int)$this->dbHandle->lastInsertId();
	    }
	    
	    
	    
	}
	
	public function getAllItems() {
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
	}		

}
?>