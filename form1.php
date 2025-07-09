<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$conn = new mysqli("localhost", "root", "", "stud_resume");
if ($conn->connect_error) {
    die("<div class='alert alert-danger'>Database connection failed.</div>");
}

// Fetch qualifications
$qualifications = [];
$res = $conn->query("SELECT qualification_name FROM qualifications");
while ($row = $res->fetch_assoc()) {
    $qualifications[] = $row['qualification_name'];
}

// Check if only one qualification exists and it is "OTHERS"
if (count($qualifications) === 1 && strtoupper($qualifications[0]) === 'OTHERS') {
    echo '
    <div class="col-md-6">
        <label class="form-label">Add New Qualification</label>
        <input type="text" name="quali" class="form-control" placeholder="Enter your qualification" required>
    </div>';
    exit;
}


// Fetch hobbies
$hobbiesList = [];
$res = $conn->query("SELECT hobby_name FROM hobbies");
while ($row = $res->fetch_assoc()) {
    $hobbiesList[] = $row['hobby_name'];
}

// Check if only one hobbies exists and it is "OTHERS"
if (count($hobbiesList) === 1 && strtoupper($hobbiesList[0]) === 'OTHERS') {
    echo '
    <div class="col-md-6">
        <label class="form-label">Add New hobby</label>
        <input type="text" name="quali" class="form-control" placeholder="Enter your qualification" required>
    </div>';
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Student Resume Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/style.css">

</head>

<body class="bg-light">
    <div class="container py-5">
        <h2 class="mb-4">Student Resume Form</h2>

        <form method="POST" action="insert_student.php" enctype="multipart/form-data" novalidate>
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">First Name</label>
                    <input type="text" name="fname" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Last Name</label>
                    <input type="text" name="lname" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Phone</label>
                    <input type="tel" name="phone" class="form-control" maxlength="10" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label d-block">Gender</label>
                    <div class="form-check form-check-inline">
                        <input type="radio" name="gender" value="Male" class="form-check-input" required>
                        <label class="form-check-label">Male</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input type="radio" name="gender" value="Female" class="form-check-input" required>
                        <label class="form-check-label">Female</label>
                    </div>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Resume</label>
                    <input type="file" name="profile" class="form-control">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Address 1</label>
                    <input type="text" name="add1" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Address 2</label>
                    <input type="text" name="add2" class="form-control">
                </div>
                <div class="col-md-4">
                    <label class="form-label">City</label>
                    <input type="text" name="city" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">State</label>
                    <input type="text" name="state" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Country</label>
                    <select name="country" class="form-select" required>
                        <option value="" disabled selected>Select country</option>
                        <?php foreach (['USA', 'India', 'Canada', 'Australia'] as $c): ?>
                            <option value="<?= $c ?>"><?= $c ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">ZIP</label>
                    <input type="text" name="zip" maxlength="6" class="form-control" required>
                </div>

                <!-- Qualification with autocomplete -->
                <div class="col-md-8 position-relative">
                    <label class="form-label">Qualification</label>
                    <input type="text" name="quali" id="quali_input" class="form-control" required>
                    <div id="quali_dropdown" class="autocomplete-dropdown"></div>
                    <div id="quali_add_container" class="mt-2" style="display: none;">
                        <input type="text" id="quali_add_input" class="form-control mb-2" placeholder="Add new qualification">
                        <button type="button" id="quali_add_btn" class="btn btn-sm btn-success">Add Qualification</button>
                    </div>
                </div>
                <?php if (count($qualifications) === 1 && strtoupper($qualifications[0]) === 'OTHERS'): ?>
                    <div class="col-md-6">
                        <label class="form-label">Add New Qualification</label>
                        <input type="text" name="quali" class="form-control" placeholder="Enter your qualification" required>
                    </div>
                <?php endif; ?>
                <div class="col-md-4">
                    <label class="form-label">Percentage</label>
                    <input type="text" name="percentage" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Passing Year</label>
                    <input type="text" name="passing_year" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">University</label>
                    <input type="text" name="university" class="form-control" required>
                </div>

                <!-- Hobbies -->
                <!-- Hobbies with autocomplete and optional add -->
                <div class="col-md-12 position-relative">
                    <label class="form-label">Hobbies</label>
                    <input type="text" id="hobbies_input" class="form-control" placeholder="Type to search hobbies...">
                    <div id="hobbies_dropdown" class="autocomplete-dropdown"></div>
                    <div id="selected_hobbies" class="multi-selected mt-2"></div>
                    <input type="hidden" name="hobbies_final" id="hobbies_final">
                    <div id="hobby_add_container" class="mt-2" style="display: none;">
                        <input type="text" id="hobby_add_input" class="form-control mb-2" placeholder="Add new hobby">
                        <button type="button" id="hobby_add_btn" class="btn btn-sm btn-success">Add Hobby</button>
                    </div>
                </div>
                <?php if (count($hobbiesList) === 1 && strtoupper($hobbiesList[0]) === 'OTHERS'): ?>
                    <div class="col-md-6">
                        <label class="form-label">Add New Hobby</label>
                        <input type="text" name="hobby" class="form-control" placeholder="Enter your hobby" required>
                    </div>
                <?php endif; ?>

            </div>
            <button type="submit" class="btn btn-primary mt-4 w-100">Submit</button>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        const qualifications = <?= json_encode($qualifications) ?>;
        const hobbies = <?= json_encode($hobbiesList) ?>;
    </script>
    <script src="js/form.js"></script>
</body>

</html>