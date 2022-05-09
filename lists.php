<?php

require_once './functions.php';

//Initialize the session
session_start();

//Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

//Get lists by user id
$lists = mysqli_query($db, "SELECT * FROM `lists` WHERE `user_id` = $_SESSION[id]");

//initialize errors variable
$errors = "";

//Add new list if submit button is clicked
if (isset($_POST['newList'])) {
    if (empty($_POST['newItem'])) {
        $errors = "Je moet een item invullen.";
    }else{
    addList($_POST['newList'], $_SESSION['id']);
    }
}

//Delete list if delete button is clicked
if (isset($_GET['deleteList'])) {
    deleteList($_GET['deleteList'], $_SESSION['id']);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/output.css">
    <script src="./scripts/functions.js"></script>
    <title>Short lists</title>
    <link rel="icon" href="images/favicon.ico">
    <link rel="apple-touch-icon" href="images/apple-touch-icon.png">
</head>
<body class="bg-[#f9f9fd] max-h-screen">
    <div id="overlay" onClick="closeListModal()" class="hidden h-screen w-screen fixed z-10 bg-opacity-80 bg-[#c8c8dd]">
    </div>
    <header id="header" class="flex flex-col w-full py-5 text-3xl px-7 ">
        <a href="index.php">
            <span class="mb-3 text-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 mb-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 15l-3-3m0 0l3-3m-3 3h8M3 12a9 9 0 1118 0 9 9 0 01-18 0z" />
                </svg>
            </span>
        </a>
        <p class="font-semibold">Short lists</p>
    </header>

    <main id="main" class="flex flex-col w-full px-7">

        <!-- Add list button to show modal  -->
        <div class="w-full mb-5">
            <button onClick="openListModal()" class="flex items-center w-full px-2 py-2 text-left border border-dashed rounded-full border-primary text-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 ml-1 mr-2" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd" />
                </svg>
                Voeg nieuwe lijst toe
            </button>
            <?php if (isset($errors)) { ?>
                <p><?php echo $errors; ?></p>
            <?php } ?>
        </div>

        <!-- Lists -->
        <?php foreach ($lists as $list) { ?>
            <div class="flex flex-col w-full px-2 py-3 mb-5 bg-white shadow rounded-3xl">
                <div class="flex items-center justify-between w-full cursor-pointer" onClick="showList(<?php echo $list['id']?>)">
                    <div class="flex items-center">
                        <span class="ml-3"><?php echo $list['name'] ?></span>
                        <span class="flex items-center justify-center w-5 h-5 ml-2 text-xs text-white rounded-full bg-primary">
                            <?php
                            $items = $list['items'];
                            $listItems = unserialize($items);
                            $itemCount = count($listItems);
                            echo $itemCount;
                            ?>
                        </span>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg"
                    id="listOpenID<?php echo $list['id']?>"
                    class="w-6 h-6 mr-3 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
                <div id="listID<?php echo $list['id']?>" class="flex-col text-[#727c81] px-3 pt-1 space-y-2 hidden">
                    <?php
                    $items = $list['items'];
                    $listItems = unserialize($items);
                    foreach ($listItems as $item) {
                        echo "<span>$item</span>";
                    } ?>
                    <div class="flex items-center justify-between w-full">
                        <a href="index.php?addedList=<?php echo $list['id']?>">
                            <button class="flex items-center justify-center px-5 py-1 text-white rounded-full bg-primary">Zet op boodschappen lijst</button>
                        </a>
                        <div class="flex items-center text-primary">
                            <a href="editList.php?editList=<?php echo $list['id']?>">
                                <span class="">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </span>
                            </a>
                            <a href="lists.php?deleteList=<?php echo $list['id']?>">
                                <span class="">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </main>
    <!-- Add list modal -->
    <div id="addListModal" class="fixed bottom-0 bg-[#f9f9fd] w-full rounded-t-xl hidden z-20 flex-col">
        <div class="flex items-center justify-between mt-5 px-7">
            <span class="ml-4 text-xl font-semibold">Voeg item toe</span>
            <span class="mr-5 text-primary">
                <svg onClick="closeListModal()" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </span>
        </div>
        <div class="flex w-full mt-5 mb-8 mx-7">
            <form action="lists.php" method="post">
                <input type="text" name="newList" id="newList" placeholder="Typ hier de naam van je lijst" maxlength="55"
                class="bg-white block items-center py-2 px-2 w-[350px] rounded-full shadow mb-5 placeholder:pl-2">
                <input type="submit" name="submit" value="Add" class="hidden w-0 px-3 py-1 text-sm rounded-md">
            </form>
        </div>
    </div>
</body>
</html>
