<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}
$session_expiration = 60 * 10; 
// Connect to the database
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > $session_expiration)) {
    session_unset(); // Unset all session variables
    session_destroy(); // Destroy the session
    header("Location: index.html");
    exit;
}
$_SESSION['LAST_ACTIVITY'] = time();

$conn = new mysqli("localhost", "root", "", "mblstr");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle item removal from the cart
if (isset($_POST['action']) && $_POST['action'] === 'remove_from_cart') {
    $cart_item_id = $_POST['cart_item_id'];

    $username = $_SESSION['username'];

    $delete_query = "DELETE FROM cart WHERE id = '$cart_item_id' AND username = '$username'";
    if ($conn->query($delete_query) === TRUE) {
        $cart_query = "SELECT * FROM cart WHERE username = '$username'";
    $cart_result = $conn->query($cart_query);

    $_SESSION['cart'] = array(); // Clear the session cart

    while ($cart_row = $cart_result->fetch_assoc()) {
        $cart_item = array(
            'name' => $cart_row['name'],
            'price' => $cart_row['price'],
            'image' => $cart_row['img'],
        );
        $_SESSION['cart'][] = $cart_item;
    }
        echo "<script>alert('Item deleted!'); window.location.href='cart.php';</script>";
    } else {
        echo "<script>alert('Error deleting item!'); window.location.href='cart.php';</script>";
    }
}

// Retrieve items from the cart table for the logged-in user
$username = $_SESSION['username'];
$query = "SELECT * FROM cart WHERE username = '$username'";
$result = $conn->query($query);

// Initialize an array to store cart items
$cartItems = array();

// Fetch cart items
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $cartItems[] = $row;
    }
}
?>
<html>
<head>
    <title>Mobile Cover Management System - Home</title>
    <style>
       
    
       .container {
            max-width: 400px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

 
        .product-list  .product-image {
            display: block;
            width: 200px;
            height: auto;
            margin: 10px auto;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            align-items: center;
        }

        input[type="text"] {
            text-align: center;
            padding: 8px;
            font-size: 14px;
            border-radius: 4px;
            border: 1px solid #ccc;
            margin: 8px;
            margin-left: 30px;
        }

        .search input[type="submit"] {
            padding: 8px 5px;
            font-size: 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 1px;
            cursor: pointer;
            margin-left: 30px;
        }

        .search input[type="submit"]:hover {
            background-color: #45a049;
        }
        
        
        
    </style>
 <link rel="stylesheet" href="./css/nav.css">
 <link rel="stylesheet" href="./css/cart.css">

   <link rel="stylesheet" href="./css/style.css">
   <script>
function confirmCheckout() {
    if (<?= count($cartItems) ?> === 0) {
        alert("Add some products to the cart before proceeding with the checkout!!");
        window.location.href = "products.php";
    } else {
        var confirmation = confirm("Are you sure you want to proceed with the checkout?");
        if (confirmation) {
            // If the user clicks "OK," proceed with checkout
            window.location.href = "checkout.php"; // Replace with your final checkout page URL
        } else {
            // If the user clicks "Cancel," do nothing
        }
    }
}
</script>

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
    <div class="container">
        <h1>Your Cart</h1>
        <table>
            <tr>
                <th>Name</th>
                <th>Price</th>
                <th>Image</th>
                <th>Action</th>
            </tr>
            <?php foreach ($cartItems as $item) : ?>
                <tr>
                    <td><?= htmlentities($item['name']) ?></td>
                    <td><?= htmlentities($item['price']) ?></td>
                    <td><img src="<?= htmlentities($item['img']) ?>" alt="Product Image" width="100"></td>
                    <td>
                        <form method="POST" action="">
                            <input type="hidden" name="action" value="remove_from_cart">
                            <input type="hidden" name="cart_item_id" value="<?= htmlentities($item['id']) ?>">
                            <button type="submit" class="remove">Remove</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        <div class="checkout">
            <div class="checkout">
                <button class="check" onclick="confirmCheckout()">Checkout</button>
            </div>
        </div>
    </div>
    </body>
</html>