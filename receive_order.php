<?php
session_start();
require 'config.php';

$products = $pdo->query("SELECT * FROM products")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $total = 0;
    $order_items = [];

    foreach ($_POST['products'] as $prod_id => $qty) {
        if ($qty > 0) {
            $prod = $pdo->prepare("SELECT * FROM products WHERE id = ?");
            $prod->execute([$prod_id]);
            $p = $prod->fetch();
            $total += $p['price'] * $qty;
            $order_items[] = [$prod_id, $qty, $p['price']];
        }
    }

    $stmt = $pdo->prepare("INSERT INTO orders (user_id, total) VALUES (?, ?)");
    $stmt->execute([$user_id, $total]);
    $order_id = $pdo->lastInsertId();

    foreach ($order_items as $item) {
        $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)")->execute([$order_id, $item[0], $item[1], $item[2]]);
    }

    $pdo->prepare("INSERT INTO transactions (order_id, user_id, amount, type) VALUES (?, ?, ?, 'sale')")->execute([$order_id, $user_id, $total]);

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Products</title>
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
            max-width: 900px;
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
            margin-bottom: 30px;
            font-size: 2.5em;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .product {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            padding: 15px;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            background-color: #f9f9f9;
            transition: all 0.3s ease;
        }
        .product:hover {
            background-color: #f0f0f0;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .product label {
            flex-grow: 1;
            font-weight: 500;
            color: #555;
        }
        .product .price {
            font-weight: bold;
            color: #28a745;
            margin-right: 10px;
        }
        .product input {
            width: 80px;
            padding: 8px;
            text-align: center;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }
        button {
            display: block;
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            margin-top: 20px;
            transition: all 0.3s ease;
        }
        button:hover {
            background: linear-gradient(135deg, #218838 0%, #17a2b8 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }
        .message {
            text-align: center;
            margin-top: 20px;
            font-weight: bold;
            color: #007bff;
            background-color: #e7f3ff;
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #b3d9ff;
        }
        .message a {
            color: #007bff;
            text-decoration: none;
        }
        .message a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Place Your Order</h1>
        <form method="post">
            <?php foreach ($products as $prod): ?>
                <div class="product">
                    <label><?php echo htmlspecialchars($prod['name']); ?> <span class="price">($<?php echo number_format($prod['price'], 2); ?>)</span></label>
                    <input type="number" name="products[<?php echo $prod['id']; ?>]" min="0" value="0">
                </div>
            <?php endforeach; ?>
            <button type="submit">Submit Order</button>
        </form>
        <?php if ($_SERVER['REQUEST_METHOD'] == 'POST'): ?>
            <div class="message">
                Order received. <a href="print_receipt.php?order_id=<?php echo $order_id; ?>">Print Receipt</a>
            </div>
        <?php endif; ?>
        <a href="dashboard.php">Dashboard</a>
    </div>
</body>
</html>
