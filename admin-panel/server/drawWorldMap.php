<?php
	include ('db.php'); 
	$stmtCount = $pdo->prepare('SELECT COUNT(*) FROM clients WHERE `location` = ? ');
	$stmt = $pdo->query('SELECT DISTINCT(`location`) FROM clients')->fetchAll(PDO::FETCH_COLUMN);

    $data = array();
	foreach ($stmt as $key) {
        $stmtCount->execute([$key]);
        $value = $stmtCount->fetchColumn();
        $data[$key] = array( 'value' => $value, 'tooltip' => array( 'content' => "".$key.": ".$value.""));
	}
    echo json_encode($data);
?>