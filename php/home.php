<?php
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: index.html');
	exit;
}

require_once ('authrole.php');
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>Portal</title>
		<link href="style.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
        <link href="../styles/style.css" rel="stylesheet" type="text/css">
		<link href="../styles/footer.css" rel="stylesheet" type="text/css">
	</head>
	<body class="loggedin">
		<nav class="navtop">
			<div>
				<h1>Portal</h1>
				<a href="account.php"><i class="fas fa-user-circle"></i>Profile</a>
				<a href="uploads.php"><i class="fas fa-upload"></i>Uploads</a>
				<?php if ($role == 'admin') : ?>
                		<a href="admin.php"><i class="fas fa-cog"></i>Admin</a>
				<?php endif; ?>
				<a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
			</div>
		</nav>
		<div class="content">
			<h2>Dashboard</h2>
			<p>Welcome back, <?=$_SESSION['name']?>!</p>
		</div>
		<div class="footer">
			<img src="../images/cnlong.png" alt="Code Nation">
		</div>
	</body>
</html>