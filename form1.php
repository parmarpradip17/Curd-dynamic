<?php

$isEdit = false;
$errors = $_SESSION['errors'] ?? [];
$oldInput = $_SESSION['old_input'] ?? [];
unset($_SESSION['errors']);
unset($_SESSION['old_input']);

// Set default values or values from old input
$fname = $oldInput['fname'] ?? '';
$lname = $oldInput['lname'] ?? '';
$email = $oldInput['email'] ?? '';
$phone = $oldInput['phone'] ?? '';
$gender = $oldInput['gender'] ?? '';
$address1 = $oldInput['add1'] ?? '';
$address2 = $oldInput['add2'] ?? '';
$city = $oldInput['city'] ?? '';
$state = $oldInput['state'] ?? '';
$country = $oldInput['country'] ?? '';
$zip = $oldInput['zip'] ?? '';
$qualification = $oldInput['quali'] ?? '';
$profile_photo = $oldInput['profile_photo'] ?? '';



if (isset($_SESSION['error'])) {
    echo "<div class='alert alert-danger'>{$_SESSION['error']}</div>";
    unset($_SESSION['error']);
}

if (isset($_SESSION['success'])) {
    echo "<div class='alert alert-success'>{$_SESSION['success']}</div>";
    unset($_SESSION['success']);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>CURD Operation</title>
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>
    <header class="header" id="header">
        <div class="container">
            <div class="header-txt" id="header-txt">
                <h2>CURD Operation</h2>
            </div>
        </div>
    </header>
    <div id="form-response"></div>

    <section class="form-curd">
        <div class="container">
            <?php if (!empty($errors)): ?>
                <div class="error-messages">
                    <?php foreach ($errors as $error): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <div class="form">
                <form id="stud_form" method="POST" role="form" enctype="multipart/form-data" novalidate>
                    <div class="form-group">
                        <label for="fname">First Name</label>
                        <input type="text" name="fname" class="form-control validation" id="fname" value="<?= htmlspecialchars($fname) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="lname">Last Name</label>
                        <input type="text" name="lname" class="form-control validation" id="lname" value="<?= htmlspecialchars($lname) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" class="form-control validation" id="email" value="<?= htmlspecialchars($email) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone</label>
                        <input type="tel" name="phone" class="form-control validation" maxlength="10" id="phone" value="<?= htmlspecialchars($phone) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="gender">Gender</label>
                        <div class="radio-btn">
                            <span id="Male">
                                <label>
                                    <input type="radio" name="gender" class="form-control validation" id="male" value="Male" <?= ($gender == 'Male') ? 'checked' : '' ?> required>
                                    Male
                                </label>
                            </span>
                            <span id="Female">
                                <label>
                                    <input type="radio" name="gender" class="form-control validation" id="female" value="Female" <?= ($gender == 'Female') ? 'checked' : '' ?> required>
                                    Female
                                </label>
                            </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="add1">Address1</label>
                        <input type="text" name="add1" class="form-control validation" id="add1" value="<?= htmlspecialchars($address1) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="add2">Address2</label>
                        <input type="text" name="add2" class="form-control" id="add2" value="<?= htmlspecialchars($address2) ?>">
                    </div>
                    <div class="form-group">
                        <label for="city">City</label>
                        <input type="text" name="city" class="form-control validation" id="city" value="<?= htmlspecialchars($city) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="state">State</label>
                        <input type="text" name="state" class="form-control validation" id="state" value="<?= htmlspecialchars($state) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="country">Country</label>
                        <select name="country" id="country" class="form-control validation" required>
                            <option value="" disabled <?= (empty($country)) ? 'selected' : '' ?>>Select your country</option>
                            <option value="USA" <?= $country == 'USA' ? 'selected' : '' ?>>United States</option>
                            <option value="India" <?= $country == 'India' ? 'selected' : '' ?>>India</option>
                            <option value="Canada" <?= $country == 'Canada' ? 'selected' : '' ?>>Canada</option>
                            <option value="Australia" <?= $country == 'Australia' ? 'selected' : '' ?>>Australia</option>
                            <option value="Germany" <?= $country == 'Germany' ? 'selected' : '' ?>>Germany</option>
                            <option value="Japan" <?= $country == 'Japan' ? 'selected' : '' ?>>Japan</option>
                            <option value="Brazil" <?= $country == 'Brazil' ? 'selected' : '' ?>>Brazil</option>
                            <option value="France" <?= $country == 'France' ? 'selected' : '' ?>>France</option>
                            <option value="South Africa" <?= $country == 'South Africa' ? 'selected' : '' ?>>South Africa</option>
                            <option value="United Kingdom" <?= $country == 'United Kingdom' ? 'selected' : '' ?>>United Kingdom</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="zip">ZIP</label>
                        <input type="text" name="zip" maxlength="6" class="form-control validation" id="zip" value="<?= htmlspecialchars($zip) ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="quali">Qualification</label>
                        <select name="quali" id="quali" class="form-control validation" required>
                            <option value="" disabled <?= (empty($qualification)) ? 'selected' : '' ?>>Select your qualification</option>
                            <option value="MCA" <?= $qualification == 'MCA' ? 'selected' : '' ?>>MCA</option>
                            <option value="MBA" <?= $qualification == 'MBA' ? 'selected' : '' ?>>MBA</option>
                            <option value="BCA" <?= $qualification == 'BCA' ? 'selected' : '' ?>>BCA</option>
                            <option value="B.Com" <?= $qualification == 'B.Com' ? 'selected' : '' ?>>B.Com</option>
                            <option value="BBA" <?= $qualification == 'BBA' ? 'selected' : '' ?>>BBA</option>
                            <option value="BA" <?= $qualification == 'BA' ? 'selected' : '' ?>>B.A</option>
                            <option value="MA" <?= $qualification == 'MA' ? 'selected' : '' ?>>M.A</option>
                            <option value="PhD" <?= $qualification == 'PhD' ? 'selected' : '' ?>>Ph.D</option>
                        </select>
                    </div>
                    <div class="profile-img ">
                        <label for="profile">profile photo</label>
                        <input type="file" name="profile">
                    </div>
                    <?php if ($isEdit && !empty($profile_photo)): ?>
                        <div class="img-show">
                            <img src="<?= $profile_photo ?>" alt="Profile Photo" style="width: 100px; height: 100px;">
                        </div>
                    <?php endif; ?>


                    <div class="sub-btn">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                    <div id="form-response"></div>
                </form>
            </div>
        </div>
    </section>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="./js/main.js"></script>
    <script src="./js/form.js"></script>
</body>

</html>