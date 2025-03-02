<?php

// require_once 'environment.php';

//db credentials
// Get database credentials from environment variables
define('DB_SERVER', getenv('db_host') ?: $_ENV['db_host']);
define('DB_USERNAME', getenv('db_user') ?: $_ENV['db_user']);
define('DB_PASSWORD', getenv('db_pass') ?: $_ENV['db_pass']);
define('DB_NAME', getenv('db_name') ?: $_ENV['db_name']);

//Setup db connection
$db = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

//Add new item to db
function addItem($item, $user_id)
{
    global $db;
    $item = $_POST['newItem'];
    $sql = "INSERT INTO `list`(`item`, `status`, `user_id`) VALUES ('$item', '0', $user_id)";
    mysqli_query($db, $sql);
    header('location: index.php');
}

//Update item status in the db
function updateItemStatus($id)
{
    global $db;
    $id = $_GET['update'];
    $sql = "UPDATE list SET status = '1' WHERE id = '$id'";
    mysqli_query($db, $sql);
    header('location: index.php');
}

//Add shortlist items to the main list
function addListItems($list_id, $user_id)
{
    global $db;
    $list_id = $_GET['addedList'];
    $items = mysqli_query($db, "SELECT * FROM `lists` WHERE `id` = $list_id AND `user_id` = $user_id");
    foreach ($items as $item) {
        $items = $item['items'];
        $newItems = unserialize($items);
    }
    foreach ($newItems as $item) {
        $sql = "INSERT INTO `list`(`item`, `status`, `user_id`) VALUES ('$item', '0', '$user_id')";
        mysqli_query($db, $sql);
    }
    header('location: index.php');
}

//Add new list to db
function addList($list, $user_id)
{
    global $db;
    $list = $_POST['newList'];
    $emptyList = serialize([]);
    $sql = "INSERT INTO `lists`(`name`, `user_id`, `items`) VALUES ('$list', $user_id, '$emptyList')";
    mysqli_query($db, $sql);
    $sqlGetID = "SELECT * FROM `lists` WHERE `name` = '$list' AND `user_id` = $user_id";
    $listID = mysqli_query($db, $sqlGetID);
    foreach ($listID as $id) {
        $listID = $id['id'];
    }
    header('location: editList.php?editList=' . $listID);
}

//Delete list from db
function deleteList($listID, $user_id)
{
    global $db;
    $listID = $_GET['deleteList'];
    $sql = "DELETE FROM `lists` WHERE `id` = '$listID' AND `user_id` = '$user_id'";
    mysqli_query($db, $sql);
    header('location: lists.php');
}

//add query parameters to the url to fix caching issues for non development environments
function auto_version($file) {
    if ($_ENV['environment'] == 'development') {
        return $file;
    } else {
        $file = "/" . $file;
        $mtime = filemtime($_SERVER['DOCUMENT_ROOT'] . $file);
        return sprintf("%s?v=%d", $file, $mtime);
    }
}
