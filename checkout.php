<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

// Connect to the database
$conn = new mysqli("localhost", "root", "", "mblstr");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve items from the cart table for the logged-in user
$username = $_SESSION['username'];
$query = "SELECT * FROM cart WHERE username = '$username'";
$result = $conn->query($query);

// Initialize total price variable
$totalPrice = 0;

// Fetch cart items and calculate total price
$cartItems = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $cartItems[] = $row;
        $totalPrice += $row['price'];
    }
}

// Handle the "Buy" button click
if (isset($_POST['action']) && $_POST['action'] === 'buy') {
    $num = $_POST['num'];
    $address = $_POST['address'];
    $allItemsPurchasedSuccessfully = true;

    foreach ($cartItems as $item) {
        $cart_item_id = $item['id'];
        $item_name = $item['name'];
        $item_price = $item['price'];
        $item_img = $item['img'];
    
        // Check if the product is in stock
        $stock_query = "SELECT stock FROM products WHERE name = '$item_name'";
        $stock_result = $conn->query($stock_query);
    
        if ($stock_result->num_rows > 0) {
            $stock_row = $stock_result->fetch_assoc();
            $current_stock = $stock_row['stock'];
    
            if ($current_stock > 0) {
                // Decrement the stock by one
                $new_stock = $current_stock - 1;
    
                // Update the stock in the 'products' table
                $update_stock_query = "UPDATE products SET stock = $new_stock WHERE name = '$item_name'";
                if ($conn->query($update_stock_query) === TRUE) {
                    // Insert the item into the 'orders' table
                    $insert_query = "INSERT INTO orders (name, price, img, username, num, address) VALUES ('$item_name', $item_price, '$item_img', '$username', '$num', '$address')";
    
                    if ($conn->query($insert_query) === TRUE) {
                        // Delete the item from the 'cart' table
                        $delete_query = "DELETE FROM cart WHERE id = $cart_item_id AND username = '$username'";
    
                        if ($conn->query($delete_query) !== TRUE) {
                            // An error occurred while removing the item from the cart
                            $allItemsPurchasedSuccessfully = false;
                            break;
                        }
                    } else {
                        // An error occurred while inserting the item into the 'orders' table
                        $allItemsPurchasedSuccessfully = false;
                        break;
                    }
                } else {
                    // An error occurred while updating the stock
                    $allItemsPurchasedSuccessfully = false;
                    break;
                }
            } else {
                // The product is out of stock
                $allItemsPurchasedSuccessfully = false;
                break;
            }
        }
    }
    
    if ($allItemsPurchasedSuccessfully) {
        $conn->commit(); // All items were purchased successfully, commit the transaction
        $_SESSION['cart'] = array(); // Clear the session cart
        echo "<script>alert('Thank you for your order!');window.location.href='index.php'</script>";
    } else {
        $conn->rollback(); // Something went wrong, rollback the transaction
        echo "<script>alert('Error completing the purchase!');</script>";
    }
}
// Close the database connection
$conn->close();
?>



<html>
<head>
    <title>Mobile Cover Management System - Home</title>

 <link rel="stylesheet" href="./css/cart.css">
 <link rel="stylesheet" href="./css/container1.css">
 <link rel="stylesheet" href="./css/nav.css">
   <link rel="stylesheet" href="./css/style.css">
<style>
    .container{
        margin-top: 50px!important;
    }
    h1{
        text-align: center;
        margin-bottom: 10px;
    }
    </style>
    
   <script>
    document.querySelector('.buy').addEventListener('click', function (e) {
        e.preventDefault();
        var confirmation = confirm("Are you sure you want to complete your purchase?");
        if (confirmation) {
            // If the user confirms the purchase, submit the form to process the order
            document.querySelector('form[action=""]').submit();
        }
    });
    </script>
</head>
<body>
    
   
    <div class="container">
        <div class="back">
            <a href="cart.php"><img src="./img/back.jpg"></a>
        </div>
    
        <h1>Conformation</h1>
        <table>
            <tr>
                <th>Name</th>
                <th>Price</th>
                <th>Image</th>
            </tr>
            <?php foreach ($cartItems as $item) : ?>
                <tr>
                    <td><?= htmlentities($item['name']) ?></td>
                    <td><?= htmlentities($item['price']) ?></td>
                    <td><img src="<?= htmlentities($item['img']) ?>" alt="Product Image" width="100"></td>
                </tr>
            <?php endforeach; ?>
        </table>
        <div class="checkout">
            <p class="check1">Total Price: Rs. <?= $totalPrice ?></p>
        </div>
        <div class="container0">  
        <form method="POST" action="">
            <input type="hidden" name="action" value="buy">     
            <label for="phone">Phone Number:</label>
            <input type="text" id="num" name="num" required> 
            <label for="address">Address:</label>
            <input type="text" id="address" name="address" required>
            <input type="submit" class="buy1" value="Buy now">
        </form>
        </div>         
    </div>
    </body>
</html>

