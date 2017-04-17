<?php


include_once('./main.php');

// instantiate chabit object
$chat = new chabit\chabit();

$chat -> updateUserInOnlineUsers();

//echo $chat -> getObserver() :: getLogs(3);
?>