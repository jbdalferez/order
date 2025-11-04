<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_role = $_SESSION['role'];

if ($user_role == 'super_admin') {
    header('Location: super_admin_dashboard.php');
    exit;
} elseif ($user_role == 'cashier') {
    header('Location: cashier_dashboard.php');
    exit;
} else {
    // Fallback for unexpected roles
    echo "Access denied.";
    exit;
}
?>