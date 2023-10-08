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

if (isset($_POST['order_id'])) {
    // Get the order ID from the form submission
    $orderID = $_POST['order_id'];

    // Delete the order from the database
    $deleteQuery = "DELETE FROM orders WHERE id = '$orderID'";
    if ($conn->query($deleteQuery) === TRUE) {
        // Redirect back to the same page to refresh
        header("Location: orders.php");
        exit;
    } else {
        echo "Error deleting order: " . $conn->error;
    }
} 
// Move the database query outside of the if block
$checkQuery = "SELECT * FROM orders ";
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
            <div class="container1">
    <?php
    if ($checkResult->num_rows > 0) {
        // Initialize a variable to keep track of the current user
        $currentUser = null;
        $firstUser = true;

        // Loop through the orders
        while ($row = $checkResult->fetch_assoc()) {
            $username = $row['username'];
            $itemName = $row['name'];
            $itemPrice = $row['price'];
            $itemImg = $row['img'];
            $orderID = $row['id'];
            $num = $row ['num'];
            $address = $row ['address'];
            // Check if the user has changed
            if ($username !== $currentUser) {
                // If it's not the first user, close the previous table
                if (!$firstUser) {
                    echo "</table>";
                }
                $firstUser = false;

                // Display the user's name as a heading
                echo "<h2>User: $username</h2>";
                echo "<h3>Number: $num</h3>";
                echo "<h3>Address: $address</h3>";
                echo "<table>";
                echo "<tr><th>Item Name</th><th>Price</th><th>Image</th><th>Action</th></tr>";
                $currentUser = $username;
            }

            // Display the order details for the current user
            echo "<tr>";
            echo "<td style='text-align:center;width:500px'>$itemName</td>";
            echo "<td>$itemPrice</td>";
            echo "<td style='height:200px; width:100px'><img src='../$itemImg' alt='$itemName' width='100'></td>";
            echo "<td>";
            echo "<form method='post' action=''>";
            echo "<input type='hidden' name='order_id' value='$orderID'>";
            echo "<input type='submit' value='Delete'>";
            echo "</form>";
            echo "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<li>No orders yet</li>";
    }
    ?>
</div>

</div>

            </div>
            
</div> <!-- .content end -->
</div> <!-- .container end -->
</body>
</html>
