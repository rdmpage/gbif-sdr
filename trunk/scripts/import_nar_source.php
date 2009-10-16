<?php

//adjust for the SDR database
$host="localhost";
$dbname="sdr";
$user="postgres";
$pass="";
$psqlfol = "/usr/local/pgsql/bin";
$namedAreasShp = "/Users/jatorre/Desktop/province/PROVINCE.SHP";

$fol = dirname(__FILE__);
$conn = pg_connect ("host=$host dbname=$dbname user=$user password=$pass");

$NAcode="iso3166_2";
$NAtitle="ISO 3166-2 incomplete";
$NAdescription="http://en.wikipedia.org/wiki/ISO_3166-2";



echo ("\n\nStarting import script...\n\n");

//Delete in case there was a previous import table there
runSqlCommand("DROP TABLE imported_areas_shapefile");

//check if the source does not exist already
$sql="SELECT id from named_area_reference WHERE code='$NAcode'";
$result = pg_query($conn, $sql);
if(pg_num_rows($result)<1) {
    //does not exist. We create it
    $sql="INSERT INTO named_area_reference(code,title,description) VALUES ('$NAcode','$NAtitle','$NAdescription')";
    $result2=pg_query($conn, $sql); 
    
    $sql = "SELECT currval('named_area_reference_id2_seq') AS last_value";
    $result3=pg_query($conn, $sql);
    $res=pg_fetch_assoc($result3);
    $namedAreareferenceId=$res['last_value'];
    echo ("created new resource with ID $namedAreareferenceId.\n\n");
} else {
    $res=pg_fetch_assoc($result);
    $namedAreareferenceId=$res['id'];
    echo ("resource ALREADY existing with ID $namedAreareferenceId.\n\n  Existing Program\n\n");
    exit();
}


//Import the shapefile into PostGIS as a new table called imported_shapefile.
//Adjust path to shp2pgsql, the SRS of the Shapefile and the encoding. (normally 4326, LATIN1)
exec("$psqlfol/shp2pgsql -s4326 -i -I -c $namedAreasShp imported_areas_shapefile | $psqlfol/psql -h$host -d$dbname -U$user");

$sql="ALTER TABLE named_area_geom ADD COLUMN temp_id character varying(20)";
runSqlCommand($sql);

$sql=<<<SQL
insert into named_area_geom(temp_id,the_geom)
select 
CASE 
	WHEN name='Alberta' THEN 'CA-AB' 
	WHEN name='Manitoba' THEN 'CA-MB' 
	WHEN name='British Columbia' THEN 'CA-BC' 
	WHEN name='Yukon Territory' THEN 'CA-YT' 
	WHEN name='Saskatchewan' THEN 'CA-SK' 
	WHEN name='Quebec' THEN 'CA-QC' 
	WHEN name='New Brunswick' THEN 'CA-NB' 
	WHEN name='Northwest Territories' THEN 'CA-NT' 
	WHEN name='Nova Scotia' THEN 'CA-NS' 
	WHEN name='Ontario' THEN 'CA-ON' 
	WHEN name='Nunavut' THEN 'CA-NU' 
	WHEN name='Prince Edward Island' THEN 'CA-PE' 

ELSE null END as temp_id,
the_geom

from imported_areas_shapefile WHERE name in ('Alberta','Manitoba','British Columbia','Yukon Territory','Saskatchewan','Quebec','New Brunswick','Northwest Territories','Nova Scotia','Ontario','Nunavut','Prince Edward Island') 
SQL;
runSqlCommand($sql);

$sql=<<<SQL
    insert into named_area(area_code,area_name,named_area_reference_fk)
    select 
    CASE 
    	WHEN name='Alberta' THEN 'CA-AB' 
    	WHEN name='Manitoba' THEN 'CA-MB' 
    	WHEN name='British Columbia' THEN 'CA-BC' 
    	WHEN name='Yukon Territory' THEN 'CA-YT' 
    	WHEN name='Saskatchewan' THEN 'CA-SK' 
    	WHEN name='Quebec' THEN 'CA-QC' 
    	WHEN name='New Brunswick' THEN 'CA-NB' 
    	WHEN name='Northwest Territories' THEN 'CA-NT' 
    	WHEN name='Nova Scotia' THEN 'CA-NS' 
    	WHEN name='Ontario' THEN 'CA-ON' 
    	WHEN name='Nunavut' THEN 'CA-NU' 
    	WHEN name='Prince Edward Island' THEN 'CA-PE' 

    ELSE null END as area_code,
    name as area_name,
    $namedAreareferenceId as named_area_reference_fk

    from imported_areas_shapefile WHERE name in ('Alberta','Manitoba','British Columbia','Yukon Territory','Saskatchewan','Quebec','New Brunswick','Northwest Territories','Nova Scotia','Ontario','Nunavut','Prince Edward Island') AND area_code 
SQL;


$sql="UPDATE named_area SET named_area_geom_fk = (SELECT id from named_area_geom WHERE temp_id=named_area.area_code) WHERE named_area_geom_fk is null";
runSqlCommand($sql);
runSqlCommand("ALTER TABLE named_area_geom DROP COLUMN temp_id");

runSqlCommand("DROP TABLE imported_areas_shapefile");

runSqlCommand("vacuum analyze named_area");
runSqlCommand("vacuum analyze named_area_geom");


echo ("\n\nFINISHED!!!\n\n");

function runSqlCommand($command) {
    global $psqlfol,$host,$dbname,$user,$pass;
    exec("$psqlfol/psql -h$host -d$dbname -U$user -c\"$command\"");
}

?>