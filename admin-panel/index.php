<?php 
require_once './server/db.php';
$stmt = $pdo->query('SELECT * FROM users');
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<?php if ( !$row ): ?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<?php require_once './templates/head.php'; ?>
	</head>
	<body>
		<?php include_once './pages/register.php'; ?>
		
		<?php include_once './templates/footer.php'; ?>
	</body>
</html>

<?php elseif ( isset($_SESSION['user_login']) ): ?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<?php require_once './templates/head.php'; ?>
	</head>
	<body>
		<div class="container-fluid">
			<div class="content">
				<?php require_once './templates/header.php'; ?>
				<?php require_once './templates/content.php'; ?>
                <?php require_once './pages/createTask.php'; ?>
			</div>
		</div>
		<?php require_once './templates/footer.php'; ?>
	</body>
</html>

<?php else : ?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<?php require_once './templates/head.php'; ?>
	</head>
	<body>
		<?php include_once './pages/login.php'; ?>
		
		<?php include_once './templates/footer.php'; ?>
	</body>
</html>

<?php endif; ?>
