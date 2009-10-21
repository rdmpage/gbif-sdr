<?php

/** 
 * The base configurations of SDR.
**/

/** The name of the database for Runnity */
define('DB_NAME', 'sdr');

/** PostgreSQL database username */
define('DB_USER', 'postgres');

/** PostgreSQL database password */
define('DB_PASSWORD', 'atlas');

/** PostgreSQL hostname */
define('DB_HOST', 'localhost');

/** GEOSERVER URL */
define('GEOSERVER_URL', 'http://localhost:8080/geoserver/');

/** GEOSERVER URL */
define('DATA_URL', 'http://localhost/amfphp/');

/** Caching folder */
define('CACHE_FOLDER', '/tmp/sdr/');

/** Google API Key */
define('GMAP_KEY', 'ABQIAAAAtDJGVn6RztUmxjnX5hMzjRTb-vLQlFZmc2N8bgWI8YDPp5FEVBTeJc72_716EfYqx-s8UGt88XqC9w');


/** WMS proxies */
define('WMS_PROXY', 'http://localhost/wmsproxy.php');
define('WMS_BIG_PROXY', 'http://localhost/wmsbig.php');

/** ECAT services */
define('ECAT_SERVICES', 'http://ecat-ws.gbif.org/ws/');




?>