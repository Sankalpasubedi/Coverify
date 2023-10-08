<?php
// Check if the form is submitted

session_start();
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve form data
    $ausername = $_POST["ausername"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Perform basic validation (you can add more checks as per your requirements)
    if (!empty($ausername) && !empty($email) && !empty($password)) {
        // Connect to the database
    

        $conn = new mysqli("localhost", "root", "", "mblstr");

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Check if the ausername already exists
        $checkQuery = "SELECT * FROM admin WHERE ausername = '$ausername'";
        $checkResult = $conn->query($checkQuery);

        if ($checkResult->num_rows > 0) {
            // Existing ID alert
            echo "<script>alert('Existing ID. Please choose a different username.'); window.location.href='registration.php';</script>";
            
        } else {
            // Insert user data into the database
            $insertQuery = "INSERT INTO admin (ausername, password, email) VALUES ('$ausername', '$password', '$email')";
            if ($conn->query($insertQuery) === true) {
                // Registration successful
                echo "<script>alert('Regestration Successful!'); window.location.href='admin.php';</script>";
            } else {
                // Error inserting data
                echo "Error: " . $insertQuery . "<br>" . $conn->error;
            }
        }

        $conn->close();
    } else {
        // Invalid input
        echo "<script>alert('Please fill in all the fields.');</script>";
    }
}
?>
<html>
<head>
    <title>Registration Page</title>
    
    <link rel="stylesheet" href="./css/admin.css">
</head>
<body><div class="container">
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
            </div>
            <div class="item">
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
        <h2>Registration</h2>
        <form action="" method="POST">
            <div class="form-group">
                <label for="ausername">Username:</label>
                <input type="text" id="ausername" name="ausername" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <input type="submit" value="Register">
        </form>
    </div>
    


</div> <!-- .content end -->
</div> <!-- .container end -->
</body>
</html>
