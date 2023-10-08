<style>
     @import url('https://fonts.googleapis.com/css2?family=Signika+Negative:wght@600&display=swap');
        *{
            font-family: 'Signika Negative', sans-serif;
        }
.register-box {
  display: grid;
  justify-content: center;
  align-items: center;
  height: 100px;
  width: 100%;
  background-color: #f2f2f2;
  border: 1px solid #ccc;
  border-radius: 1px;
  padding: 2px 5px;
  
}
.register-section a{
    text-decoration: none;
}
.register-section a span {
    background-color: #333333;
    color: #fff;
    border: 1px solid #fff;
    width: 100%;
    padding: 2px 5px;
    text-align: center;
    cursor: pointer;
    border-radius: 10px;

}
.register-section a span:hover {
    background-color: white;
    color: black;
    border: 1px solid #333333;
}

</style>

<?php
session_start();
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve form data
    $ausername = $_POST["ausername"];
    $password = $_POST["password"];


    // Perform basic validation (you can add more checks as per your requirements)
    if (!empty($ausername) && !empty($password)) {
    

        $conn = new mysqli("localhost", "root", "", "mblstr");

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Query the database for user credentials
        $sql = "SELECT * FROM admin WHERE ausername = '$ausername' AND password = '$password'";
        $result = $conn->query($sql);

        if ($result->num_rows === 1) {
            // Successful login
            session_name('AdminSession');
            $_SESSION['ausername'] = $ausername;
            header("Location: admin.php");

        } else {
            // Invalid credentials
            echo "
                <div class='register-box'>
                    <div class='register-section'>
                        <p>Invalid username or password.<a href='alogin.php'><span>Go back!</span></a></p>
                     </div>
                </div>
                ";
        }

        $conn->close();
    } else {
        // Invalid input
        echo "Please fill in all the fields.";
    }
}
?>