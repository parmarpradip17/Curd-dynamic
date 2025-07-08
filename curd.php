<?php
session_start();
error_reporting(E_ALL);

// Database connection (mysqli object-oriented)
$conn = new mysqli("localhost", "root", "", "info_stud");

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Fetch all students
$sql = "SELECT * FROM students ORDER BY id";
$result = $conn->query($sql);

$students = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }
}

// Display session message if exists
$message = $_SESSION['message'] ?? '';
$messageType = $_SESSION['message_type'] ?? '';
unset($_SESSION['message'], $_SESSION['message_type']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Student Records</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/curd.css">
</head>

<body>
    <header class="header" id="header">
        <div class="container">
            <div class="header-txt" id="header-txt">
                <h2>Student Records</h2>
            </div>
        </div>
    </header>
    <div id="edit-container"></div>

    <section class="student-records">
        <div class="container">
            <?php if ($message): ?>
                <div class="alert alert-<?= $messageType ?>"><?= htmlspecialchars($message) ?></div>
                <script>
                    setTimeout(function() {
                        document.querySelector('.alert').style.display = 'none';
                    }, 3000);
                </script>
            <?php endif; ?>

            <a href="form1.php" class="btn btn-primary mb-3">Add New Student</a>

            <table class="student-table table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Gender</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($students)) : ?>
                        <?php foreach ($students as $student) : ?>
                            <tr>
                                <td><?= htmlspecialchars($student['id']) ?></td>
                                <td><?= htmlspecialchars($student['fname']) . ' ' . htmlspecialchars($student['lname']) ?></td>
                                <td><?= htmlspecialchars($student['email']) ?></td>
                                <td><?= htmlspecialchars($student['phone']) ?></td>
                                <td><?= htmlspecialchars($student['gender']) ?></td>
                                <td>
                                    <a href="update_student.php?id=<?= $student['id'] ?>" class="btn btn-edit btn-sm">Edit</a>
                                    <a href="delete.php?id=<?= $student['id'] ?>" class="btn btn-delete btn-sm" data-id="<?= $student['id'] ?>">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="6" class="text-center">No student records found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="./js/Ajax.js"></script>
</body>

</html>

<?php $conn->close(); ?>