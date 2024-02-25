<?php
	include ('db.php'); 
	$stmtCount = $pdo->prepare('SELECT COUNT(*) FROM clients WHERE `antivirus` = ? ');
	$stmt = $pdo->query('SELECT DISTINCT(`antivirus`) FROM clients')->fetchAll(PDO::FETCH_COLUMN);

	$result = array();
    $data = array();

	foreach ($stmt as $key) {
        $stmtCount->execute([$key]);
        $value = $stmtCount->fetchColumn();
        array_push($data, array($key, $value));
	}

    $result["data"] = $data;
    echo json_encode($result);
?>