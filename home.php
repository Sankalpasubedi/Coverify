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
    header("Location: index.php");
    exit;
}
$_SESSION['LAST_ACTIVITY'] = time();

$conn = new mysqli("localhost", "root", "", "mblstr");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Mobile Cover Management System - Home</title>
    <link rel="stylesheet" href="./css/nav.css">
    <link rel="stylesheet" href="./css/home.css">
    <link rel="stylesheet" href="./css/style.css">
    
</head>
<body>
    <div class="navbar">
        <div class="logo">
            <img src="./img/logo.png" >
        </div>
        <ul>
            <div class="cent">
            <li><a href="./products.php">Products</a></li>
            <li><a href="./home.php">Home</a></li>
            <li><a href="contact.php">Contact Us</a></li>       
            </div>
        </ul>
        

            <ul>
            <li><a href='cart.php'><div class='cart'><img src='./img/cart.png'></div></a></li>
            <li><a class='logout' href='php/logout.php'>Logout</a></li></ul>
       
        
    </div>
        
    </div>
    <header id="header">
				<div class="content">
					<h1>Coverify</h1>
                  
					<ul class="actions">
                    <div class="welcome-message">
            <p>Welcome, <?php echo $_SESSION['username']; ?>!</p>
        </div>
            		
					</ul>
    </div>
				</div>
				<div class="image phone"><div class="inner"><img src="img/c2.jpg" alt="" ></div></div>
			</header>


            <section id="one" class="wrapper">
				<header class="major">
					<h2>Simply for your phone</h2>
				</header>
			</section>


            <section id="two" class="wrapper-cont">
				<div class="inner alt">
					<section class="spotlight">
						<div class="image"><img src="img/ast.jpg" alt="" /></div>
						<div class="content">
							<h3>Elevating Phone Aesthetics for a Beautiful Future</h3>
							<p>Welcome to Coverify, your ultimate source for premium phone covers that redefine sophistication. 
                                Step into a world where protection meets style. Explore our collection to find the perfect accessory
                             that effortlessly complements your device and reflects your personal style.
                            <br> <a href="./products.php">Explore now!!</a></p>
						</div>
					</section>
					<section class="spotlight">
						<div class="image"><img src="img/hard.jpg" alt="" /></div>
						<div class="content">
							<h3>Unrivaled Phone Protection at Its Finest</h3>
							<p>Welcome to Coverify, where we take phone safeguarding to a whole new level.
                                 Our mission is to provide you with the ultimate protection for your valuable 
                                 device without compromising on style.Explore our curated selection to find the
                                  perfect armor that seamlessly merges uncompromising protection with sleek aesthetics.
                                  <br> <a href="./products.php">Explore now!!</a> </p>
						</div>
					</section>
					<section class="spotlight">
						<div class="image"><img src="img/cheap.jpeg" alt="" /></div>
						<div class="content">
							<h3>Extraordinary Cases, Unbeatable Prices</h3>
							<p>At Coverify, we believe that amazing phone cases shouldn't come with a hefty price tag. 
                                Our commitment to excellence extends to both design and affordability.
                                Discover the perfect phone case that transforms your device into a reflection of your unique personality,
                                 all at prices that will leave you delighted. 
                                 <br> <a href="./products.php">Discover now!!</a></p>
						</div>
					</section>
				
				</div>
			</section>

            <section id="one" class="wrapper style2 special">
				<header class="major">
					<h2>Case, What Else?<br><a href="./products.php">Click here to browse</a></h2>
				</header>
			</section>

</body>
</html>