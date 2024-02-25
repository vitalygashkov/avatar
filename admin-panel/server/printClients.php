<?php
include ('config.php'); 

// Database connection info
$dbDetails = array(
    'host' => $config->database->host,
    'user' => $config->database->user,	
    'pass' => $config->database->password,
    'db'   => $config->database->name
);

// DB table to use
$table = 'clients';

// Table's primary key
$primaryKey = 'id';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database. 
// The `dt` parameter represents the DataTables column identifier. CAST(`RAM` as UNSIGNED) AS `RAM`
$columns = array(
    array( 'db' => 'id', 'dt' => 'id' ),
	array( 'db' => 'uuid', 'dt' => 'uuid' ),
    array( 'db' => 'ip',  'dt' => 'ip' ),
    array( 'db' => 'location', 'dt' => 'location' ),
    array( 'db' => 'os', 'dt' => 'os' ),
    array( 'db' => 'user', 'dt' => 'user' ),
    array( 'db' => 'role', 'dt' => 'role' ),
	array( 'db' => 'antivirus', 'dt' => 'antivirus' ),
	array( 'db' => 'cpu', 'dt' => 'cpu' ),
	array( 'db' => 'ram', 'dt' => 'ram' ),
	array( 'db' => 'storage', 'dt' => 'storage' ),
	array( 'db' => 'network', 'dt' => 'network' ),
	array( 'db' => 'added', 'dt' => 'added' ),
	array( 'db' => 'seen', 'dt' => 'seen' ),
	array( 'db' => 'version', 'dt' => 'version' )
);

// Include SQL query processing class
require('ssp.class.php');

// Output data as json format
echo json_encode(
    SSP::simple($_POST, $dbDetails, $table, $primaryKey, $columns)
);
?>