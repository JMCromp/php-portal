<?php
define("server", "localhost");
define("user", "root");
define("password", "toor");
define("database", "portal");

$connect = mysqli_connect(server, user, password, database);

if (!$connect)
{
    die("You DB connection has been failed!: " . mysqli_connect_error());
}
$connection = "You have successfully connected to the mysql database";

?>