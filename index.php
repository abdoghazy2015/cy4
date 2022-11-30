<?php
require('banking.php');
session_start();
if (!isset($_SESSION["username"]) or !($_SESSION['authenticated'])) {
    header("Location: login.html");
    exit();
}

require('../index.html')
?>
