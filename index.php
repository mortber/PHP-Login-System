<?php
session_start();

const VALID_USERNAME = 'Smørbukk';
const VALID_PASSWORD = 'Huldra';

if (isset($_GET['logout'])) {
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );
    }
    session_destroy();
    header('Location: index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    if (hash_equals(VALID_USERNAME, $username) && hash_equals(VALID_PASSWORD, $password)) {
        $_SESSION['logged_in'] = true;
        $_SESSION['username'] = VALID_USERNAME;
        header('Location: novel.html');
        exit;
    }

    $error = 'Ugyldig brukernavn eller passord.';
    $_SESSION['logged_in'] = false;
    unset($_SESSION['username']);
}

$loggedIn = $_SESSION['logged_in'] ?? false;

if ($loggedIn) {
    header('Location: novel.html');
    exit;
}
?>
<!DOCTYPE html>
<html lang="no">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Innlogging</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            min-height: 100vh;
            align-items: center;
            justify-content: center;
            background-color: #f5f5f5;
        }
        .login-container {
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            width: 100%;
            max-width: 360px;
        }
        h1 {
            margin-top: 0;
            text-align: center;
        }
        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: bold;
        }
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 0.5rem;
            margin-bottom: 1rem;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            width: 100%;
            padding: 0.6rem;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
        }
        button:hover {
            background-color: #0056b3;
        }
        .error {
            color: #d93025;
            margin-bottom: 1rem;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="login-container">
    <h1>Logg inn</h1>
    <?php if ($error): ?>
        <div class="error"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
    <?php endif; ?>
    <form method="post" action="">
        <label for="username">Brukernavn</label>
        <input type="text" id="username" name="username" required>
        <label for="password">Passord</label>
        <input type="password" id="password" name="password" required>
        <button type="submit">Logg inn</button>
    </form>
</div>
</body>
</html>
