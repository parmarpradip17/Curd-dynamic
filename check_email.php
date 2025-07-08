<?php
$conn = new mysqli("localhost", "root", "", "info_stud");
if ($conn->connect_error) {
    echo "error";
    exit;
}

$email = $_POST['email'];

$stmt = $conn->prepare("SELECT id FROM students WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo "exists";
} else {
    echo "available";
}

$stmt->close();
$conn->close();
?>
<?php ?>