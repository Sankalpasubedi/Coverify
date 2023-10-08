<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

$session_expiration = 60 * 10; 
// Connect to the database
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > $session_expiration)) {
    session_unset(); // Unset all session variables
    session_destroy(); // Destroy the session
    header("Location: index.php");
    exit;
}
$_SESSION['LAST_ACTIVITY'] = time();

$conn = new mysqli("localhost", "root", "", "mblstr");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_SESSION['username'];
    $number = $_POST['number'];
    $email = $_POST['email'];
    $mesg = $_POST['mesg'];

    // Use prepared statement to insert data into the database
    $insert_sql = "INSERT INTO contact (username, number, email, mesg) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($insert_sql);

    // Bind parameters and execute the statement
    $stmt->bind_param("ssss", $username, $number, $email, $mesg);

    if ($stmt->execute()) {
        echo "<script>alert('Message sent successfully!');</script>";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the prepared statement
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Mobile Cover Management System - Home</title>
    
 <link rel="stylesheet" href="./css/nav.css">
 <link rel="stylesheet" href="./css/style.css"> 
 <link rel="stylesheet" href="./css/container1.css"> 

   
</head>
<body>
    <div class="navbar">
        <div class="logo">
            <img src="./img/logo.png" >
        </div>
        <div class="cent">
        <ul>
            
        <li><a href="./products.php">Products</a></li>
            <li><a href="./home.php">Home</a></li>
            <li><a href="contact.php">Contact Us</a></li>
            
            
        </ul>
    </div>
        <ul>
        <li>
        <a href='cart.php'><div class='cart'><img src='./img/cart.png'></div></a>
        </li>
        <li>
        <div class="logout"><a  href="./php/logout.php">Logout</a></div>
        </li>    
    </ul>
    </div>

    <div class="container0">
    <h1>Contact Us</h1>
        <form action="" method="post">
            <label for="number">Number:</label>
            <input type="number" id="number" name="number" required>
            
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            
            <label for="message">Message:</label>
            <textarea id="mesg" name="mesg" rows="4" required></textarea>
            
            <input type="submit" name="add_to_cart" value="Submit">
        </form>
    </div> 
</body>
</html>
