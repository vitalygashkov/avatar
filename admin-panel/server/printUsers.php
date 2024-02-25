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
$table = 'users';

// Table's primary key
$primaryKey = 'id';

// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database. 
// The `dt` parameter represents the DataTables column identifier.
$columns = array(
    array( 'db' => 'id', 'dt' => 'id' ),
	array( 'db' => 'login', 'dt' => 'login' ),
    array( 'db' => 'role',  'dt' => 'role' )
);

// Include SQL query processing class
require('ssp.class.php');

// Output data as json format
echo json_encode(
    SSP::simple($_POST, $dbDetails, $table, $primaryKey, $columns)
);
?>