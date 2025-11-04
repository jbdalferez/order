<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'super_admin') {
    die("Access denied. Only super admins can access this page.");
}
require 'config.php';

// Handle actions with confirmation
$message = '';
if (isset($_GET['activate'])) {
    $id = intval($_GET['activate']);
    try {
        $stmt = $pdo->prepare("UPDATE users SET status = 'active' WHERE id = ? AND role = 'cashier'");
        $stmt->execute([$id]);
        if ($stmt->rowCount() > 0) {
            $message = "Account activated successfully.";
        } else {
            $message = "Account not found or already active.";
        }
    } catch (PDOException $e) {
        $message = "Error activating account: " . $e->getMessage();
    }
}

if (isset($_GET['suspend'])) {
    $id = intval($_GET['suspend']);
    try {
        $stmt = $pdo->prepare("UPDATE users SET status = 'suspended' WHERE id = ? AND role = 'cashier'");
        $stmt->execute([$id]);
        if ($stmt->rowCount() > 0) {
            $message = "Account suspended successfully.";
        } else {
            $message = "Account not found or already suspended.";
        }
    } catch (PDOException $e) {
        $message = "Error suspending account: " . $e->getMessage();
    }
}

// Fetch cashier users
try {
    $users = $pdo->query("SELECT id, username, status, created_at FROM users WHERE role = 'cashier' ORDER BY status, created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Cashier Accounts</title>
    <link rel="stylesheet" href="css/styles.css">
    <script>
        function confirmAction(action, id) {
            return confirm("Are you sure you want to " + action + " this account?");
        }
    </script>
</head>
<body>
    <h1>Manage Cashier Accounts</h1>
    <p><a href="super_admin_dashboard.php">Back to Dashboard</a></p>
    
    <?php if ($message): ?>
        <p class="message"><?php echo $message; ?></p>
    <?php endif; ?>
    
    <?php if (empty($users)): ?>
        <p>No cashier accounts found.</p>
    <?php else: ?>
        <table>
            <tr>
                <th>Username</th>
                <th>Status</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                    <td><?php echo htmlspecialchars($user['status']); ?></td>
                    <td><?php echo htmlspecialchars($user['created_at']); ?></td>
                    <td>
                        <?php if ($user['status'] == 'pending'): ?>
                            <a href="?activate=<?php echo $user['id']; ?>" onclick="return confirmAction('activate', <?php echo $user['id']; ?>)">Activate</a>
                        <?php elseif ($user['status'] == 'active'): ?>
                            <a href="?suspend=<?php echo $user['id']; ?>" onclick="return confirmAction('suspend', <?php echo $user['id']; ?>)">Suspend</a>
                        <?php elseif ($user['status'] == 'suspended'): ?>
                            <a href="?activate=<?php echo $user['id']; ?>" onclick="return confirmAction('reactivate', <?php echo $user['id']; ?>)">Reactivate</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</body>
</html>