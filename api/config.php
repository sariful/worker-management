<?php
// $servername = "localhost";
// $username = "root";
// $password = "pass";
// $dbname = "worker_management";
$config  = parse_ini_file(dirname(dirname(dirname(__dir__))). '/config.ini');

$host = $config['host'];
$username = $config['username'];
$password = $config['password'];
$dbname = $config['dbname'];
$conn=new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}