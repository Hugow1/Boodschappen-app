<?php

require_once './functions.php';

// Initialize the session
session_start();

// Check if the user is logged in, if not then redirect him to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

// Get the list for the logged in user
if (isset($_GET['editList'])) {
    $listID = $_GET['editList'];
    $lists = mysqli_query($db, "SELECT * FROM `lists` WHERE `id` = $listID AND `user_id` = $_SESSION[id]");
} else {
    echo "No list found in";
}

// Format variables for the list
foreach ($lists as $list) {
    $listID = $list["id"];
    $listName = $list['name'];
    $items = unserialize($list['items']);
}

// Delete an item if it is clicked
if (isset($_GET['deleteItem'])) {
    unset($items[$_GET['deleteItem']]);
    $newItems = serialize($items);
    $sql = "UPDATE `lists` SET `items` = '$newItems' WHERE `id` = $listID";
    mysqli_query($db, $sql);
    header('location: editList.php?editList=' . $listID);
}

// Add an item to the list
if (isset($_POST['newItem'])) {
    if (empty($_POST['newItem'])) {
        $errors = "Je moet een item invullen.";
    } else {
    $item = $_POST['newItem'];
    $items[] = $item;
    $newItems = serialize($items);
    $sql = "UPDATE `lists` SET `items` = '$newItems' WHERE `id` = $listID";
    mysqli_query($db, $sql);
    header('location: editList.php?editList=' . $listID);
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php echo '<link rel="stylesheet" href="' . auto_version('./styles/output.css') . '" type="text/css">'; ?>
    <?php echo '<script src="' . auto_version('./scripts/functions.js') . '"></script>'; ?>
    <title>Bewerk je lijst</title>
    <link rel="icon" href="images/favicon.ico">
    <link rel="apple-touch-icon" href="images/apple-touch-icon.png">
</head>
<body class="bg-[#f9f9fd] max-h-screen">
    <div id="overlay" onClick="closeModal()" class="hidden h-screen w-screen fixed z-10 bg-opacity-80 bg-[#c8c8dd]">
    </div>
    <header id="header" class="flex flex-col w-full py-5 text-3xl px-7">
        <a href="lists.php">
            <span class=" text-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 15l-3-3m0 0l3-3m-3 3h8M3 12a9 9 0 1118 0 9 9 0 01-18 0z" />
                </svg>
            </span>
        </a>
        <div class="flex items-center mt-5">
            <p class="font-semibold">
                <?php echo $listName ?>
            </p>
            <span class="flex items-center justify-center w-8 h-8 ml-2 text-base text-white rounded-full bg-primary">
                <?php
                $itemCount = count($items);
                echo $itemCount;
                ?>
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

        <!-- List items -->
        <?php
            foreach ($items as $id => $item) { ?>
                <div class="flex items-center justify-between w-full px-2 py-2 mb-5 bg-white rounded-full shadow">
                    <span class="ml-2"><?php echo $item ?></span>
                    <a href="editList.php?deleteItem=<?php echo $id ?>&editList=<?php echo $listID ?>" class=" text-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </a>
                </div>
        <?php } ?>
    </main>


    <!-- Add item to list modal -->
    <div id="addItemsModal" class="fixed bottom-0 bg-[#f9f9fd] w-full rounded-t-xl hidden z-20">
        <div class="flex mt-5 px-7">
            <span class="text-xl font-semibold">Voeg item toe aan je lijst</span>
        </div>
        <div class="flex w-full mt-5 mb-8 mx-7">
            <form action="editList.php?editList=<?php echo $listID ?>" method="post">
            <?php if (isset($errors)) { ?>
                <p><?php echo $errors; ?></p>
            <?php } ?>
                <input type="text" name="newItem" id="newItem" placeholder="Voeg je item to aan de lijst" maxlength="55"
                class="bg-white block items-center py-2 px-2 w-[350px] rounded-full shadow mb-5 placeholder:pl-2">
                <input type="submit" name="submit" value="Add" class="hidden w-0 px-3 py-1 text-sm rounded-md">
            </form>
        </div>
    </div>

</body>
</html>
