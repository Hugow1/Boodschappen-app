<?php

// Include config file
require_once "./functions.php";

// Initialize the session
session_start();

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
<body class="bg-[#f9f9fd] max-h-screen">
    <div id="overlay" onClick="closeModal()" class="hidden h-screen w-screen fixed z-10 bg-opacity-80 bg-[#c8c8dd]">
    </div>
    <header id="header" class="flex items-center justify-between w-full py-5 mt-10 text-3xl px-7">
        <p class="font-semibold">Inloggen</p>
    </header>
    <div class="flex flex-col mt-10 px-7">

        <?php
        if(!empty($login_err)){
            echo '<div class="alert alert-danger">' . $login_err . '</div>';
        }
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="flex flex-col">
                <label class="mb-2 ml-2 text-xl font-bold">Gebruikersnaam</label>
                <input type="text" name="username" placeholder="Typ hier je gebruikersnaam in" class="bg-white block items-center py-2 px-2 w-[350px] rounded-full shadow mb-5 <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                <span class="invalid-feedback"><?php echo $username_err; ?></span>
            </div>
            <div class="flex flex-col">
                <label class="mb-2 ml-2 text-xl font-bold">Wachtwoord</label>
                <input type="password" name="password" placeholder="Typ hier je wachtwoord in" class="bg-white block items-center py-2 px-2 w-[350px] rounded-full shadow mb-5 placeholder:pl-2 <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <div class="fixed bottom-0 flex items-center justify-between w-full font-semibold">
                <a href="register.php" class="flex justify-center w-1/3 py-5 text-xl text-primary">Registreer</a>
                <input type="submit" class="flex justify-center w-2/3 py-5 text-xl text-white rounded-tl-full bg-primary" value="Login">
            </div>
        </form>
    </div>
</body>
</html>
