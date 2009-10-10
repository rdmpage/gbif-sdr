<?php

//adjust for the SDR database
$host="localhost";
$dbname="sdr";
$user="postgres";
$pass="";
$psqlfol = "/usr/local/pgsql/bin";
$resourceIdToRemove=12;

$sql=<<<SQL
    DELETE FROM distribution_unit WHERE distribution_fk in (SELECT id from distribution 
        WHERE resource_fk=$resourceIdToRemove)
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

function runSqlCommand($command) {
    global $psqlfol,$host,$dbname,$user,$pass;
    exec("$psqlfol/psql -h$host -d$dbname -U$user -c\"$command\"");
}

?>