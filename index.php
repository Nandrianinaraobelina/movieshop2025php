<?php
// Main entry point of the application
session_start();
include_once 'includes/db.php';

// Get current page from URL parameter, default to home
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

// Include header
include_once 'includes/header.php';

// Load appropriate page content based on page parameter
switch ($page) {
    case 'home':
        include_once 'pages/home.php';
        break;
    case 'catalog':
        include_once 'pages/catalog.php';
        break;
    case 'product':
        include_once 'pages/product.php';
        break;
    case 'cart':
        include_once 'pages/cart.php';
        break;
    case 'checkout':
        include_once 'pages/checkout.php';
        break;
    case 'invoice':
        include_once 'pages/invoice.php';
        break;
    case 'login':
        include_once 'pages/login.php';
        break;
    case 'register':
        include_once 'pages/register.php';
        break;
    case 'account':
        include_once 'pages/account.php';
        break;
    default:
        include_once 'pages/home.php';
}

// Include footer
include_once 'includes/footer.php';
?>