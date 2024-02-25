<?php

include('db.php');

date_default_timezone_set('Europe/Moscow');
$currentDate = date('Y-m-d H:i:s');
$currentDate = strtotime($currentDate);

$clients = $pdo->query("SELECT * FROM clients");

foreach ($clients as $client) {
    $seenDate = strtotime($client['seen']);
    $diffSec = $currentDate - $seenDate;
    $diffDays = $diffSec / 86400;
    
    if ($diffDays > 4)
        $pdo->query("DELETE FROM `clients` WHERE id='".$client['id']."'");
}

?>