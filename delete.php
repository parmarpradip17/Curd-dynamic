<?php


$servername = "localhost";
$username = "root";
$password = "";
$dbname = "info_stud";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}



if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = intval($_POST['id']);

    $sql = "DELETE FROM students WHERE id = $id";
    if ($conn->query($sql) === TRUE) {
        echo "Success";
    } else {
        echo "Error: " . $conn->error;
    }

    $conn->close();
}

?>
<?php ?>


