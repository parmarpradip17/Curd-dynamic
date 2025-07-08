<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Application Form</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- jQuery Validation Plugin -->
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.min.js"></script>
    <style>
        .error {
            color: red;
            font-size: 0.875em;
        }

        .form-container {
            max-width: 800px;
            margin: 30px auto;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .dynamic-field {
            margin-bottom: 15px;
            padding: 15px;
            background: #fff;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        .add-btn,
        .remove-btn {
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="container form-container">
        <h2 class="text-center mb-4">Job Application Form</h2>
        <form id="applicationForm" enctype="multipart/form-data">
            <!-- Personal Information Section -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    Personal Information
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="fname" class="form-label">First Name*</label>
                            <input type="text" class="form-control" id="fname" name="fname" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="lname" class="form-label">Last Name*</label>
                            <input type="text" class="form-control" id="lname" name="lname" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email*</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Phone*</label>
                            <input type="tel" class="form-control" id="phone" name="phone" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="resume" class="form-label">Resume (Photo/PDF Upload)*</label>
                        <input type="file" class="form-control" id="resume" name="resume" accept=".pdf,.jpg,.jpeg,.png" required>
                        <small class="text-muted">Accepted formats: PDF, JPG, PNG (Max 5MB)</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Gender*</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="gender" id="male" value="male" required>
                            <label class="form-check-label" for="male">Male</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="gender" id="female" value="female">
                            <label class="form-check-label" for="female">Female</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="gender" id="other" value="other">
                            <label class="form-check-label" for="other">Other</label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Address Section -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    Address Information
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="address1" class="form-label">Address Line 1*</label>
                        <input type="text" class="form-control" id="address1" name="address1" required>
                    </div>

                    <div class="mb-3">
                        <label for="address2" class="form-label">Address Line 2</label>
                        <input type="text" class="form-control" id="address2" name="address2">
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="city" class="form-label">City*</label>
                            <input type="text" class="form-control" id="city" name="city" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="state" class="form-label">State/Province*</label>
                            <input type="text" class="form-control" id="state" name="state" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="country" class="form-label">Country*</label>
                            <select class="form-select" id="country" name="country" required>
                                <option value="">Select Country</option>
                                <option value="USA">United States</option>
                                <option value="UK">United Kingdom</option>
                                <option value="Canada">Canada</option>
                                <option value="Australia">Australia</option>
                                <option value="India">India</option>
                                <!-- Add more countries as needed -->
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="zip" class="form-label">ZIP/Postal Code*</label>
                            <input type="text" class="form-control" id="zip" name="zip" required>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Qualification Section (Dynamic) -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <span>Qualifications</span>
                    <button type="button" class="btn btn-sm btn-light" id="addQualification">Add Qualification</button>
                </div>
                <div class="card-body" id="qualificationsContainer">
                    <div class="dynamic-field qualification-field">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="qualification" class="form-label">Qualification*</label>
                                <select class="form-select qualification" name="qualification[]" required>
                                    <option value="">Select Qualification</option>
                                    <option value="High School">High School</option>
                                    <option value="Diploma">Diploma</option>
                                    <option value="Bachelor's Degree">Bachelor's Degree</option>
                                    <option value="Master's Degree">Master's Degree</option>
                                    <option value="PhD">PhD</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="percentage" class="form-label">Percentage/GPA*</label>
                                <input type="text" class="form-control percentage" name="percentage[]" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="passing_year" class="form-label">Passing Year*</label>
                                <select class="form-select passing_year" name="passing_year[]" required>
                                    <option value="">Select Year</option>
                                    <?php
                                    $currentYear = date("Y");
                                    for ($year = $currentYear; $year >= 1970; $year--) {
                                        echo "<option value='$year'>$year</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="university" class="form-label">University/Institution*</label>
                                <input type="text" class="form-control university" name="university[]" required>
                            </div>
                        </div>

                        <button type="button" class="btn btn-danger btn-sm remove-btn remove-qualification">Remove</button>
                    </div>
                </div>
            </div>

            <!-- Hobbies Section (Dynamic) -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <span>Hobbies</span>
                    <button type="button" class="btn btn-sm btn-light" id="addHobby">Add Hobby</button>
                </div>
                <div class="card-body" id="hobbiesContainer">
                    <div class="dynamic-field hobby-field">
                        <div class="mb-3">
                            <label for="hobby" class="form-label">Hobby*</label>
                            <input type="text" class="form-control hobby" name="hobby[]" required>
                        </div>
                        <button type="button" class="btn btn-danger btn-sm remove-btn remove-hobby">Remove</button>
                    </div>
                </div>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary btn-lg">Submit Application</button>
            </div>
        </form>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script src="./js/form.js"></script>
</body>

</html>