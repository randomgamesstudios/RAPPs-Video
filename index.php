<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $users = file('users.txt', FILE_IGNORE_NEW_LINES);
    $valid = false;
    foreach ($users as $user) {
        list($u, $p) = explode(':', $user);
        if ($_POST['username'] === $u && $_POST['password'] === $p) {
            $_SESSION['user'] = $u;
            header('Location: videos.php');
            exit;
        }
    }
    $error = "Invalid username or password";
}
?>

<form method="POST">
    <h2>Login</h2>
    <?php if (!empty($error)) echo "<p>$error</p>"; ?>
    <input type="text" name="username" required placeholder="Username"><br>
    <input type="password" name="password" required placeholder="Password"><br>
    <input type="submit" value="Login">
</form>
