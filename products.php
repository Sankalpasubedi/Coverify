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
    header("Location: index.html");
    exit;
}
$_SESSION['LAST_ACTIVITY'] = time();

$conn = new mysqli("localhost", "root", "", "mblstr");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve products and image URLs from the database
$query = "SELECT * FROM products";
$result = $conn->query($query);

if (isset($_POST['add_to_cart'])) {
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_image = $_POST['product_image'];
    $username = $_SESSION['username'];
    
    // Check if the item is already in the cart
    $item_already_in_cart = false;

    foreach ($_SESSION['cart'] as $cart_item) {
        if ($cart_item['name'] === $product_name) {
            $item_already_in_cart = true;
            break;
        }
    }

    $stock_query = "SELECT stock FROM products WHERE name = '$product_name'";
    $stock_result = $conn->query($stock_query);

    if ($stock_result->num_rows > 0) {
        $stock_row = $stock_result->fetch_assoc();
        $item_stock = $stock_row['stock'];

        if ($item_stock > 0) {
            if (!$item_already_in_cart) {
                // Check if the item is already in the cart database
                $check_query = "SELECT * FROM cart WHERE name = '$product_name' AND username = '$username'";
                $check_result = $conn->query($check_query);

                if ($check_result->num_rows == 0) {
                    // Add the selected product to the shopping cart array
                    $cart_item = array(
                        'name' => $product_name,
                        'price' => $product_price,
                        'image' => $product_image,
                    );

                    $_SESSION['cart'][] = $cart_item;

                    // Insert the item into the cart database
                    $insert_query = "INSERT INTO cart (name, price, img, username) VALUES ('$product_name', $product_price, '$product_image', '$username')";

                    if ($conn->query($insert_query) === TRUE) {
                        // Insert successful
                        echo "<script>alert('Product added to cart.');</script>";
                    } else {
                        // Insert failed
                        echo "Error: " . $insert_query . "<br>" . $conn->error;
                    }
                } else {
                    echo "<script>alert('Item is already in the cart.');</script>";
                }
            } else {
                echo "<script>alert('Item is already in the cart.');</script>";
            }
        } else {
            // Display an alert if the item is out of stock
            echo "<script>alert('Item stock is finished.');</script>";
        }
    } else {
        // Handle the case where the item is not found in the database
        echo "<script>alert('Item not found.');</script>";
    }

    // Fetch the updated cart data from the database
    
}

?>

<!DOCTYPE html>
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
   

   <link rel="stylesheet" href="./css/style.css">
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
        <div class="search">
            <form method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <input type="text" name="search" placeholder="Search for products">
                <input type="submit" value="Search">
            </form>   
        </div>
    </div>

    <div class="product_row">
            <?php
            $search = isset($_GET['search']) ? $_GET['search'] : '';

            if (!empty($search)) {
                $select_query = "SELECT * FROM products WHERE name LIKE '$search%'";
                $prod = $conn->query($select_query);

                if ($prod->num_rows > 0) {
                    while ($row = $prod->fetch_assoc()) {
                        $productName = $row["name"];
                        $productPrice = $row["price"];
                        $productImage = $row["img"];
                        $productdescr = $row["descr"];
                        ?>
                        <div class="card">
                            <div class="product_img">
                                <img src="<?php echo $productImage ?>" alt="product image">
                            </div>
                            <div class="product_details">
                                <div class="product_title">
                                    <?php echo $productName ?>
                                </div>
                                <p class="desc">
                                    <label>&#9830;</label><?php echo $productdescr ?>
                                    </p>
                                <div class="product_price">
                                    Rs. <?php echo $productPrice ?>
                                </div>
                                <div class="buynow">
                                <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                    <input type="hidden" name="product_name" value="<?php echo $productName; ?>">
                                    <input type="hidden" name="product_price" value="<?php echo $productPrice; ?>">
                                    <input type="hidden" name="product_image" value="<?php echo $productImage; ?>">
                                    <input type="hidden" name="username" value="<?php echo $username; ?>">
                                    <input type="submit" name="add_to_cart" value="Add to cart"> 
                                </form> 
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo "<li>No products found.</li>";
                }
            } else {
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $productName = $row["name"];
                        $productPrice = $row["price"];
                        $productImage = $row["img"];
                        $productdescr=$row["descr"];
                        ?>
                        <div class="card">
                            <div class="product_img">
                                <img src="<?php echo $productImage ?>" alt="product image">
                            </div>
                            <div class="product_details">
                                <div class="product_title">
                                    <?php echo $productName ?>
                                </div>
                                <p class="desc">
                                    <label>&#9830;</label><?php echo $productdescr ?>
                                    </p>
                                <div class="product_price">
                                    Rs. <?php echo $productPrice ?>
                                </div>
                                <div class="buynow">
                                <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                    <input type="hidden" name="product_name" value="<?php echo $productName; ?>">
                                    <input type="hidden" name="product_price" value="<?php echo $productPrice; ?>">
                                    <input type="hidden" name="product_image" value="<?php echo $productImage; ?>">
                                    <input type="hidden" name="username" value="<?php echo $username; ?>">
                                    <input type="submit" name="add_to_cart" value="Add to cart"> 
                                </form> 
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo "<li>No products found.</li>";
                }
            
            }
    
            ?>
        </div>
    
</body>
</html>
