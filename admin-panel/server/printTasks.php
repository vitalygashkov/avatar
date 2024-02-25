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
$table = 'tasks';

// Table's primary key
$primaryKey = 'ID';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database. 
// The `dt` parameter represents the DataTables column identifier.
$columns = array(
    array( 'db' => 'id', 'dt' => 'id' ),
    array( 'db' => 'title',  'dt' => 'title' ),
    array( 'db' => 'type', 'dt' => 'type' ),
    array( 'db' => 'content', 'dt' => 'content' ),
	array( 'db' => 'target', 'dt' => 'target' ),
	array( 'db' => 'trigger', 'dt' => 'trigger' ),
	
	array( 'db' => 'limit', 'dt' => 'limit' ),
	array( 'db' => 'client_id', 'dt' => 'client_id' ),
	array( 'db' => 'location', 'dt' => 'location' ),
	array( 'db' => 'os', 'dt' => 'os' ),
	array( 'db' => 'role', 'dt' => 'role' ),
	array( 'db' => 'antivirus', 'dt' => 'antivirus' ),
	array( 'db' => 'cpu', 'dt' => 'cpu' ),
	array( 'db' => 'ram', 'dt' => 'ram' ),
	array( 'db' => 'storage_type', 'dt' => 'storage_type' ),
	array( 'db' => 'storage', 'dt' => 'storage' ),
	array( 'db' => 'network_type', 'dt' => 'network_type' ),
	array( 'db' => 'network', 'dt' => 'network' ),
	
	array( 'db' => 'status', 'dt' => 'status' ),
	array( 'db' => 'total_done', 'dt' => 'total_done' ),
	array( 'db' => 'total_failed', 'dt' => 'total_failed' ),
	array( 'db' => 'added', 'dt' => 'added' )
);

// Include SQL query processing class
require('ssp.class.php');

// Output data as json format
echo json_encode(
    SSP::simple($_POST, $dbDetails, $table, $primaryKey, $columns)
);
?>