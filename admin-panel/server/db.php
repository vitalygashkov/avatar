<?php
include ('config.php'); 

$driver = $config->database->driver;
$host = $config->database->host;
$db = $config->database->name;
$user = $config->database->user;
$pass = $config->database->password;
$charset = $config->database->charset;
$dsn = "$driver:host=$host;dbname=$db;charset=$charset";
$opt = [
				PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
				PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_LAZY,
				PDO::ATTR_EMULATE_PREPARES   => false,
		];

$pdo = new PDO($dsn, $user, $pass, $opt);
session_start();
?>