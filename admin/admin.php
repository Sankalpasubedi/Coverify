<?php

session_start();
// Database connection parameters
$servername = "localhost";
$ausername = "root";
$password = "";
$database = "mblstr";

// Create a database connection
$conn = new mysqli($servername, $ausername, $password, $database);

// Check the database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// Retrieve admin data from the database (assuming you have already authenticated the admin)
if (isset($_SESSION['ausername'])) {
    $admin_ausername = $_SESSION['ausername'];
    $query = "SELECT email, password FROM admin WHERE ausername = '$admin_ausername'";
    $result = $conn->query($query);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $admin_email = $row["email"];
        $admin_password = $row["password"];
    }
}

// Handle form submission to update admin data
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update"])) {
    $new_email = $_POST["new_email"];
    $new_password = $_POST["new_password"];
    
    // Update admin data in the database
    $update_query = "UPDATE admin SET email = '$new_email', password = '$new_password' WHERE ausername = '$admin_ausername'";
    
    if ($conn->query($update_query) === TRUE) {
        // Update successful
        $admin_email = $new_email; // Update the displayed email
        $admin_password = $new_password; // Update the displayed password
        // You can add a success message here
    } else {
        // Handle update failure
        // You can add an error message here
    }
}

// Close the database connection
$conn->close();
?>

<html>
<head>
    <title>Mobile Cover Management System - Admin</title>
    <link rel="stylesheet" href="./css/admin.css">
  


</head>
<body>
    <div class="container">
        <input type="checkbox" name="hide-sidebar" id="hide-sidebar">

        <div class="sidebar">
            <div class="item logo">
            <a href="admin.php">
                <img src="../img/logo.png">
            </a>    
            </div>

            <div class="item">
              <a href="registration.php">Add admin</a>
            </div>

            <div class="item">
                <a href="../upload.php">Upload</a>
            </div>

            <div class="item">
            <a href="../manage.php">Manage Items</a>

            </div><div class="item">
            <a href="orders.php">Orders</a>
            </div>
            <div class="item">
            <a href="contact.php">Contacts</a>
            </div>
            <div class="item">
              <a href="session.php">Log Out</a>
            </div>
           
        </div> <!-- .sidebar end -->

        <div class="content">
            <div class="header">
                <h1>Admin-Dashboard</h1>
                <label for="hide-sidebar">&#9776;</label>
            </div>

            <div class="container1">
                <!-- Add a section for admin ausername and user data editing form -->
                <h2>Welcome, Admin </h2>
                <form method="POST" action="">
                    <label for="new_email">Email:</label>
                    <input type="text" name="new_email" id="new_email" value="<?php echo $admin_email; ?>"><br><br>
                    <label for="new_password">Password:</label>
                    <input type="password" name="new_password" id="new_password" value="<?php echo $admin_password; ?>"><br><br>
                    <button type="submit" name="update">Update</button>
                </form>
            </div>
            
        </div> <!-- .content end -->
      
    </div> <!-- .container end -->
</body>

</html>
