<?php
require_once './functions.php';

// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

// Get the lists for the logged in user
$boodschappen = mysqli_query($db, "SELECT * FROM `list` WHERE `status` = 0 AND `user_id` = $_SESSION[id]");
$doneItems = mysqli_query($db, "SELECT * FROM `list` WHERE `status` = 1 AND `user_id` = $_SESSION[id]");

// initialize errors variable
$errors = "";

// insert an item if submit button is clicked
if (isset($_POST['newItem'])) {
    if (empty($_POST['newItem'])) {
        $errors = "Je moet een item invullen.";
    }else{
    addItem($_POST['newItem'], $_SESSION['id']);
    }
}

//update an item if it is clicked
if (isset($_GET['update'])) {
    updateItemStatus($_GET['update'], $_SESSION['id']);
}

//add list items id added from the list page
if (isset($_GET['addedList'])) {
    addListItems($_GET['addedList'], $_SESSION['id']);
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php echo '<link rel="stylesheet" href="' . auto_version('/styles/output.css') . '" type="text/css">'; ?>
    <?php echo '<script src="' . auto_version('/scripts/functions.js') . '"></script>'; ?>
    <link rel="manifest" href="/manifest.json">
    <title>Je lijst</title>
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
    <header id="header" class="flex items-center justify-between w-full py-5 text-3xl px-7">
        <p class="font-semibold">Boodschappen</p>
        <div class="flex space-x-2">
            <span class="text-primary">
                <button onClick="reloadPage()">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 p-1 border-2 rounded-full" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                </button>
            </span>
            <span class="text-primary">
                <a href="account.php">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 p-1 border-2 rounded-full" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </a>
            </span>
        </div>

    </header>
    <main id="main" class="flex flex-col w-full overflow-scroll px-7 h-[75vh]">

        <!-- Add item button to show modal  -->
        <div class="w-full mb-5">
            <button onClick="openModal()" class="flex items-center w-full px-2 py-2 text-left border border-dashed rounded-full border-primary text-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 ml-1 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
                </svg>
                Voeg nieuw item toe
            </button>
            <?php if (isset($errors)) { ?>
                <p><?php echo $errors; ?></p>
            <?php } ?>
        </div>

        <!-- todo section -->
        <?php
            foreach ($boodschappen as $boodschap) { ?>
                <a href="index.php?update=<?php echo $boodschap['id'] ?>" class="flex items-center w-full px-2 py-2 mb-5 bg-white rounded-full shadow">
                    <span class="w-7 h-7 bg-[#f9f9fd] rounded-full mr-2 ml-2 shadow-inner"></span>
                    <span class="ml-2"><?php echo $boodschap['item'] ?></span>
                </a>
        <?php } ?>

        <!-- done section -->
        <?php
            foreach ($doneItems as $boodschap) { ?>
                <div class="flex items-center w-full px-2 py-2 mb-5 bg-white rounded-full shadow opacity-50">
                    <span class="w-7 h-7 bg-[#f9f9fd] rounded-full mr-2 ml-2 shadow-inner text-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                    </span>
                    <span class="ml-2 line-through"><?php echo $boodschap['item'] ?></span>
                </div>
        <?php } ?>

    </main>
    <footer id="footer" class="fixed bottom-0 flex items-center justify-end w-full ">
        <a href="lists.php" class="flex justify-center w-1/2 py-5 text-xl text-white rounded-tl-full bg-primary">Bekijk short list</a>
    </footer>



    <!-- Add items -->
    <div id="addItemsModal" class="fixed bottom-0 bg-[#f9f9fd] w-full rounded-t-xl hidden z-20">
        <div class="flex items-center justify-between mt-5 px-7">
            <span class="ml-4 text-xl font-semibold">Voeg item toe</span>
            <span class="mr-5 text-primary">
                <svg onClick="closeModal()" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </span>
        </div>
        <div class="flex w-full mt-5 mb-8 mx-7">
            <form action="index.php" method="post">
                <input type="text" name="newItem" id="newItem" placeholder="Typ hier je item" maxlength="55"
                class="bg-white block items-center py-2 px-2 w-[350px] rounded-full shadow mb-5 placeholder:pl-2 ">
                <input type="submit" name="submit" value="Add" class="hidden w-0 px-3 py-1 text-sm rounded-md">
            </form>
        </div>
    </div>
</body>
</html>
