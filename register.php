<?php
// Include config file
require_once "./functions.php";

// Define variables and initialize with empty values
$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))){
        $username_err = "Gebruikersnaam mag alleen bestaan uit letters, cijfers, en underscores.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE username = ?";

        if($stmt = mysqli_prepare($db, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            // Set parameters
            $param_username = trim($_POST["username"]);

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // store result
                mysqli_stmt_store_result($stmt);

                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "Deze gebruikersnaam is al in gebruik.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }

    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Wachtwoord moet minstens 6 karakters lang zijn.";
    } else{
        $password = trim($_POST["password"]);
    }

    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Bevestig uw wachtwoord.";
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Wachtwoorden komen niet overeen.";
        }
    }

    // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){

        // Prepare an insert statement
        $sql = "INSERT INTO users (username, password) VALUES (?, ?)";

        if($stmt = mysqli_prepare($db, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);

            // Set parameters
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: login.php");
            } else{
                echo "Oops! Something went wrong. Please try again later.";
                echo mysqli_error($db);
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
    <title>Registreer</title>
    <?php echo '<link rel="stylesheet" href="' . auto_version('/styles/output.css') . '" type="text/css">'; ?>
    <?php echo '<script src="' . auto_version('/scripts/functions.js') . '"></script>'; ?>
    <link rel="icon" href="images/favicon.ico">
    <link rel="apple-touch-icon" href="images/apple-touch-icon.png">
</head>
<body class="bg-[#f9f9fd] max-h-screen">
    <div id="overlay" onClick="closeModal()" class="hidden h-screen w-screen fixed z-10 bg-opacity-80 bg-[#c8c8dd]">
    </div>
    <header id="header" class="flex items-center justify-between w-full py-5 mt-10 text-3xl px-7">
        <p class="font-semibold">Registreren</p>
    </header>
    <div class="flex flex-col mt-10 px-7">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="flex flex-col">
                <label class="mb-2 ml-2 text-xl font-bold">Gebruikersnaam</label>
                <input type="text" name="username" placeholder="Typ hier je gebruikersnaam in" class="bg-white block items-center py-2 px-2 w-[350px] rounded-full shadow mb-5 <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                <span class="invalid-feedback"><?php echo $username_err; ?></span>
            </div>
            <div class="flex flex-col">
                <label class="mb-2 ml-2 text-xl font-bold">Wachtwoord</label>
                <input type="password" name="password" placeholder="Typ hier je wachtwoord in" class="bg-white block items-center py-2 px-2 w-[350px] rounded-full shadow mb-5 placeholder:pl-2 <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <div class="flex flex-col">
                <label class="mb-2 ml-2 text-xl font-bold">Wachtwoord bevestigen</label>
                <input type="password" name="confirm_password" placeholder="Typ hier nog een keer je wachtwoord in" class="bg-white block items-center py-2 px-2 w-[350px] rounded-full shadow mb-5 placeholder:pl-2 <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>">
                <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="fixed bottom-0 flex items-center justify-between w-full font-semibold">
                <a href="login.php" class="flex justify-center w-1/3 py-5 text-xl text-primary">Login</a>
                <input type="submit" class="flex justify-center w-2/3 py-5 text-xl text-white rounded-tl-full bg-primary" value="Registreer">
            </div>
        </form>
    </div>
</body>
</html>
