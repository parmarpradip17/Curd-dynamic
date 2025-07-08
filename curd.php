<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "info_stud";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


$sql = "SELECT id, fname, lname, email, phone, gender, address1, address2, city, state, country, zip, qualification , profile_photo FROM students";
$result = $conn->query($sql);
?>

<?php include 'curd_table.php' ?>