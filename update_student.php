<?php
session_start();

// Database configuration
$host = 'localhost';
$dbname = 'info_stud';
$username = 'root';
$password = '';

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$student = [];
$errors = [];

// Check if ID parameter exists
if (isset($_GET['id'])) {
    $id = $conn->real_escape_string($_GET['id']);

    // Fetch student data
    $sql = "SELECT * FROM students WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $student = $result->fetch_assoc();
    } else {
        $_SESSION['errors'] = ["No student found with ID: $id"];
        header("Location: curd.php");
        exit;
    }
    $stmt->close();
} else {
    $_SESSION['errors'] = ["No ID provided"];
    header("Location: curd.php");
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and process form data
    $id = $conn->real_escape_string($_POST['id']);
    $fname = $conn->real_escape_string($_POST['fname']);
    $lname = $conn->real_escape_string($_POST['lname']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $gender = $conn->real_escape_string($_POST['gender']);
    $address1 = $conn->real_escape_string($_POST['add1']);
    $address2 = $conn->real_escape_string($_POST['add2']);
    $city = $conn->real_escape_string($_POST['city']);
    $state = $conn->real_escape_string($_POST['state']);
    $country = $conn->real_escape_string($_POST['country']);
    $zip = $conn->real_escape_string($_POST['zip']);
    $qualification = $conn->real_escape_string($_POST['quali']);

    // Validate required fields
    $required = ['fname', 'lname', 'email', 'phone', 'gender', 'add1', 'city', 'state', 'country', 'zip', 'quali'];
    foreach ($required as $field) {
        if (empty($_POST[$field])) {
            $errors[] = ucfirst($field) . " is required";
        }
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }

    // Validate phone number
    if (!preg_match('/^\d{10}$/', $phone)) {
        $errors[] = "Phone must be 10 digits";
    }

    // Check if email already exists (excluding current student)
    $checkEmail = $conn->prepare("SELECT id FROM students WHERE email = ? AND id != ?");
    $checkEmail->bind_param("si", $email, $id);
    $checkEmail->execute();
    $checkEmail->store_result();

    if ($checkEmail->num_rows > 0) {
        $errors[] = "Email already exists";
    }
    $checkEmail->close();

    // If no errors, update record
    if (empty($errors)) {
        $sql = "UPDATE students SET 
                fname = ?, lname = ?, email = ?, phone = ?, gender = ?, 
                address1 = ?, address2 = ?, city = ?, state = ?, 
                country = ?, zip = ?, qualification = ? 
                WHERE id = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "ssssssssssssi",
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
            $id
        );

        if ($stmt->execute()) {
            $_SESSION['message'] = "Student updated successfully!";
            $_SESSION['message_type'] = "success";
            header("Location: curd.php");
            exit;
        } else {
            $_SESSION['errors'] = ["Error updating record: " . $conn->error];
            header("Location: update_student.php?id=$id");
            exit;
        }
        $stmt->close();
    } else {
        // Store errors and form data in session to repopulate form
        $_SESSION['errors'] = $errors;
        $_SESSION['old_input'] = $_POST;
        header("Location: update_student.php?id=$id");
        exit;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Update Student</title>
    <link rel="stylesheet" href="./css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <header class="header" id="header">
        <div class="container">
            <div class="header-txt" id="header-txt">
                <h2>Update Student</h2>
            </div>
        </div>
    </header>

    <section class="form-curd">
        <div class="container">
            <?php
            // Display errors from session
            if (isset($_SESSION['errors'])) {
                foreach ($_SESSION['errors'] as $error) {
                    echo '<div class="alert alert-danger">' . htmlspecialchars($error) . '</div>';
                }
                unset($_SESSION['errors']);
            }

            // Get old input from session if exists
            $oldInput = $_SESSION['old_input'] ?? [];
            unset($_SESSION['old_input']);

            // Use old input if available, otherwise use student data
            $fname = $oldInput['fname'] ?? $student['fname'] ?? '';
            $lname = $oldInput['lname'] ?? $student['lname'] ?? '';
            $lname = $oldInput['email,'] ?? $student['email,'] ?? '';
            $lname = $oldInput['phone,'] ?? $student['phone,'] ?? '';
            $lname = $oldInput['gender,'] ?? $student['gender,'] ?? '';
            $lname = $oldInput['address1,'] ?? $student['address1,'] ?? '';
            $lname = $oldInput['address2,'] ?? $student['address2,'] ?? '';
            $lname = $oldInput['city,'] ?? $student['city,'] ?? '';
            $lname = $oldInput['state,'] ?? $student['state,'] ?? '';
            $lname = $oldInput['country,'] ?? $student['country,'] ?? '';
            $lname = $oldInput['zip,'] ?? $student['zip,'] ?? '';
            $lname = $oldInput['qualification,'] ?? $student['qualification,'] ?? '';
            // $lname = $oldInput['id'] ?? $studen['$id'] ?? '';
            // ... (same for all other fields)
            ?>

            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= htmlspecialchars($student['id'] ?? '') ?>">

                <div class="form-group">
                    <label for="fname">First Name</label>
                    <input type="text" name="fname" class="form-control" id="fname"
                        value="<?= htmlspecialchars($student['fname'] ?? '') ?>" required>
                </div>

                <div class="form-group">
                    <label for="lname">Last Name</label>
                    <input type="text" name="lname" class="form-control" id="lname"
                        value="<?= htmlspecialchars($student['lname'] ?? '') ?>" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" class="form-control" id="email"
                        value="<?= htmlspecialchars($student['email'] ?? '') ?>" required>
                </div>

                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input type="tel" name="phone" class="form-control" id="phone" maxlength="10"
                        value="<?= htmlspecialchars($student['phone'] ?? '') ?>" required>
                </div>

                <div class="form-group">
                    <label>Gender</label>
                    <div class="radio-btn">
                        <span id="Male">
                            <label>
                                <input type="radio" name="gender" value="Male"
                                    <?= ($student['gender'] ?? '') == 'Male' ? 'checked' : '' ?> required> Male
                            </label>
                        </span>
                        <span id="Female">
                            <label>
                                <input type="radio" name="gender" value="Female"
                                    <?= ($student['gender'] ?? '') == 'Female' ? 'checked' : '' ?> required> Female
                            </label>
                        </span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="add1">Address 1</label>
                    <input type="text" name="add1" class="form-control" id="add1"
                        value="<?= htmlspecialchars($student['address1'] ?? '') ?>" required>
                </div>

                <div class="form-group">
                    <label for="add2">Address 2</label>
                    <input type="text" name="add2" class="form-control" id="add2"
                        value="<?= htmlspecialchars($student['address2'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label for="city">City</label>
                    <input type="text" name="city" class="form-control" id="city"
                        value="<?= htmlspecialchars($student['city'] ?? '') ?>" required>
                </div>

                <div class="form-group">
                    <label for="state">State</label>
                    <input type="text" name="state" class="form-control" id="state"
                        value="<?= htmlspecialchars($student['state'] ?? '') ?>" required>
                </div>

                <div class="form-group">
                    <label for="country">Country</label>
                    <select name="country" id="country" class="form-control" required>
                        <option value="" disabled>Select your country</option>
                        <option value="USA" <?= ($student['country'] ?? '') == 'USA' ? 'selected' : '' ?>>United States</option>
                        <option value="India" <?= ($student['country'] ?? '') == 'India' ? 'selected' : '' ?>>India</option>
                        <option value="Canada" <?= ($student['country'] ?? '') == 'Canada' ? 'selected' : '' ?>>Canada</option>
                        <option value="Australia" <?= ($student['country'] ?? '') == 'Australia' ? 'selected' : '' ?>>Australia</option>
                        <option value="Germany" <?= ($student['country'] ?? '') == 'Germany' ? 'selected' : '' ?>>Germany</option>
                        <option value="Japan" <?= ($student['country'] ?? '') == 'Japan' ? 'selected' : '' ?>>Japan</option>
                        <option value="Brazil" <?= ($student['country'] ?? '') == 'Brazil' ? 'selected' : '' ?>>Brazil</option>
                        <option value="France" <?= ($student['country'] ?? '') == 'France' ? 'selected' : '' ?>>France</option>
                        <option value="South Africa" <?= ($student['country'] ?? '') == 'South Africa' ? 'selected' : '' ?>>South Africa</option>
                        <option value="United Kingdom" <?= ($student['country'] ?? '') == 'United Kingdom' ? 'selected' : '' ?>>United Kingdom</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="zip">ZIP Code</label>
                    <input type="text" name="zip" class="form-control" id="zip" maxlength="6"
                        value="<?= htmlspecialchars($student['zip'] ?? '') ?>" required>
                </div>

                <div class="form-group">
                    <label for="quali">Qualification</label>
                    <select name="quali" id="quali" class="form-control" required>
                        <option value="" disabled>Select your qualification</option>
                        <option value="MCA" <?= ($student['qualification'] ?? '') == 'MCA' ? 'selected' : '' ?>>MCA</option>
                        <option value="MBA" <?= ($student['qualification'] ?? '') == 'MBA' ? 'selected' : '' ?>>MBA</option>
                        <option value="BCA" <?= ($student['qualification'] ?? '') == 'BCA' ? 'selected' : '' ?>>BCA</option>
                        <option value="B.Com" <?= ($student['qualification'] ?? '') == 'B.Com' ? 'selected' : '' ?>>B.Com</option>
                        <option value="BBA" <?= ($student['qualification'] ?? '') == 'BBA' ? 'selected' : '' ?>>BBA</option>
                        <option value="BA" <?= ($student['qualification'] ?? '') == 'BA' ? 'selected' : '' ?>>B.A</option>
                        <option value="MA" <?= ($student['qualification'] ?? '') == 'MA' ? 'selected' : '' ?>>M.A</option>
                        <option value="PhD" <?= ($student['qualification'] ?? '') == 'PhD' ? 'selected' : '' ?>>Ph.D</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="profile">Profile Photo</label>
                    <input type="file" name="profile" class="form-control" id="profile">
                    <?php if (!empty($student['profile_photo'])): ?>
                        <div class="current-photo mt-2">
                            <p>Current Photo:</p>
                            <img src="<?= htmlspecialchars($student['profile_photo']) ?>" alt="Profile Photo" style="max-width: 150px; max-height: 150px;">
                        </div>
                    <?php endif; ?>
                </div>


                <div class="sub-btn">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="curd.php" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </section>
</body>

</html>