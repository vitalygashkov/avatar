<?php
include('db.php');
if ($_POST['id']) {
    $taskID = $_POST['id'];
    
    $stmt = $pdo->prepare("SELECT `status` FROM `tasks` WHERE `id` = :id");
    $stmt->bindParam(':id', $taskID);
	$stmt->execute();
	$column = $stmt->fetchColumn();
	if ($column == 'Active')
		$status = 'Paused';
	else
		$status = 'Active';
    
    $stmt = $pdo->prepare("UPDATE tasks SET `status` = :status WHERE `id` = :id");
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':id', $taskID);
    $stmt->execute();
}
?>