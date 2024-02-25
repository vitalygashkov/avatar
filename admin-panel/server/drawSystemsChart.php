<?php
	include ('db.php'); 
	$stmtCount = $pdo->prepare('SELECT COUNT(*) FROM clients WHERE `os` LIKE ? ');
	$stmt = $pdo->query('SELECT DISTINCT(`os`) FROM clients')->fetchAll(PDO::FETCH_COLUMN);

	$result = array();
    $data = array(
        array( 'name' => 'Windows 10', 'count' => 0),
        array( 'name' => 'Windows 8.1', 'count' => 0),
        array( 'name' => 'Windows 8', 'count' => 0),
        array( 'name' => 'Windows 7', 'count' => 0),
        array( 'name' => 'Windows XP', 'count' => 0),
        array( 'name' => 'Windows Server 2008', 'count' => 0),
        array( 'name' => 'Windows Server 2012', 'count' => 0),
        array( 'name' => 'Windows Server 2016', 'count' => 0)
    );

	foreach ($stmt as $key) {
        if (strpos($key, 'Windows 10') !== false) {
            $stmtCount->execute(['%Windows 10%']);
            $value = $stmtCount->fetchColumn();
            $data[0]['name'] = 'Windows 10';
            $data[0]['count'] = $value;
        }
        if (strpos($key, 'Windows 8.1') !== false) {
            $stmtCount->execute(['%Windows 8.1%']);
            $value = $stmtCount->fetchColumn();
            $data[1]['name'] = 'Windows 8.1';
            $data[1]['count'] = $value;
        }
        if (strpos($key, 'Windows 8 ') !== false) {
            $stmtCount->execute(['%Windows 8 %']);
            $value = $stmtCount->fetchColumn();
            $data[2]['name'] = 'Windows 8';
            $data[2]['count'] = $value;
        }
        if (strpos($key, 'Windows 7') !== false) {
            $stmtCount->execute(['%Windows 7%']);
            $value = $stmtCount->fetchColumn();
            $data[3]['name'] = 'Windows 7';
            $data[3]['count'] = $value;
        }
        if (strpos($key, 'Windows XP') !== false) {
            $stmtCount->execute(['%Windows XP%']);
            $value = $stmtCount->fetchColumn();
            $data[4]['name'] = 'Windows XP';
            $data[4]['count'] = $value;
        }  
        if (strpos($key, 'Windows Server 2008') !== false) {
            $stmtCount->execute(['%Windows Server 2008%']);
            $value = $stmtCount->fetchColumn();
            $data[5]['name'] = 'Windows Server 2008';
            $data[5]['count'] = $value;
        }   
        if (strpos($key, 'Windows Server 2012') !== false) {
            $stmtCount->execute(['%Windows Server 2012%']);
            $value = $stmtCount->fetchColumn();
            $data[6]['name'] = 'Windows Server 2012';
            $data[6]['count'] = $value;
        }
        if (strpos($key, 'Windows Server 2016') !== false) {
            $stmtCount->execute(['%Windows Server 2016%']);
            $value = $stmtCount->fetchColumn();
            $data[7]['name'] = 'Windows Server 2016';
            $data[7]['count'] = $value;
        }
	}

    $result["data"] = $data;
    echo json_encode($result);
?>