<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

// Database connection
$conn = new mysqli("localhost", "root", "", "info_stud");
if ($conn->connect_error) {
    $response['message'] = "Database connection failed.";
    echo json_encode($response);
    exit;
}

// Sanitize inputs
function sanitize($data)
{
    return htmlspecialchars(trim($data));
}

$fname = sanitize($_POST['fname'] ?? '');
$lname = sanitize($_POST['lname'] ?? '');
$email = sanitize($_POST['email'] ?? '');
$phone = sanitize($_POST['phone'] ?? '');
$gender = sanitize($_POST['gender'] ?? '');
$add1 = sanitize($_POST['add1'] ?? '');
$add2 = sanitize($_POST['add2'] ?? '');
$city = sanitize($_POST['city'] ?? '');
$state = sanitize($_POST['state'] ?? '');
$country = sanitize($_POST['country'] ?? '');
$zip = sanitize($_POST['zip'] ?? '');
$quali = sanitize($_POST['quali'] ?? '');

// Validate required fields
$required = [$fname, $lname, $email, $phone, $gender, $add1, $city, $state, $country, $zip, $quali];
if (in_array('', $required)) {
    $response['message'] = "Please fill all required fields.";
    echo json_encode($response);
    $conn->close();
    exit;
}

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Invalid email format.']);
    $conn->close();
    exit;
}

// Check MX record of email domain
list(, $domain) = explode('@', $email);
if (!checkdnsrr($domain, 'MX')) {
    http_response_code(400);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Email domain does not exist (no MX record found).']);
    $conn->close();
    exit;
}


// Check email duplication
$stmt = $conn->prepare("SELECT id FROM students WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    $response['message'] = "Email already exists. Please use a different email.";
    echo json_encode($response);
    $stmt->close();
    $conn->close();
    exit;
}
$stmt->close();

// Handle file upload
$profilePath = '';
if (isset($_FILES['profile']) && $_FILES['profile']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = 'uploads/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $fileTmp = $_FILES['profile']['tmp_name'];
    $fileName = basename($_FILES['profile']['name']);
    $filePath = $uploadDir . time() . '_' . $fileName;
    $fileType = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

    if (!in_array($fileType, $allowedTypes)) {
        $response['message'] = "Invalid file type. Only JPG, JPEG, PNG & GIF allowed.";
        echo json_encode($response);
        $conn->close();
        exit;
    }

    if (move_uploaded_file($fileTmp, $filePath)) {
        $profilePath = $filePath;
    } else {
        $response['message'] = "Error uploading the image.";
        echo json_encode($response);
        $conn->close();
        exit;
    }
}

// Insert data
$sql = "INSERT INTO students (fname, lname, email, phone, gender, address1, address2, city, state, country, zip, qualification, profile_photo)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssssssssss", $fname, $lname, $email, $phone, $gender, $add1, $add2, $city, $state, $country, $zip, $quali, $profilePath);

if ($stmt->execute()) {
    $response['success'] = true;
    $response['message'] = "Student added successfully!";
    $response['redirect'] = "curd.php";
} else {
    $response['message'] = "Error adding student: " . $stmt->error;
}

$stmt->close();
$conn->close();
echo json_encode($response);
?>
<?php ?>




