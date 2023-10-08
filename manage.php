<?php

session_start();
if (!isset($_SESSION['ausername'])) {
    echo "Please login to continue";
} else{
$conn = new mysqli("localhost", "root", "", "mblstr");
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete' && isset($_POST['id'])) {
    $deleteId = $_POST['id'];
    $deleteSql = "DELETE FROM products WHERE id = ?";
    $deleteStmt = mysqli_prepare($conn, $deleteSql);
    mysqli_stmt_bind_param($deleteStmt, 'i', $deleteId);

    if (mysqli_stmt_execute($deleteStmt)) {
        // Redirect to refresh the page after deletion
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
}

$query = "SELECT * FROM products"; // Query to select from the 'admin' table
$result = mysqli_query($conn, $query);
$adminList = array();

while ($row = mysqli_fetch_assoc($result)) {
    $adminList[] = $row;
}

mysqli_free_result($result);
mysqli_close($conn);
}
?>

<html>

<head>
    <title>Mobile Cover Management System - Admin</title>

    <link rel="stylesheet" href="./css/manage.css">


</head>

<body>
    <div class="container">
        <input type="checkbox" name="hide-sidebar" id="hide-sidebar">

        <div class="sidebar">
            <div class="item logo">
            <a href="./admin/admin.php">
                <img src="./img/logo.png">
            </a>    
            </div>

            <div class="item">
              <a href="./admin/registration.php">Add admin</a>
            </div>

            <div class="item">
                <a href="upload.php">Upload</a>
            </div>

            <div class="item">
            <a href="manage.php">Manage Items</a>
            </div>

             <div class="item">
            <a href="./admin/orders.php">Orders</a>
            </div>
            <div class="item">
            <a href="./admin/contact.php">Contacts</a>
            </div>
            <div class="item">
              <a href="./admin/session.php">Log Out</a>
            </div>
           
        </div> <!-- .sidebar end -->

        <div class="content">
            <div class="header">
                <h1>Admin-Dashboard</h1>
                <label for="hide-sidebar">&#9776;</label>
            </div>

            <div class="container1">
                <table>
                    <tr>
                        <th>Name</th>
                        <th>Stock</th>
                        <th>Price</th>
                        <th>Image</th>
                        <th>Action</th>
                    </tr>
                    <?php foreach ($adminList as $admin) : ?>
                        <tr>
                            <td><?= htmlentities($admin['name']) ?></td>
                            <td><?= htmlentities($admin['stock']) ?></td>
                            <td><?= htmlentities($admin['price']) ?></td>
                            <td><img src="<?= htmlentities($admin['img']) ?>" alt="Admin Image" width="100"></td>
                            <td>
                                <form method="POST" action="">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?= htmlentities($admin['id']) ?>">
                                    <button type="submit">Delete</button>
                                </form>
                                <a href="./admin/edit.php?id=<?= htmlentities($admin['id']) ?>" class="edit">Edit</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
    </div>
</body>

</html>
