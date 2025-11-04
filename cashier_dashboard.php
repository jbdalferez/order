<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'cashier') {
    header('Location: login.php');
    exit;
}
require 'config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cashier Dashboard</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 0;
            padding: 20px;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .container {
            max-width: 800px;
            width: 100%;
            background-color: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 10px;
            font-size: 2.5em;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .welcome {
            text-align: center;
            margin-bottom: 30px;
            font-size: 18px;
            color: #666;
        }
        .welcome a {
            color: #dc3545;
            text-decoration: none;
            font-weight: bold;
        }
        .welcome a:hover {
            text-decoration: underline;
        }
        h2 {
            color: #495057;
            margin-top: 30px;
            margin-bottom: 15px;
            font-size: 1.8em;
            border-bottom: 2px solid #e9ecef;
            padding-bottom: 5px;
        }
        .link-list {
            list-style: none;
            padding: 0;
        }
        .link-list li {
            margin-bottom: 10px;
        }
        .link-list a {
            display: inline-block;
            padding: 12px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .link-list a:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Cashier Dashboard</h1>
        <p class="welcome">Welcome, Cashier! <a href="logout.php">Logout</a></p>

        <h2>Product Management</h2>
        <ul class="link-list">
            <li><a href="add_product.php">Add Product</a></li>
        </ul>

        <h2>Order Management</h2>
        <ul class="link-list">
            <li><a href="receive_order.php">Receive Order</a></li>
            <li><a href="transaction_history.php">View Transaction History</a></li>
        </ul>
    </div>
</body>
</html>
