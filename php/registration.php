<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve form data
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Perform basic validation (you can add more checks as per your requirements)
    if (!empty($username) && !empty($email) && !empty($password)) {
        // Connect to the database
    

        $conn = new mysqli("localhost", "root", "", "mblstr");

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Check if the username already exists
        $checkQuery = "SELECT * FROM user WHERE username = '$username'";
        $checkResult = $conn->query($checkQuery);

        if ($checkResult->num_rows > 0) {
            // Existing ID alert
            echo "<script>alert('Existing ID. Please choose a different username.'); window.location.href='../registration.html';</script>";
            
        } else {
            // Insert user data into the database
            $insertQuery = "INSERT INTO user (username, password, email) VALUES ('$username', '$password', '$email')";
            if ($conn->query($insertQuery) === true) {
                // Registration successful
                echo "<script>alert('Regestration Successful!'); window.location.href='../index.php';</script>";
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