<?php
include('db.php');

$stmt = $pdo->prepare("SELECT * FROM tasks WHERE `id` = :taskname");
$stmt->bindParam(':taskname', $_POST['id']);
$stmt->execute();

$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($results);
?>