<?php

//adjust for the SDR database
$host="ec2-174-129-85-138.compute-1.amazonaws.com";
$dbname="sdr";
$user="postgres";
$pass="atlas";
$psqlfol = "/usr/local/pgsql/bin";
$shpfile = "/Users/jatorre/workspace/Desktop/groms_shp/groms.shp";
$namesResolved = "/Users/jatorre/Desktop/gromsnames/groms_resolved_names.txt";

//Mapping
$originalNameIdField="name_id";

//metadata
$resourceName="Global Registry of Migratory Species";
$resourceCode="GROMS";
$resourceUuid="a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a13";

$distribution_type=2;   //1=NamedArea,2=Geometry,3=Raster/Grid
$spatial_resolution=1;  //1=world,2=Country,3=County
$record_base=2;         //1=Derived Countries from PD, 2=Range Maps for experts
//---------

$fol = dirname(__FILE__);
$conn = pg_connect ("host=$host dbname=$dbname user=$user password=$pass");


echo ("\n\nStarting import script...\n\n");

//Import the shapefile into PostGIS as a new table called imported_shapefile.
//Adjust path to shp2pgsql, the SRS of the Shapefile and the encoding. (normally 4326, LATIN1)
            //exec("$psqlfol/shp2pgsql -s4326 -i -WUTF8 -I -c $shpfile imported_shapefile > $fol/imported_shapefile.sql");

echo ("imported_shapefile SQL file generated.\n\n");

//Delete in case there was a previous import table there
runSqlCommand("DROP TABLE imported_shapefile");

//import into the DB the imported_shapefile file
            //exec("$psqlfol/psql -h$host -d$dbname -U$user -f$fol/imported_shapefile.sql");
echo ("imported_shapefile written in the DB.\n\n");

//rename the originalNameIdField to understand it later
            //runSqlCommand("ALTER TABLE imported_shapefile RENAME $originalNameIdField  TO original_name_id;");




//Now we import into a temporary table the names from GROMS as given from ECAT
//this file should only have original_name (from us and used in the shapefile) clb_usage_id nub_usage_id

//start removing and recreating the imported_nameds table
runSqlCommand("DROP TABLE imported_ecat_names");
//create it
$sql=<<<SQL
CREATE TABLE imported_ecat_names
(
   original_name_id character varying(20), 
   clb_usage_id integer, 
   nub_usage_id integer, 
   CONSTRAINT imported_ecat_names_pk PRIMARY KEY (original_name_id)
) WITH (OIDS=FALSE)
;
SQL;
runSqlCommand($sql);
runSqlCommand("\COPY imported_ecat_names FROM '$namesResolved' WITH NULL as ''");
echo ("imported_ecat_names written in the DB.\n\n");



//create the new resource if it does not exist
$sql="SELECT id from resource WHERE code='$resourceCode'";
$result = pg_query($conn, $sql);
if(pg_num_rows($result)<1) {
    //does not exist. We create it
    $sql="INSERT INTO resource(resource_key,code,resourcename) VALUES ('$resourceUuid','$resourceCode','$resourceName')";
    $result2=pg_query($this->conn, $sql); 
    
    $sql = "SELECT currval('users_id_seq') AS last_value";
    $result3=pg_query($conn, $sql);
    $res=pg_fetch_assoc($result3);
    $resourceId=$res['last_value'];
    echo ("created new resource with ID $resourceId.\n\n");
} else {
    $res=pg_fetch_assoc($result);
    $resourceId=$res['id'];
    echo ("resource ALREADY existing with ID $resourceId.\n\n");
}



//Now we will start importing into the SDR tables the different components
//Import the new names into the DB, if they are not already on the system
$sql="insert into name_usage select clb_usage_id,nub_usage_id from imported_ecat_names WHERE imported_ecat_names.clb_usage_id NOT IN (SELECT clb_usage_id FROM name_usage)";
runSqlCommand($sql);

//Looping through all the records in the shapefile and generate tiles
$sql="select s.*,n.clb_usage_id as name_id from imported_shapefile as s inner join imported_ecat_names as n on s.original_name_id=n.original_name_id order by s.original_name_id";
$result=pg_query($conn, $sql);

$curDist=array();
$curNameId=0;
while ($row = pg_fetch_array($result, NULL, PGSQL_ASSOC)) {
	if($curNameId!=$row['original_name_id']) {
		//This is a new distribution
		
		//save first the previous distribution in case there was one
		if ($curNameId!=0) {
			$sql="
			INSERT INTO distribution(clb_usage_id,resource_fk,year_start,year_end,created_when,spatial_resolution_fk,
					spatial_scope_named_area_fk,record_base_fk,spatial_accuracy,confidence_by_source,distribution_type_fk
					is_public,map_source)
			";
		}
		
		$curDist=array();
	}
}


//THIS IS WHERE THE MAPPING TAKES PLACE IN A SQL STATEMENT
$sql=<<<SQL
insert into distribution
    (occurrence_status_fk,status_tags_fk,start_day_in_year,end_day_in_year,distribution_fk,nativeness_fk,interpreted_geom)

select tdu.occurrence_status_fk,st.id as status_tags_fk,start_day_in_year,end_day_in_year, d.id as distribution_fk,nativeness_fk,interpreted_geom from imported_shapefile as tdu inner join imported_ecat_names as sn on tdu.original_name_id=sn.original_name_id inner join distribution as d on sn.id=d.clb_usage_id and d.resource_fk=2 right join status_tags as st on tdu.status=st.tag;
SQL;











function runSqlCommand($command) {
    global $psqlfol,$host,$dbname,$user,$pass;
    exec("$psqlfol/psql -h$host -d$dbname -U$user -c\"$command\"");
}

?>