<?php

//adjust for the SDR database
$host="localhost";
$dbname="sdr";
$user="postgres";
$pass="";
$psqlfol = "/usr/local/pgsql/bin";
$resourceIdToRemove=14;

$sql=<<<SQL
    DELETE FROM distribution_unit WHERE distribution_fk in (SELECT id from distribution 
        WHERE resource_fk=$resourceIdToRemove)
SQL;
runSqlCommand($sql);

$sql=<<<SQL
    delete from name_usage where 
        clb_usage_id in (select clb_usage_id from distribution where resource_fk=$resourceIdToRemove) and 
        clb_usage_id not in (select clb_usage_id from distribution where resource_fk<>$resourceIdToRemove)
SQL;
runSqlCommand($sql);

$sql=<<<SQL
    delete from defined_area_unit where distribution_unit_fk in 
    (select du.id from distribution_unit as du inner join distribution as d on du.distribution_fk=d.id WHERE d.resource_fk=$resourceIdToRemove)
SQL;
runSqlCommand($sql);


$sql=<<<SQL
    DELETE FROM status_tags_resources WHERE resource_fk =$resourceIdToRemove
SQL;
runSqlCommand($sql);

$sql=<<<SQL
    DELETE FROM distribution WHERE resource_fk =$resourceIdToRemove
SQL;
runSqlCommand($sql);


$sql=<<<SQL
    DELETE FROM resource WHERE id =$resourceIdToRemove
SQL;
runSqlCommand($sql);


//clean tables after:

//dead distribution_units without defined_area_units
$sql=<<<SQL
delete from distribution_unit WHERE id in (
select du.id from distribution_unit as du inner join distribution as d on du.distribution_fk=d.id
left join defined_area_unit as da on da.distribution_unit_fk=du.id 
where da.id is null and d.distribution_type_fk=1
)
SQL;
runSqlCommand($sql);

//dead distribution_units without distribution
$sql=<<<SQL
delete from distribution_unit WHERE id in (
select du.id from distribution_unit as du left join distribution as d on du.distribution_fk=d.id
where d.id is null
)
SQL;
runSqlCommand($sql);


//dead defined_area_unit
$sql=<<<SQL
delete from defined_area_unit where id in (
select da.id from defined_area_unit as da left join distribution_unit as du on da.distribution_unit_fk=du.id where du.id is null)
)
SQL;
runSqlCommand($sql);


//dead distributions without resources
$sql=<<<SQL
delete from distribution WHERE id in (
select d.id from distribution as d left join resource as r on d.resource_fk=r.id
where r.id is null
)
SQL;
runSqlCommand($sql);


//dead distributions without names
$sql=<<<SQL
delete from distribution WHERE id in (
select d.id from distribution as d left join name_usage as n on d.clb_usage_id=n.clb_usage_id
where n.clb_usage_id is null
)
SQL;
runSqlCommand($sql);



function runSqlCommand($command) {
    global $psqlfol,$host,$dbname,$user,$pass;
    exec("$psqlfol/psql -h$host -d$dbname -U$user -c\"$command\"");
}

?>