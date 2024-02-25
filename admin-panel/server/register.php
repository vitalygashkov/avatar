<?php
require_once 'db.php';

$login = trim( $_POST['login'] );
$pwd = trim( $_POST['pwd'] );

if( !empty($login) && !empty($pwd) ){

  $pwd = password_hash($pwd, PASSWORD_DEFAULT);

  $sql = 'INSERT INTO users(login, password, role) VALUES(:login, :pwd, :role)';
  $params = [ 'login' => $login, ':pwd' => $pwd , ':role' => 'Administrator'];

  $stmt = $pdo->prepare($sql);
  $stmt->execute($params);
    
  $_SESSION['user_login'] = $login;
  header('Location: ../');

}else{
  echo 'error';
}

?>
