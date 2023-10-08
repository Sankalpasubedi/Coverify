<?php
session_start();

// Check if the user is logged in
if (isset($_SESSION['username'])) {
    header("Location: home.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Mobile Cover Management System - Home</title>
  <style>
 
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            border-radius: 3px;
            border: 1px solid #ccc;
        }

        input[type="submit"] {
            width: 100%;           
            background-color: #4285f4;
            color: #fff;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }
 
   
    </style>
    <link rel="stylesheet" href="./css/nav.css">
    <link rel="stylesheet" href="./css/home.css">
    <link rel="stylesheet" href="./css/style.css">
     
    </script>

</head>
<body>
    <div class="navbar">
        <div class="logo">
            <img src="./img/logo.png" >
        </div>
        <ul>
            <li><a href="products1.php">Products</a></li>r
            <li><a href="index.php">Home</a></li>
            
            
           
        </ul>
        
        
    </div>
    <header id="header">
				<div class="content">
					<h1>Coverify</h1>
                
                    
					<ul class="actions">
                    <form action="php/login.php" method="POST">
                    <input type="text" id="username" name="username" placeholder="Username" required>
                    <input type="password" id="password" name="password"  placeholder="Password" required>
                   
				<input type="submit" class="button primary icon solid fa-download" value="Login" >
                <a href="registration.html">Signup here</a>
			</form>
            		
					</ul>
    </div>
				</div>
				<div class="image phone"><div class="inner"><a href="./admin/alogin.php"><img src="img/c2.jpg" alt="" ></a></div></div>
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
                            <br> <a href="./products1.php">Explore now!!</a></p>
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
                                  <br> <a href="./products1.php">Explore now!!</a> </p>
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
                                 <br> <a href="./products1.php">Discover now!!</a></p>
						</div>
					</section>
				
				</div>
			</section>

            <section id="one" class="wrapper style2 special">
				<header class="major">
					<h2>Case, What Else?<br><a href="./products1.php">Click here to browse</a></h2>
				</header>
			</section>  
</body>

</html>

