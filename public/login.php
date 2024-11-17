<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Simple hardcoded authentication (for demo purposes)
    if ($username === "yugjindal1234@gmail.com" && $password === "yug1234") {
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;
        header("Location: index.php");
        exit;
    } else {
        $error = "Invalid credentials!";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Login - SQLMagic</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="icon" type="image/x-icon" href="favicon.ico">
</head>

<body>
    <div class="login-container" style="height: 100vh; display: flex; justify-content: center; align-items: center;">
        <form method="POST" action="" style="background: var(--surface); padding: 2rem; border-radius: 10px; min-width: 300px;">
            <h2 style="text-align: center; margin-bottom: 2rem;">Login to SQLMagic</h2>
            <?php if (isset($error)) { ?>
                <p style="color: red; text-align: center;"><?php echo $error; ?></p>
            <?php } ?>
            <input type="text" name="username" placeholder="Username" required style="width: 100%; margin-bottom: 1rem; padding: 0.5rem;">
            <input type="password" name="password" placeholder="Password" required style="width: 100%; margin-bottom: 1rem; padding: 0.5rem;">
            <button type="submit" class="cta-button" style="width: 100%;">Login</button>
        </form>
    </div>
</body>

</html>