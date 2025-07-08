<?php
session_start();
error_reporting(E_ALL);

// Database connection
$conn = new mysqli("localhost", "root", "", "info_stud");
if ($conn->connect_error) {
    echo "<div class='alert alert-danger'>Database connection failed.</div>";
    exit;
}

// Sanitize form inputs
$fname         = trim($_POST['fname'] ?? '');
$lname         = trim($_POST['lname'] ?? '');
$email         = trim($_POST['email'] ?? '');
$phone         = trim($_POST['phone'] ?? '');
$gender        = $_POST['gender'] ?? '';
$address1      = trim($_POST['add1'] ?? '');
$address2      = trim($_POST['add2'] ?? '');
$city          = trim($_POST['city'] ?? '');
$state         = trim($_POST['state'] ?? '');
$country       = trim($_POST['country'] ?? '');
$zip           = trim($_POST['zip'] ?? '');
$qualification = $_POST['quali'] ?? '';

// Profile photo (optional)
$profile_photo = '';
if (!empty($_FILES['profile']['name']) && $_FILES['profile']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = 'uploads/';
    $fileName  = time() . '_' . basename($_FILES['profile']['name']);
    $uploadPath = $uploadDir . $fileName;

    if (move_uploaded_file($_FILES['profile']['tmp_name'], $uploadPath)) {
        $profile_photo = $fileName;
    }
}

// Store old input in session for repopulating if needed
$_SESSION['old_input'] = $_POST;

// Check for duplicate email
$emailCheckStmt = $conn->prepare("SELECT id FROM students WHERE email = ?");
$emailCheckStmt->bind_param("s", $email);
$emailCheckStmt->execute();
$emailCheckStmt->store_result();

if ($emailCheckStmt->num_rows > 0) {
    echo "<div class='alert alert-danger'>❌ Email already exists. Please use a different email.</div>";
    $emailCheckStmt->close();
    exit;
}
$emailCheckStmt->close();

// Insert new student record
$stmt = $conn->prepare("INSERT INTO students (fname, lname, email, phone, gender, address1, address2, city, state, country, zip, qualification, profile_photo)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
if (!$stmt) {
    echo "<div class='alert alert-danger'>Database prepare failed. Please try again.</div>";
    exit;
}

$stmt->bind_param(
    "sssssssssssss",
    $fname,
    $lname,
    $email,
    $phone,
    $gender,
    $address1,
    $address2,
    $city,
    $state,
    $country,
    $zip,
    $qualification,
    $profile_photo
);

if ($stmt->execute()) {
    echo "<div class='alert alert-success'>✅ Form submitted successfully.</div>";
} else {
    echo "<div class='alert alert-danger'>❌ Failed to add student. Please try again.</div>";
}

$stmt->close();
$conn->close();
?>
<?php ?>