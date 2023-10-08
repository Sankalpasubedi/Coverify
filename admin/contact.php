<?php
// Start the session (if not already started)
session_start();
if (!isset($_SESSION['ausername'])) {
    echo "Please login to continue";
} else{
// Connect to the database (replace with your database credentials)
$conn = new mysqli("localhost", "root", "", "mblstr");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form was submitted to delete a contact message
if (isset($_POST['delete'])) {
  
    $id = $_POST['id'];
    
    // Delete the contact message from the database
    $deleteQuery = "DELETE FROM contact WHERE id = '$id'";
    
    if ($conn->query($deleteQuery) === TRUE) {
        // Redirect back to the same page to refresh
        header("Location: contact.php");
        exit;
    } else {
        echo "Error deleting contact message: " . $conn->error;
    }
}

// Fetch contact messages from the database
$checkQuery = "SELECT * FROM contact";
$checkResult = $conn->query($checkQuery);
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
            <div class="container2">
            <?php
if ($checkResult->num_rows > 0) {
    // Initialize a variable to keep track of the current user
    $currentUser = null;
    $firstUser = true;

    // Loop through the contact messages
    while ($row = $checkResult->fetch_assoc()) {
        $id = $row['id'];
        $username = $row['username'];
        $email = $row['email'];
        $number = $row['number'];
        $mesg = $row['mesg'];

        // Check if the user has changed
        if ($username !== $currentUser) {
            // If it's not the first user, close the previous table
            if (!$firstUser) {
                echo "</table>";
            }
            $firstUser = false;

            // Display the user's name as a heading
            echo "<h2>User: $username</h2>";
            echo "<table>";
            echo "<tr><th>Email</th><th>Number</th><th>Message</th><th>Action</th></tr>";
            $currentUser = $username;
        }

        // Display the contact message details for the current user
        echo "<tr>";
        echo "<td>$email</td>";
        echo "<td>$number</td>";
        echo "<td style='height:200px; width:1500px'>$mesg</td>";
        echo "<td>";
        echo "<form method='post' action=''>";
        echo "<input type='hidden' name='id' value='$id'>";
        echo "<input type='submit' name='delete' value='Delete'>";
        echo "</form>";
        echo "</td>";
        echo "</tr>";
    }
    echo "</table>";
} else {
    echo "<li>No contact messages yet</li>";
}
?>

</div>

</div>

            </div>
            
</div> <!-- .content end -->
</div> <!-- .container end -->
</body>
</html>
