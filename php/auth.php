<?php
session_start();

$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'root';
$DATABASE_PASS = 'toor';
$DATABASE_NAME = 'portal';
$con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);

if ( mysqli_connect_errno() ) {
	exit('Failed to connect to MySQL: ' . mysqli_connect_error());
}

if ( !isset($_POST['username'], $_POST['password']) ) {
	exit('Please fill both the username and password fields!');
}

if ($stmt = $con->prepare('SELECT id, password, role FROM accounts WHERE username = ?')) {
	$stmt->bind_param('s', $_POST['username']);
	$stmt->execute();
	$stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $password, $role);
        $stmt->fetch();

        //// bodgy workaround
        $postpass = $_POST['password'];
        $post256 = hash('sha256', $postpass);

        if ($post256 == $password) {
        // if (password_verify($_POST['password'], $password)) {
            session_regenerate_id();
            $_SESSION['loggedin'] = TRUE;
            $_SESSION['name'] = $_POST['username'];
            $_SESSION['id'] = $id;
            $_SESSION['role'] = $role;
            header('Location: home.php');
        } else {
            echo 'Incorrect username and/or password. Redirecting in 3 seconds...';
            header ("Refresh: 3;URL='../index.html'"); 
        }
    } else {
        echo 'Incorrect username and/or password.';
        header ("Refresh: 3;URL='../index.html'"); 
    }


	$stmt->close();
}
?>