<?php

//adjust for the SDR database
$host="localhost";
$dbname="sdr";
$user="postgres";
$pass="";
$psqlfol = "/usr/local/pgsql/bin";
$shpfile = "/Users/jatorre/Desktop/red_list/carnivora/all_GMA_CARNIVORA.shp";
$namesResolved = "/Users/jatorre/Desktop/red_list/carnivora/names_resolved.txt";

//Mapping
$originalNameIdField="name_id";

//metadata
$resourceName="2008 IUCN Red List of Threatened Species";
$resourceCode="RED_LIST2008";
$resourceUuid="a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a17";

$distribution_type=2;   //1=NamedArea,2=Geometry,3=Raster/Grid
$spatial_resolution=1;  //1=world,2=Country,3=County
$spatial_accuracy=1;  //1=world,2=Country,3=County
$record_base=2;         //1=Derived Countries from PD, 2=Range Maps for experts
$confidence_by_source=1;
//---------

$fol = dirname(__FILE__);
$conn = pg_connect ("host=$host dbname=$dbname user=$user password=$pass");


echo ("\n\nStarting import script...\n\n");

//Delete in case there was a previous import table there
runSqlCommand("DROP TABLE imported_shapefile");

//Import the shapefile into PostGIS as a new table called imported_shapefile.
//Adjust path to shp2pgsql, the SRS of the Shapefile and the encoding. (normally 4326, LATIN1)
exec("$psqlfol/shp2pgsql -s4326 -i -WUTF8 -I -c $shpfile imported_shapefile | $psqlfol/psql -h$host -d$dbname -U$user");



echo ("imported_shapefile in DB.\n\n");


//create the originalNameIdField to understand it later
""
runSqlCommand("ALTER TABLE imported_shapefile ADD COLUMN original_name_id character varying(100);");
runSqlCommand("UPDATE imported_shapefile SET original_name_id = CASE WHEN subspecies is not null THEN sci_name ||' '||subspecies ELSE sci_name END ");




//Now we import into a temporary table the names from GROMS as given from ECAT
//this file should only have original_name (from us and used in the shapefile) clb_usage_id nub_usage_id

//start removing and recreating the imported_nameds table
runSqlCommand("DROP TABLE imported_ecat_names");
//create it
$sql=<<<SQL
CREATE TABLE imported_ecat_names
(
   original_name_id character varying(100),    
   clb_usage_id integer, 
   nub_usage_id integer, 
   CONSTRAINT imported_ecat_names_pk PRIMARY KEY (original_name_id)
) WITH (OIDS=FALSE)
;
SQL;
runSqlCommand($sql);
//runSqlCommand("\COPY imported_ecat_names FROM '$namesResolved' WITH NULL as ''");
runSqlCommand("\COPY imported_ecat_names FROM '$namesResolved'");
echo ("imported_ecat_names written in the DB.\n\n");



