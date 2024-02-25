<?php
require_once 'db.php';

$currentPassword = trim( $_POST['currentPassword'] );
$newPassword = trim( $_POST['newPassword'] );

if( !empty($currentPassword) && !empty($newPassword) ){

    $sql = 'SELECT `login`, `password` FROM `users` WHERE `login` = :login';
    $params = [':login' => $_SESSION['user_login']];
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $user = $stmt->fetch(PDO::FETCH_OBJ);
    
    if( password_verify($currentPassword, $user->password) ) {
        $newPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        $sql = 'UPDATE users SET password=:password WHERE login=:login';
        $params = [ 'login' => $_SESSION['user_login'], ':password' => $newPassword];

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        header('Location: ../');
    }
}else{
  echo 'error';
}

?>