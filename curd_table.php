<!DOCTYPE html>
<html>

<head>
    <title>CRUD</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/curd.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body class="bg-light">

    <div class="container-main py-5">

        <h2 class="text-center mb-4">User List</h2>

        <?php
        $updated = $_GET['updated'] ?? 0;
        ?>

        <?php if ($updated): ?>
            <div class="alert alert-success text-center">Record updated successfully!</div>
        <?php endif; ?>

        <?php if (isset($_GET['msg']) && $_GET['msg'] === 'deleted'): ?>
            <div class="alert alert-success text-center">User deleted successfully.</div>
        <?php endif; ?>

        <div class="text-end mb-3">
            <a href="./form1.php" target="_blank" class="btn btn-primary">Add New User</a>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-danger  text-center">
                    <tr>
                        <th>ID</th>
                        <th>Profile Image</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Gender</th>
                        <th>City</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>
                                <td class='text-center'>" . htmlspecialchars($row["id"]) . "</td>
                                <td class='text-center'>
                                    <img src='" . htmlspecialchars($row["profile_photo"]) . "' class='rounded-circle' width='50' height='50'>
                                </td>
                                <td>" . htmlspecialchars($row["fname"]) . " " . htmlspecialchars($row["lname"]) . "</td>
                                <td>" . htmlspecialchars($row["email"]) . "</td>
                                <td>" . htmlspecialchars($row["phone"]) . "</td>
                                <td>" . htmlspecialchars($row["gender"]) . "</td>
                                <td>" . htmlspecialchars($row["city"]) . "</td>
                                <td class='text-center'>
                                    <a href='#' class='btn btn-sm btn-danger delete-btn ' data-id='" . $row["id"] . "'>Delete</a>
                                    <a href='form1.php?id=" . $row["id"] . "' class='btn btn-sm btn-success' target='_blank'>Update</a>
                                </td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='8' class='text-center'>No data found</td></tr>";
                    }

                    $conn->close();
                    ?>

                </tbody>
            </table>
        </div>

    </div>

    <script src="./js/delete.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>