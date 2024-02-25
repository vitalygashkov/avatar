<?php
	include ('db.php'); 
	$stmtCount = $pdo->prepare('SELECT COUNT(*) FROM clients WHERE `os` LIKE ? ');
	$stmt = $pdo->query('SELECT DISTINCT(`os`) FROM clients')->fetchAll(PDO::FETCH_COLUMN);

	$result = array();
    $data = array(
        array('Windows 10', 0),
        array('Windows 8.1', 0),
        array('Windows 8', 0),
        array('Windows 7', 0),
        array('Windows XP', 0),
        array('Windows Server 2008', 0),
        array('Windows Server 2012', 0),
        array('Windows Server 2016', 0)
    );

	foreach ($stmt as $key) {
        if (strpos($key, 'Windows 10') !== false) {
            $stmtCount->execute(['%Windows 10%']);
            $value = $stmtCount->fetchColumn();
            $data[0][1] = $value;
        }
        if (strpos($key, 'Windows 8.1') !== false) {
            $stmtCount->execute(['%Windows 8.1%']);
            $value = $stmtCount->fetchColumn();
            $data[1][1] = $value;
        }
        if (strpos($key, 'Windows 8 ') !== false) {
            $stmtCount->execute(['%Windows 8 %']);
            $value = $stmtCount->fetchColumn();
            $data[2][1] = $value;
        }
        if (strpos($key, 'Windows 7') !== false) {
            $stmtCount->execute(['%Windows 7%']);
            $value = $stmtCount->fetchColumn();
            $data[3][1] = $value;
        }
        if (strpos($key, 'Windows XP') !== false) {
            $stmtCount->execute(['%Windows XP%']);
            $value = $stmtCount->fetchColumn();
            $data[4][1] = $value;
        }  
        if (strpos($key, 'Windows Server 2008') !== false) {
            $stmtCount->execute(['%Windows Server 2008%']);
            $value = $stmtCount->fetchColumn();
            $data[5][1] = $value;
        }   
        if (strpos($key, 'Windows Server 2012') !== false) {
            $stmtCount->execute(['%Windows Server 2012%']);
            $value = $stmtCount->fetchColumn();
            $data[6][1] = $value;
        }
        if (strpos($key, 'Windows Server 2016') !== false) {
            $stmtCount->execute(['%Windows Server 2016%']);
            $value = $stmtCount->fetchColumn();
            $data[7][1] = $value;
        }
	}

    $result["data"] = $data;
    echo json_encode($result);
?>