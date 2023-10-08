<?php

session_start();

if (!isset($_SESSION['ausername'])) {
    echo "Please login to continue";
} else {
    if (isset($_POST["upload_form"])) {
        // Establish a database connection
        $conn = new mysqli("localhost", "root", "", "mblstr");

        // Check the connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $product_name = $_POST['product_name'];
        $product_price = $_POST['product_price'];
        $product_descr = $_POST['product_descr'];

        $p_img = $_FILES["productimg"];
        $target_directory = "./img/uploads/";
        $target_file = $target_directory . basename($p_img["name"]);

        if (move_uploaded_file($p_img["tmp_name"], $target_file)) {
            // Use prepared statements to insert data
            $stmt = $conn->prepare("INSERT INTO products (name, price, img, descr) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $product_name, $product_price, $target_file, $product_descr);

            if ($stmt->execute()) {
                ?>
                <script>
                    alert("Insertion successful");
                </script>
                <?php
            } else {
                echo "Error: " . $stmt->error;
            }

            // Close the statement
            $stmt->close();
        } else {
            echo "Failed to upload file.";
        }

        // Close the database connection
        $conn->close();
    }
}
?>

<html>
<head>
    <title>Upload file</title>
    <link rel="stylesheet" href="./admin/css/admin.css">

</head>

<body><div class="container">
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
            <h2>Upload Case</h2>
    <form action="#" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <input type="text" name="product_name" placeholder="Enter Product name">
        </div>
        <div class="form-group">
            <input type="number" name="product_price" placeholder="Enter Product price">
        </div>
        <div class="form-group">
            <input type="text" name="product_descr" placeholder="Enter Product desc">
        </div>
        <div class="form-group">
            <input type="file" name="productimg" accept="image/*">
        </div>
        
        <input type="hidden" name="upload_form" value="1">
        <input type="submit" value="Upload">
    </form>
    </div>


</div> <!-- .content end -->
</div> <!-- .container end -->
</body>
</html>
