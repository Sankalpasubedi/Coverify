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


// Retrieve product ID from the URL parameter
$id = $_GET['id'];

// Fetch product details from the database
$query = "SELECT * FROM products WHERE id = $id";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_assoc($result);

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

// Set the parameters
$name = $_POST['name'];
$stock = $_POST['stock'];
$price = $_POST['price'];
$descr = $_POST['descr'];
$id = $_GET['id'];

// Update the product using a prepared statement
$updateQuery = "UPDATE products SET name = ?, stock = ?, price = ?, descr = ? WHERE id = ?";
$stmt = $conn->prepare($updateQuery);
$stmt->bind_param("ssdsi", $name, $stock, $price, $descr, $id);

if ($stmt->execute()) {
    // Redirect back to the dashboard
    header('Location: ../manage.php');
    exit;
} else {
    // Handle the error, e.g., display an error message or log it
    echo "Error: " . $stmt->error;
}

$stmt->close();

}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Product</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }

        h1 {
            text-align: center;
        }

        form {
            max-width: 400px;
            margin: 0 auto;
        }

        form textarea{
            font-family: Arial, sans-serif;
            width: 100%;
            height: 300px;
            font-size: 15px ;
        }

        input[type="text"], input[type="submit"] {
            display: block;
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: #fff;
            width: 100%;
            cursor: pointer;
        }

        /* Optional: Add styling to highlight the edited input fields */
        input[type="text"]:focus {
            outline: none;
            border-color: #4CAF50;
        }
    </style>
</head>
<body>
    <h1>Edit Product</h1>

    <form action="" method="POST">
        <input type="text" name="name" value="<?php echo $row['name']; ?>" placeholder="Product Name" required><br>
        <input type="text" name="stock" value="<?php echo $row['stock']; ?>" placeholder="Product Stock" required><br>
        <input type="text" name="price" value="<?php echo $row['price']; ?>" placeholder="Price" required><br>
        <textarea name="descr" placeholder="Description" required><?php echo $row['descr']; ?></textarea><br>
        <input type="submit" value="Update Product">
    </form>
</body>
</html>
