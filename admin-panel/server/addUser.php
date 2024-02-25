<?php
include('db.php');

$login = $_POST['username'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$role = $_POST['role'];

$stmt = $pdo->prepare("INSERT INTO users (login, password, role) VALUES (:username, :password, :role)");
$stmt->bindParam(':username', $login);
$stmt->bindParam(':password', $password);
$stmt->bindParam(':role', $role);
$stmt->execute();

?>