//create the new resource if it does not exist
$sql="SELECT id from resource WHERE code='$resourceCode'";
$result = pg_query($conn, $sql);
if(pg_num_rows($result)<1) {
    //does not exist. We create it
    $sql="INSERT INTO resource(resource_key,code,resourcename) VALUES ('$resourceUuid','$resourceCode','$resourceName')";
    $result2=pg_query($conn, $sql); 
    
    $sql = "SELECT currval('resource_id3_seq') AS last_value";
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



$sql=<<<SQL
    insert into distribution(
            clb_usage_id,name_fk,original_name_id,created_when,map_source, distribution_type_fk,spatial_resolution_fk, 
            spatial_accuracy,record_base_fk,confidence_by_source,is_public,resource_fk)
    select distinct 
        clb_usage_id,
        clb_usage_id as name_fk,
        ims.original_name_id, 
        (creat_year||'01'||'01')::timestamp, 
        mapsource, 
        $distribution_type as distribution_type_fk,
        $spatial_resolution as spatial_resolution_fk, 
        $spatial_accuracy as spatial_accuracy, 
        $record_base as record_base_fk, 
        $confidence_by_source as confidence_by_source, 
        true as is_public,
        $resourceId as resource_fk
    from imported_shapefile as ims inner join imported_ecat_names as ien on ims.original_name_id=ien.original_name_id

    
SQL;

runSqlCommand($sql);

//Insert the status_tags
$sql=<<<SQL
insert into status_tags(tag,occurrence_status_fk)
select distinct 
    CASE WHEN new_pres=1 THEN 'Extant' 
    WHEN new_pres=2 THEN 'Probably Extant' 
    WHEN new_pres=3 THEN 'Possibly Extant' 
    WHEN new_pres=4 THEN 'Possibly Extinct' 
    WHEN new_pres=5 THEN 'Extinct (post-1500)' 
    WHEN new_pres=6 THEN 'Presence Uncertain' 
    WHEN new_pres=14 THEN 'Historical' ELSE
    new_pres||'' END as tag, 

    CASE WHEN new_pres=1 THEN 1 -- Extant = present
    WHEN new_pres=2 THEN 2  -- Probably Extant = present
    WHEN new_pres=3 THEN 2 -- Possibly Extant = doubtful
    WHEN new_pres=4 THEN 2  -- Possibly Extinct = doubtful
    WHEN new_pres=5 THEN 3  -- Extinct (post-1500) = absent
    WHEN new_pres=6 THEN 2  -- Presence Uncertain = doubtful
    WHEN new_pres=14 THEN 2 ELSE  -- Historical = doubtful
    new_pres END as occurrence_status_fk 

    from imported_shapefile
where status not in (select tag from status_tags)
SQL;
runSqlCommand($sql);

//Insert the status_tags_resources
$sql=<<<SQL
insert into status_tags_resources(status_tags_fk,resource_fk)
select id as status_tags_fk, $resourceId as resource_fk from status_tags WHERE tag in 
('Extant','Probably Extant','Possibly Extant','Possibly Extinct','Extinct (post-1500)','Presence Uncertain','Historical')
SQL;
runSqlCommand($sql);


//Insert the distribution units
//MAP HERE THE VALUES BETWEEN nativeness_fk, life_stage_fk and occurrence_status_fk between the original source and
//the SDR common data model
$sql=<<<SQL
insert into distribution_unit(occurrence_status_fk,start_day_in_year,end_day_in_year,distribution_fk,nativeness_fk,seasonality_fk,life_stage_fk,interpreted_geom,status_tags_fk)
select 

    CASE WHEN new_pres=1 THEN 1 -- Extant = present
    WHEN new_pres=2 THEN 2  -- Probably Extant = present
    WHEN new_pres=3 THEN 2 -- Possibly Extant = doubtful
    WHEN new_pres=4 THEN 2  -- Possibly Extinct = doubtful
    WHEN new_pres=5 THEN 3  -- Extinct (post-1500) = absent
    WHEN new_pres=6 THEN 2  -- Presence Uncertain = doubtful
    WHEN new_pres=14 THEN 2 ELSE  -- Historical = doubtful
    new_pres END as occurrence_status_fk,
    
    d.id as distribution_fk,
    
    
    CASE WHEN new_orig=1 THEN 1 -- Native = "native"
    WHEN new_orig=2 THEN 2 -- Reintroduced = ""introduced"
    WHEN new_orig=3 THEN 2 -- Introduced = ""introduced"
    WHEN new_orig=4 THEN 4 -- Vagrant = "invasive"
    WHEN new_orig=5 THEN 6 ELSE -- Origin Uncertain = "Unknown"
    null END as nativeness_fk,
    
    CASE WHEN seasonalit=1 THEN 1 -- Resident = Resident
    WHEN seasonalit=2 THEN 2 -- Breeding Season = Breeding Season
    WHEN seasonalit=3 THEN 3 -- Non-breeding Season = Non-breeding Season
    WHEN seasonalit=4 THEN 4 -- Passage = Passage
    WHEN seasonalit=5 THEN 5 ELSE -- Seasonal occurrence uncertain = uncertain
    null END as seasonality_fk,    
    null as life_stage_fk,   --the source does not specify life stage
    the_geom as interpreted_geom,
    st.id as status_tags_fk 
from imported_shapefile as ish inner join distribution as d on ish.original_name_id=d.original_name_id left join status_tags as st on ish.status=st.tag
WHERE d.resource_fk=$resourceId
SQL;
runSqlCommand($sql);


//remove temporary tables


echo ("\n\nFINISHED!!!\n\n");
runSqlCommand("DROP TABLE imported_ecat_names");
runSqlCommand("DROP TABLE imported_shapefile");


runSqlCommand("vacuum analyze resource");
runSqlCommand("vacuum analyze distribution");
runSqlCommand("vacuum analyze status_tags");
runSqlCommand("vacuum analyze distribution_unit");
runSqlCommand("vacuum analyze name_usage");


function runSqlCommand($command) {
    global $psqlfol,$host,$dbname,$user,$pass;
    exec("$psqlfol/psql -h$host -d$dbname -U$user -c\"$command\"");
}

?>