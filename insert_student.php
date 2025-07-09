<?php
session_start();
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = new mysqli("localhost", "root", "", "stud_resume");
if ($conn->connect_error) {
    echo json_encode(['status' => 'error', 'message' => 'Database connection failed.']);
    exit;
}

// Helper function to get or insert qualification/hobby
function getOrInsert($conn, $table, $column, $value)
{
    $stmt = $conn->prepare("SELECT id FROM $table WHERE $column = ?");
    $stmt->bind_param("s", $value);
    $stmt->execute();
    $stmt->bind_result($existing_id);
    if ($stmt->fetch()) {
        $stmt->close();
        return $existing_id;
    }
    $stmt->close();

    // Insert new value
    $stmt = $conn->prepare("INSERT INTO $table ($column) VALUES (?)");
    $stmt->bind_param("s", $value);
    $stmt->execute();
    $new_id = $stmt->insert_id;
    $stmt->close();
    return $new_id;
}

// Collect form data safely
$fname = $_POST['fname'] ?? '';
$lname = $_POST['lname'] ?? '';
$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';
$gender = $_POST['gender'] ?? '';
$add1 = $_POST['add1'] ?? '';
$add2 = $_POST['add2'] ?? '';
$city = $_POST['city'] ?? '';
$state = $_POST['state'] ?? '';
$country = $_POST['country'] ?? '';
$zip = $_POST['zip'] ?? '';
$quali = $_POST['quali'] ?? '';
$percentage = $_POST['percentage'] ?? 0;
$passing_year = $_POST['passing_year'] ?? date('Y');
$university = $_POST['university'] ?? '';
$hobbies = isset($_POST['hobbies_final']) ? explode(',', $_POST['hobbies_final']) : [];

// Handle file upload
$filename = '';
if (isset($_FILES['profile']) && $_FILES['profile']['error'] === 0) {
    $uploadDir = "uploads/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    $safeName = basename($_FILES["profile"]["name"]);
    $filename = $uploadDir . uniqid() . "_" . $safeName;
    move_uploaded_file($_FILES["profile"]["tmp_name"], $filename);
}

// 1. Insert into stud_basic_info
$stmt = $conn->prepare("INSERT INTO stud_basic_info (fname, lname, email, phone) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $fname, $lname, $email, $phone);
if (!$stmt->execute()) {
    echo json_encode(['status' => 'error', 'message' => 'Email already exists or basic info insert failed.']);
    exit;
}
$student_id = $stmt->insert_id;
$stmt->close();

// 2. Insert into stud_gen_info
$stmt = $conn->prepare("INSERT INTO stud_gen_info (student_id, gender, address1, address2, city, state, country, zip, photo)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("issssssss", $student_id, $gender, $add1, $add2, $city, $state, $country, $zip, $filename);
$stmt->execute();
$stmt->close();

// 3. Get or insert qualification
$qualification_id = getOrInsert($conn, 'qualifications', 'qualification_name', $quali);

// 4. Insert into stud_academic_info
$stmt = $conn->prepare("INSERT INTO stud_academic_info (student_id, qualification_id, percentage, passing_year, university)
                        VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("iidsi", $student_id, $qualification_id, $percentage, $passing_year, $university);
$stmt->execute();
$stmt->close();

// 5. Handle hobbies
foreach ($hobbies as $hobby) {
    $hobby = trim($hobby);
    if ($hobby === '') continue;
    $hobby_id = getOrInsert($conn, 'hobbies', 'hobby_name', $hobby);

    $stmt = $conn->prepare("INSERT INTO stud_hobbies (student_id, hobby_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $student_id, $hobby_id);
    $stmt->execute();
    $stmt->close();
}

// ✅ Done
echo json_encode(['status' => 'success', 'message' => 'Student data inserted successfully.']);
?>
<?php ?>