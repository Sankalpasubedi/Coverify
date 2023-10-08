<?php
session_start();
session_unset();
session_destroy();

// Redirect to alogin.php
header("Location: alogin.php");
exit(); // Make sure to exit after setting the header
?>