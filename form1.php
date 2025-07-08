<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);


$success = '';
$error = '';
$isEdit = false;
$id = '';
$fname = '';
$lname = '';
$email = '';
$phone = '';
$gender = '';
$address1 = '';
$address2 = '';
$city = '';
$state = '';
$country = '';
$zip = '';
$qualification = '';


$conn = new mysqli("localhost", "root", "", "info_stud");
if ($conn->connect_error) {
    echo "<div class='alert alert-danger'>Database connection failed.</div>";
    exit;
}


if (isset($_GET['id'])) {
    $isEdit = true;
    $id = (int)$_GET['id'];

    $stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();

        $fname = $row['fname'];
        $lname = $row['lname'];
        $email = $row['email'];
        $phone = $row['phone'];
        $gender = $row['gender'];
        $address1 = $row['address1'];
        $address2 = $row['address2'];
        $city = $row['city'];
        $state = $row['state'];
        $country = $row['country'];
        $zip = $row['zip'];
        $qualification = $row['qualification'];
        $profile_photo = $row['profile_photo'];
    } else {
        die("Student not found.");
    }
    $stmt->close();
}



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>CRUD Operation</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/style.css">

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

</head>

<body class="bg-light">

    <header class="bg-primary py-3 text-white text-center">
        <h2>CRUD Operation <?= $isEdit ? '(Edit)' : '(Add)' ?></h2>
    </header>

    <section class="py-5">
        <div class="container">

            <div id="ajax-message"></div>

            <?php if (!empty($success)) : ?>
                <div class="alert alert-success"><?= $success ?></div>
            <?php endif; ?>

            <?php if (!empty($error)) : ?>
                <div class="alert alert-danger"><?= $error ?></div>
            <?php endif; ?>

            <form id="stud_form" class="needs-validation fx-width" method="POST" enctype="multipart/form-data" novalidate>
                <input type="hidden" id="form-action" value="<?= $isEdit ? 'update_student.php' : 'insert_student.php' ?>">
                <?php if ($isEdit) : ?>
                    <input type="hidden" name="id" value="<?= $id ?>">
                <?php endif; ?>

                <div class="mb-3 fx-width">
                    <label for="fname" class="form-label">First Name</label>
                    <input type="text" name="fname" class="form-control" id="fname" value="<?= htmlspecialchars($fname) ?>" required>
                    <div class="invalid-feedback">First name is required.</div>
                </div>

                <div class="mb-3 fx-width">
                    <label for="lname" class="form-label">Last Name</label>
                    <input type="text" name="lname" class="form-control" id="lname" value="<?= htmlspecialchars($lname) ?>" required>
                    <div class="invalid-feedback">Last name is required.</div>
                </div>

                <div class="mb-3 fx-width">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" id="email" value="<?= htmlspecialchars($email) ?>" required>
                    <div class="invalid-feedback">Please enter a valid email address.</div>
                </div>

                <div class="mb-3 fx-width">
                    <label for="phone" class="form-label">Phone</label>
                    <input type="tel" name="phone" class="form-control" id="phone" value="<?= htmlspecialchars($phone) ?>" maxlength="10" required>
                    <div class="invalid-feedback">Phone is required.</div>
                </div>

                <div class="mb-3 fx-width">
                    <label class="form-label">Gender</label><br>
                    <div class="form-check form-check-inline">
                        <input type="radio" name="gender" class="form-check-input" id="male" value="Male" <?= ($gender == 'Male') ? 'checked' : '' ?> required>
                        <label class="form-check-label" for="male">Male</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input type="radio" name="gender" class="form-check-input" id="female" value="Female" <?= ($gender == 'Female') ? 'checked' : '' ?> required>
                        <label class="form-check-label" for="female">Female</label>
                    </div>
                </div>

                <div class="mb-3 fx-width">
                    <label for="add1" class="form-label">Address 1</label>
                    <input type="text" name="add1" class="form-control" id="add1" value="<?= htmlspecialchars($address1) ?>" required>
                    <div class="invalid-feedback">Address 1 is required.</div>
                </div>

                <div class="mb-3 fx-width">
                    <label for="add2" class="form-label">Address 2</label>
                    <input type="text" name="add2" class="form-control" id="add2" value="<?= htmlspecialchars($address2) ?>">
                </div>

                <div class="mb-3 fx-width">
                    <label for="city" class="form-label">City</label>
                    <input type="text" name="city" class="form-control" id="city" value="<?= htmlspecialchars($city) ?>" required>
                    <div class="invalid-feedback">City is required.</div>
                </div>

                <div class="mb-3 fx-width">
                    <label for="state" class="form-label">State</label>
                    <input type="text" name="state" class="form-control" id="state" value="<?= htmlspecialchars($state) ?>" required>
                    <div class="invalid-feedback">State is required.</div>
                </div>

                <div class="mb-3 fx-width">
                    <label for="country" class="form-label">Country</label>
                    <select name="country" id="country" class="form-select" required>
                        <option value="" disabled <?= (empty($country)) ? 'selected' : '' ?>>Select country</option>
                        <?php foreach (['USA', 'India', 'Canada', 'Australia'] as $c) : ?>
                            <option value="<?= $c ?>" <?= $country == $c ? 'selected' : '' ?>><?= $c ?></option>
                        <?php endforeach; ?>
                    </select>
                    <div class="invalid-feedback">Country is required.</div>
                </div>

                <div class="mb-3 fx-width">
                    <label for="zip" class="form-label">ZIP</label>
                    <input type="text" name="zip" maxlength="6" class="form-control" id="zip" value="<?= htmlspecialchars($zip) ?>" required>
                    <div class="invalid-feedback">ZIP is required.</div>
                </div>

                <div class="mb-3 fx-width">
                    <label for="quali" class="form-label">Qualification</label>
                    <select name="quali" id="quali" class="form-select" required>
                        <option value="" disabled <?= (empty($qualification)) ? 'selected' : '' ?>>Select qualification</option>
                        <?php foreach (['MCA', 'MBA', 'BCA'] as $q) : ?>
                            <option value="<?= $q ?>" <?= $qualification == $q ? 'selected' : '' ?>><?= $q ?></option>
                        <?php endforeach; ?>
                    </select>
                    <div class="invalid-feedback">Qualification is required.</div>
                </div>

                <div class="mb-3 d-flex w-100 h-auto">
                    <label for="profile" class="form-label w-25">Profile Photo</label>
                    <input type="file" name="profile" class="form-control w-50">
                    <div class="img-show w-25"></div>
                </div>

                <?php if ($isEdit && !empty($profile_photo)) : ?>
                    <div class="mb-3 fx-width">
                        <img src="<?= $profile_photo ?>" alt="Profile" style="width:100px; height:100px;" class="rounded">
                    </div>
                <?php endif; ?>
                <button type="submit" id="confirm-submit" class="btn btn-primary w-100"><?= $isEdit ? 'Update' : 'Submit' ?></button>
            </form>

        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="./js/form.js"></script>
        <script src="./js/porp.js"></script>
        <script>
            $(document).ready(function() {
                $('input[name="profile"]').on('change', function(e) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('.img-show').html('<img src="' + e.target.result + '" class="privew-img" alt="Profile Photo">');
                    }
                    reader.readAsDataURL(this.files[0]);
                });
            });
        </script>

</body>

</html>