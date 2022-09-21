<?php
    session_start();
    $DATABASE_HOST = 'localhost';
    $DATABASE_USER = 'root';
    $DATABASE_PASS = 'toor';
    $DATABASE_NAME = 'portal';
    $con = mysqli_connect($DATABASE_HOST, $DATABASE_USER, $DATABASE_PASS, $DATABASE_NAME);
    if (mysqli_connect_errno()) {
        exit('Failed to connect to MySQL: ' . mysqli_connect_error());
    }
    // We don't have the password or email info stored in sessions so instead we can get the results from the database.
    $stmt = $con->prepare('SELECT role FROM accounts WHERE id = ?');
    // In this case we can use the account ID to get the account info.
    $stmt->bind_param('i', $_SESSION['id']);
    $stmt->execute();
    $stmt->bind_result($role);
    $stmt->fetch();
    $stmt->close();

    if(empty($role) || $role !== 'admin') {
        // Block user access
        // Queue redirect
        header ("Refresh: 3;URL='account.php'");
        // Kill further actions and display error
        die("You do not have permission to view this page, redirecting in 3 seconds...");
    }
?>

<!-- <!DOCTYPE html>
<body>
   <head>
      <title>Admin</title>
      <link href="../styles/style.css" rel="stylesheet" type="text/css">
   </head>
   <div class="admin">
        <form action="admin.php" method="post" align="center">
        <input id="fetchDB" type="submit" name="fetch" value="FETCH DATA" />
    </div>
</form> -->

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
                <a href="admin.php"><i class="fas fa-user-circle"></i>Admin</a>
				<a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>
			</div>
		</nav>

        <div class="content">
            <h2>Admin Panel</h2>
            <div class="admin">
                <form action="admin.php" method="post" align="center">

                <?php
                    // Temp logic for admin DB display
                    // THIS CHUNK HANDLES USER OUTPUT
                    // Fetch connection details from database.php file using require_once(); function
                    require_once ('dbconnect.php');
                    // Check successful db connection, display message
                    // echo $connection;

                    if (isset($_POST['fetch']))
                    {
                        // Mysql_query() performs a single query to the currently active database on the server that is associated with the specified link identifier
                        $response = mysqli_query($connect, 'SELECT * FROM accounts');
                        echo "<table border='2' align='center'>
                    <H2 align='center'> All Users </h2>
                    <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Password</th>
                    <th>Email</th>
                    <th>Role</th>
                    </tr>";
                        while ($fetch = mysqli_fetch_array($response))
                        {
                            echo "<tr>";
                            echo "<td>" . $fetch['id'] . "</td>";
                            echo "<td>" . $fetch['username'] . "</td>";
                            echo "<td>" . $fetch['password'] . "</td>";
                            echo "<td>" . $fetch['email'] . "</td>";
                            echo "<td>" . $fetch['role'] . "</td>";
                            echo "</tr>";
                        }
                        echo "</table>";

                        mysqli_close($connect);
                    }
                ?>

                <?php
                    // Temp logic for admin DB display
                    // THIS CHUNK HANDLES COMMENT OUTPUT
                    // Fetch connection details from database.php file using require_once(); function
                    require_once ('dbconnect.php');
                    // Check successful db connection, display message
                    // echo $connection;

                    if (isset($_POST['fetchCom']))
                    {
                        // Mysql_query() performs a single query to the currently active database on the server that is associated with the specified link identifier
                        $responseCom = mysqli_query($connect, 'SELECT * FROM comments');
                        echo "<table border='2' align='center'>
                    <H2 align='center'> All Comments </h2>
                    <tr>
                    <th>ID</th>
                    <th>Page ID</th>
                    <th>Parent ID</th>
                    <th>Posted By</th>
                    <th>Comment</th>
                    <th>Date</th>
                    </tr>";
                        while ($fetchCom = mysqli_fetch_array($responseCom))
                        {
                            echo "<tr>";
                            echo "<td>" . $fetchCom['id'] . "</td>";
                            echo "<td>" . $fetchCom['page_id'] . "</td>";
                            echo "<td>" . $fetchCom['parent_id'] . "</td>";
                            echo "<td>" . $fetchCom['name'] . "</td>";
                            echo "<td>" . $fetchCom['content'] . "</td>";
                            echo "<td>" . $fetchCom['submit_date'] . "</td>";
                            echo "</tr>";
                        }
                        echo "</table>";

                        mysqli_close($connect);
                    }
                ?>

                <input id="fetchDB" type="submit" name="fetch" value="FETCH USERS" />
                <input id="fetchDB" type="submit" name="fetchCom" value="FETCH COMMENTS" />
            </div>
        </div>
        <div class="footer">
			<img src="../images/cnlong.png" alt="Code Nation">
		</div>
	</body>
</html>