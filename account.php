<?php

require_once './functions.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/output.css">
    <script src="./scripts/functions.js"></script>
    <title>Account instellingen</title>
    <link rel="icon" href="images/favicon.ico">
    <link rel="apple-touch-icon" href="images/apple-touch-icon.png">
</head>
<body class="bg-[#f9f9fd] max-h-screen">
    <header id="header" class="flex flex-col w-full py-5 text-3xl px-7 ">
        <a href="index.php">
            <span class="mb-3 text-primary">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 mb-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 15l-3-3m0 0l3-3m-3 3h8M3 12a9 9 0 1118 0 9 9 0 01-18 0z" />
                </svg>
            </span>
        </a>
        <p class="font-semibold">Account instellingen</p>
    </header>
    <main>
        <div class="flex flex-col w-full px-7">
            <div class="flex flex-col w-full mb-5">
                <a href="cleanOldItems.php" class="flex items-center w-full px-2 py-2 text-left text-white rounded-full bg-primary">
                    Delete done items
                </a>
            </div>
    </main>
    <footer id="footer" class="fixed bottom-0 flex items-center justify-end w-full ">
        <a href="logout.php" class="flex justify-center w-1/2 py-5 text-xl text-white rounded-tl-full bg-primary">Log uit</a>
    </footer>
</body>
</html>
