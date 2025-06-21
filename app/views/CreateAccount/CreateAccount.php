<?php
session_start();
require_once 'user.php';

$feedback = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $user = new User();
    $result = $user->create_user($username, $password); // fixed

    if ($result === true) {
        $_SESSION['username'] = $username;
        header("Location: index.php");
        exit;
    } else {
        $feedback = $result; // error message
    }
}
?>

<!DOCTYPE html>
<html>
<head><title>Register</title></head>
<body>
<h1>Register</h1>

<?php if ($feedback): ?>
<p style="color:red;"><?= htmlspecialchars($feedback) ?></p>
<?php endif; ?>

<form method="post" action="CreateAccount.php">
    <label>Username:</label><br>
    <input type="text" name="username" required><br><br>
    <label>Password:</label><br>
    <input type="password" name="password" required><br><br>
    <input type="submit" value="Create Account">
</form>
</body>
</html>
