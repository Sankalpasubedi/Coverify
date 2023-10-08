<?php
$conn = new mysqli("localhost", "root", "", "mblstr");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve products and image URLs from the database
$query = "SELECT * FROM products";
$result = $conn->query($query);

if (isset($_GET['loginfirst'])) {
    echo "<script>alert('Please Login to buy products'); window.location.href='index.php';</script>";
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
        <ul>
            <li><a href="products1.php">Products</a></li>
            <li><a href="index.php">Home</a></li>
            

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
                                        <form method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                            <input type="submit" name="loginfirst" value="Add to cart"> 
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
                                    <form method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                                        <input type="submit" name="loginfirst" value="Add to cart"> 
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
