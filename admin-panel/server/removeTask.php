<?php
include('db.php');

$stmt = $pdo->prepare("DELETE FROM tasks WHERE `id` = :taskname");
$stmt->bindParam(':taskname', $_POST['id']);
$stmt->execute();
?>