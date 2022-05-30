<?php

require_once "./functions.php";

// Initialize the session
session_start();

// Unset all of the session variables
$_SESSION = array();

if (isset ($_COOKIE['user_id'])) {
    unset($_COOKIE['user_id']);
    setcookie('user_id', null, -1, '/');
    return true;
}

// Destroy the session.
session_destroy();

// Redirect to login page
header("location: login.php");
// exit;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logout | Lijs</title>
    <?php echo '<link rel="stylesheet" href="' . auto_version('styles/output.css') . '" type="text/css">'; ?>
    <?php echo '<script src="' . auto_version('scripts/functions.js') . '"></script>'; ?>
    <?php echo '<link rel="manifest" href="' . auto_version('manifest.json') . '">'; ?>
    <link rel="icon" href="images/favicon.ico">
    <link rel="apple-touch-icon" href="images/apple-touch-icon.png">
    <script type="module">
        import 'https://cdn.jsdelivr.net/npm/@pwabuilder/pwaupdate';
        const el = document.createElement('pwa-update');
        document.body.appendChild(el);
    </script>
</head>
<body>
    <header id="header" class="flex items-center justify-between w-full py-5 mt-10 text-3xl px-7">
        <p class="font-semibold">Je bent uitgelogd</p>
    </header>
    <div class="flex flex-col mt-10 px-7">
        <p>Je bent nu uitgelogd.</p>
        <p>Je wordt doorgestuurd naar de login pagina.</p>
        <p>Als je niet automatisch doorgestuurd wordt, klik dan <a class="underline " href="login.php">hier</a>.</p>
    </div>
</body>
</html>
