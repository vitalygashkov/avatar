<?php
	include ('db.php'); 
	$stmtCount = $pdo->prepare('SELECT COUNT(*) FROM clients WHERE `role` = ? ');
	$stmt = $pdo->query('SELECT DISTINCT(`role`) FROM clients')->fetchAll(PDO::FETCH_COLUMN);

	$result = array();
    $data = array();

	foreach ($stmt as $key) {
        $stmtCount->execute([$key]);
        $value = $stmtCount->fetchColumn();
        array_push($data, array( 'name' => $key, 'count' => $value));
	}

    $result["data"] = $data;
    echo json_encode($result);
?>