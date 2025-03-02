<?php

// Include config file
require_once "./functions.php";

// Initialize the session
// session_start();

// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: index.php");
    exit;
}

// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = $login_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = "Voer een gebruikersnaam in.";
    } else{
        $username = trim($_POST["username"]);
    }

    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Voer een wachtwoord in.";
    } else{
        $password = trim($_POST["password"]);
    }

    // Validate credentials
    if(empty($username_err) && empty($password_err)){
        // Prepare a select statement
        $sql = "SELECT id, username, password FROM users WHERE username = ?";

        if($stmt = mysqli_prepare($db, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            // Set parameters
            $param_username = $username;

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);

                // Check if username exists, if yes then verify password
                if(mysqli_stmt_num_rows($stmt) == 1){
                    // Bind result variables
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashed_password)){
                            // Password is correct, so start a new session
                            session_start();

                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;

                            // Set a cookie with salted user_id and store the id in the db.
                            $newId = hash_hmac('sha256', $_SESSION["id"], '12345');
                            setcookie("user_id", $newId, time() + (86400 * 30), "/");
                            $sql = "UPDATE users SET token = '$newId' WHERE id = '$id'";
                            mysqli_query($db, $sql);

                            // Redirect user to welcome page
                            header("location: index.php");
                        } else{
                            // Password is not valid, display a generic error message
                            $login_err = "Ongeldige gebruikersnaam of wachtwoord.";
                        }
                    }
                } else{
                    // Username doesn't exist, display a generic error message
                    $login_err = "Ongeldige gebruikersnaam of wachtwoord.";
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Close connection
    mysqli_close($db);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
<body class="">
    <header id="header" class="flex justify-between items-center px-7 py-5 mt-10 w-full text-3xl">
        <p class="font-semibold">Inloggen</p>
    </header>
    <div class="flex flex-col px-7 mt-10">

        <?php
        if(!empty($login_err)){
            echo '<div class="alert alert-danger">' . $login_err . '</div>';
        }
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="flex flex-col">
                <label class="mb-2 ml-2 text-xl font-bold">Gebruikersnaam</label>
                <input type="text" name="username" placeholder="Typ hier je gebruikersnaam in" class="bg-white block items-center py-2 px-2 w-full rounded-full shadow mb-5 <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                <span class="invalid-feedback"><?php echo $username_err; ?></span>
            </div>
            <div class="flex flex-col">
                <label class="mb-2 ml-2 text-xl font-bold">Wachtwoord</label>
                <input type="password" name="password" placeholder="Typ hier je wachtwoord in" class="bg-white block items-center py-2 px-2 w-full rounded-full shadow mb-5 <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <div class="fixed bottom-0 flex items-center justify-between w-full max-w-md font-semibold bg-[#f9f9fd] z-10 -ml-7">
                <a href="register.php" class="flex justify-center py-5 w-1/3 text-xl text-primary">Registreer</a>
                <input type="submit" class="flex justify-center py-5 w-2/3 text-xl text-white rounded-tl-full bg-primary" value="Login">
            </div>
        </form>
    </div>
    <div class="flex flex-col overflow-scroll px-7 h-[45%]">
        <h3 class="mt-10 text-xl">Update 25-05-2022</h3>
        <ul class="list-disc list-inside">
            <li>Fix dat je niet zo vaak opnieuw hoeft in te loggen.</li>
            <li>Minder update nodig meldingen (hopelijk) als je de app op je homescreen hebt staan.</li>
        </ul>
        <h3 class="mt-6 text-xl">Handleiding:</h3>
        <ol class="list-decimal list-inside">
            <li>Voeg de app toe aan je homescreen.</li>
            <li>Klik op de app in je homescreen.</li>
            <li>Log in of maak een gratis account aan.</li>
            <li>Zet items in je boodschappen lijst.</li>
            <li>Maak eventueel short lists aan voor vaste lijsten die je vaak nodig hebt.</li>
            <li>Haal je boodschappen en vergeet nooit meer iets met de hulp van <span class="">Lijs. :)</span></li>
        </ol>
    </div>
</body>
</html>
