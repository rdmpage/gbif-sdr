<?php

//adjust for the SDR database
$host="localhost";
$dbname="sdr";
$user="postgres";
$pass="";
$psqlfol = "/usr/local/pgsql/bin";
$dwc_distribution = "/Users/jatorre/Desktop/canada/dwc/distribution.txt";
$namesResolved = "/Users/jatorre/Desktop/canada/canada_map.txt";
$namedAreasShp = "/Users/jatorre/Desktop/province/PROVINCE.SHP";

//The dwc_distribution files come in this format: (WE ARE NOT USING THE meta.xml file sorry :( )
//sourceNameId  locationId  locality    countryCode occurrencestatus    establishmentMeans


//metadata
$resourceName="Canadensys test dataset";
$resourceCode="CANADENSYS";
$resourceUuid="a0eebc99-9c0b-4ef8-bb6d-6bb9bd380a15";

$distribution_type  =1;   //1=NamedArea,2=Geometry,3=Raster/Grid
$spatial_resolution =2;  //1=world,2=Country,3=County
$spatial_accuracy   =2;  //1=world,2=Country,3=County
$record_base        =3;         //1=Derived Countries from PD, 2=Range Maps for experts
$confidence_by_source=1;
//---------

$fol = dirname(__FILE__);
$conn = pg_connect ("host=$host dbname=$dbname user=$user password=$pass");


echo ("\n\nStarting import script...\n\n");

runSqlCommand("DROP TABLE imported_dwc_distribution");

$sql=<<<SQL
CREATE TABLE imported_dwc_distribution
(
   original_name_id integer,    
   area_code character varying(255), 
   area_name character varying(255), 
   country_code character varying(10),
   status character varying(25),
   establishment character varying(25)
) WITH (OIDS=FALSE)
;
SQL;
runSqlCommand($sql);
runSqlCommand("\COPY imported_dwc_distribution FROM '$dwc_distribution' WITH NULL as ''");



//start removing and recreating the imported_nameds table
runSqlCommand("DROP TABLE imported_ecat_names");
//create it
$sql=<<<SQL
CREATE TABLE imported_ecat_names
(
   original_name_id integer,    
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

runSqlCommand("ALTER TABLE imported_dwc_distribution ADD COLUMN id serial");
runSqlCommand("insert into name_usage(clb_usage_id,nub_usage_id ) select clb_usage_id,nub_usage_id from imported_ecat_names");


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

$sql=<<<SQL
    insert into distribution(
            clb_usage_id,name_fk,original_name_id, distribution_type_fk,spatial_resolution_fk, 
            spatial_accuracy,record_base_fk,confidence_by_source,is_public,resource_fk)
            SELECT distinct
            	clb_usage_id,
            	clb_usage_id as name_fk,
            	i.original_name_id,
                $distribution_type as distribution_type_fk,
                $spatial_resolution as spatial_resolution_fk, 
                $spatial_accuracy as spatial_accuracy, 
                $record_base as record_base_fk, 
                $confidence_by_source as confidence_by_source, 
                true as is_public,
                $resourceId as resource_fk
            from imported_dwc_distribution as i inner join imported_ecat_names as ien on i.original_name_id = ien.original_name_id
SQL;

runSqlCommand($sql);


//Insert the status_tags
$sql=<<<SQL
insert into status_tags(tag,occurrence_status_fk)
select distinct status as tag, 
    CASE 
        WHEN status='present' THEN 1 
        WHEN status='irregular' THEN 9 
        WHEN status='excluded' THEN 3 
        WHEN status='doubtful' THEN 2
	ELSE null END
    as occurrence_status_fk 
from imported_dwc_distribution
where status not in (select tag from status_tags)
SQL;
runSqlCommand($sql);

//Insert the status_tags_resources
$sql=<<<SQL
insert into status_tags_resources(status_tags_fk,resource_fk)
select id as status_tags_fk, $resourceId as resource_fk from status_tags WHERE tag in (SELECT status from imported_dwc_distribution)
SQL;
runSqlCommand($sql);


//Insert the distribution units
//MAP HERE THE VALUES BETWEEN nativeness_fk, life_stage_fk and occurrence_status_fk between the original source and
//the SDR common data model
runSqlCommand("ALTER TABLE distribution_unit ADD COLUMN temp_id integer");



$sql=<<<SQL
insert into distribution_unit(occurrence_status_fk,start_day_in_year,end_day_in_year,distribution_fk,nativeness_fk,life_stage_fk,status_tags_fk,temp_id)
select 
    CASE 
    WHEN status = 'present' THEN 1 
    WHEN status = 'irregular' THEN 9 
    WHEN status = 'excluded' THEN 3 
    WHEN status = 'doubtful' THEN 2
    ELSE null END
    as occurrence_status_fk,

    null as start_day_in_year,
    null as end_day_in_year, 
    d.id as distribution_fk,
    CASE WHEN 1=1 THEN 6 ELSE 0 END as nativeness_fk,
    CASE WHEN 1=1 THEN null ELSE 1 END  as life_stage_fk,
    st.id as status_tags_fk,
    ish.id as temp_id
from imported_dwc_distribution as ish inner join distribution as d on ish.original_name_id=d.original_name_id left join status_tags as st on ish.status=st.tag
WHERE d.resource_fk=$resourceId
SQL;
runSqlCommand($sql);


$sql=<<<SQL
insert into defined_area_unit(distribution_unit_fk,named_area_fk)
select du.id as distribution_unit_fk,na.id as named_area_fk 
from imported_dwc_distribution as idd inner join distribution_unit as du on idd.id=du.temp_id inner join named_area as na on idd.area_code=na.area_code
SQL;
runSqlCommand($sql);

runSqlCommand("ALTER TABLE distribution_unit DROP COLUMN temp_id");


runSqlCommand("vacuum analyze resource");
runSqlCommand("vacuum analyze distribution");
runSqlCommand("vacuum analyze status_tags");
runSqlCommand("vacuum analyze distribution_unit");
runSqlCommand("vacuum analyze defined_area_unit");
runSqlCommand("vacuum analyze name_usage");





echo ("\n\nFINISHED!!!\n\n");
runSqlCommand("DROP TABLE imported_ecat_names");
runSqlCommand("DROP TABLE imported_dwc_distribution");

function runSqlCommand($command) {
    global $psqlfol,$host,$dbname,$user,$pass;
    exec("$psqlfol/psql -h$host -d$dbname -U$user -c\"$command\"");
}

?>