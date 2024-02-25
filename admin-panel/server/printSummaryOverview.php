<?php
include('db.php');

$stmt = $pdo->query("SELECT `added`,`seen` FROM clients");
$stmt->execute();
date_default_timezone_set('Europe/Moscow');
$currentDate = date('Y-m-d H:i:s');

$data = array(
    'online'      => 0,
    'onlineToday' => 0,
    'onlineWeek'  => 0,
    'onlineMonth' => 0,
    'newToday'    => 0,
    'newWeek'     => 0,
    'newMonth'    => 0,
    'dead'        => 0
);


foreach ($stmt as $row) {
//  Time difference in seconds
    $diffSeconds = strtotime($currentDate) - strtotime($row['seen']);
//  Time difference in minutes
    $diffMinutes = intval($diffSeconds) / 60;
//  Time difference in hours
    $diffHours = intval($diffMinutes) / 60;
//  Time difference in days
    $diffDays = intval($diffHours) / 24;

    if (intval($diffMinutes) < 5)
        $data['online'] += 1;
    if (intval($diffHours) < 24)
        $data['onlineToday'] += 1;
    if (intval($diffDays) < 7)
        $data['onlineWeek'] += 1;
    if (intval($diffDays) < 31)
        $data['onlineMonth'] += 1;
    if (intval($diffDays) > 4)
        $data['dead'] += 1;
    
//  Time difference in seconds
    $diffSeconds = strtotime($currentDate) - strtotime($row['added']);
//  Time difference in minutes
    $diffMinutes = intval($diffSeconds) / 60;
//  Time difference in hours
    $diffHours = intval($diffMinutes) / 60;
//  Time difference in days
    $diffDays = intval($diffHours) / 24;

    if (intval($diffHours) < 24)
        $data['newToday'] += 1;
    if (intval($diffDays) < 7)
        $data['newWeek'] += 1;
    if (intval($diffDays) < 31)
        $data['newMonth'] += 1;
}

echo json_encode($data);
?>