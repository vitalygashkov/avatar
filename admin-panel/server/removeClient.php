<?php
include('db.php');

$stmt = $pdo->prepare("DELETE FROM clients WHERE `id` = :clientid");
$stmt->bindParam(':clientid', $_POST['id']);
$stmt->execute();
?>