<?php
// Ensure no whitespace or output before this point
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$conn = new mysqli("localhost", "root", "", "info_stud");
if ($conn->connect_error) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Database connection failed.']);
    exit;
}

// Validate required ID
$id = $_POST['id'] ?? '';
if (empty($id)) {
    http_response_code(400);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'No student ID provided.']);
    $conn->close();
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
$required = [$fname, $email, $phone, $gender, $add1, $city, $state, $country, $zip, $quali];
if (in_array('', $required)) {
    http_response_code(400);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Please fill all required fields.']);
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


// Check for duplicate email
$checkEmail = $conn->prepare("SELECT id FROM students WHERE email = ? AND id != ?");
$checkEmail->bind_param("si", $email, $id);
$checkEmail->execute();
$checkEmail->store_result();
if ($checkEmail->num_rows > 0) {
    http_response_code(409); // Conflict status code
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'This email is already registered.']);
    $checkEmail->close();
    $conn->close();
    exit;
}
$checkEmail->close();

// Handle file upload
$profilePath = '';
if (isset($_FILES['profile']) && $_FILES['profile']['error'] === 0) {
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
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Invalid file type. Only JPG, JPEG, PNG & GIF allowed.']);
        $conn->close();
        exit;
    }

    if (move_uploaded_file($fileTmp, $filePath)) {
        $profilePath = $filePath;
    } else {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Error uploading the image.']);
        $conn->close();
        exit;
    }
} else {
    // Keep existing image
    $stmt = $conn->prepare("SELECT profile_photo FROM students WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $profilePath = $row['profile_photo'];
    }
    $stmt->close();
}

// Update query
$sql = "UPDATE students SET fname=?, lname=?, email=?, phone=?, gender=?, address1=?, address2=?, city=?, state=?, country=?, zip=?, qualification=?, profile_photo=? WHERE id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param(
    "sssssssssssssi",
    $fname,
    $lname,
    $email,
    $phone,
    $gender,
    $add1,
    $add2,
    $city,
    $state,
    $country,
    $zip,
    $quali,
    $profilePath,
    $id
);

// Prepare response
$response = ['success' => false, 'message' => ''];

if ($stmt->execute()) {
    $response['success'] = true;
    $response['message'] = "Student updated successfully!";
    $response['redirect'] = "curd.php";
} else {
    $response['message'] = "Error updating student: " . $stmt->error;
}

$stmt->close();
$conn->close();

// Send final response
header('Content-Type: application/json');
echo json_encode($response);
?>
<?php ?>