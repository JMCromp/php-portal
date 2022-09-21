<?php
// We need to use sessions, so you should always start sessions using the below code.
session_start();
// If the user is not logged in redirect to the login page...
if (!isset($_SESSION['loggedin'])) {
	header('Location: ../index.html');
	exit;
}
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = 'toor';
$DATABASE_NAME = 'portal';
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
if (mysqli_connect_errno()) {
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}
// We don't have the password or email info stored in sessions so instead we can get the results from the database.
$stmt = $con->prepare('SELECT password, email, role FROM accounts WHERE id = ?');
// In this case we can use the account ID to get the account info.
$stmt->bind_param('i', $_SESSION['id']);
$stmt->execute();
$stmt->bind_result($password, $email, $role);
$stmt->fetch();
$stmt->close();

require_once ('authrole.php');
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>My Account</title>
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
			<h2>My Account</h2>
			<div>
				<p>Your account details are below:</p>
				<table>
					<tr>
						<td>Username:</td>
						<td><?=$_SESSION['name']?></td>
					</tr>
					<tr>
						<td>Password:</td>
						<td><?=$password?></td>
					</tr>
					<tr>
						<td>Email:</td>
						<td><?=$email?></td>
					</tr>
                    <tr>
						<td>Role:</td>
						<?php if ($role == 'admin') : ?>
							<td><?=$role?></td>
						<?php else : ?>
							<td>none</td>
						<?php endif; ?>
					</tr>
				</table>
			</div>
		</div>
		<div class="footer">
			<img src="../images/cnlong.png" alt="Code Nation">
		</div>
	</body>
</html>