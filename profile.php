<?php
session_start();
require_once __DIR__ . '/config/db.php';

if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$user = $_SESSION['user'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    $stmt = $pdo->prepare("UPDATE users SET name=?, phone=?, address=? WHERE id=?");
    $stmt->execute([$name, $phone, $address, $user['id']]);

    // Update session
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id=?");
    $stmt->execute([$user['id']]);
    $_SESSION['user'] = $stmt->fetch();

    $success = "Profile updated successfully 💖";
}
$user = $_SESSION['user'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Profile</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="wrap">
    <h2>👩‍🦰 My Profile</h2>
    <?php if (isset($success)) echo "<p style='color:green;'>$success</p>"; ?>
    <form method="post" class="form-row">
        <input class="input" type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" placeholder="Name" required>
        <input class="input" type="text" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" placeholder="Phone">
        <textarea class="input" name="address" placeholder="Address"><?php echo htmlspecialchars($user['address']); ?></textarea>
        <button class="btn">Update Profile</button>
    </form>
    <a href="orders.php">View My Orders</a> | <a href="logout.php">Logout</a>
</div>
</body>
</html>
