<?php


include_once('./main.php');

// instantiate chabit object
$chat = new chabit\chabit();

//remove user from online users
$chat -> getOnlineUsers() -> removeUser($chat -> getUser() -> getUserName(), $chat -> getUser() -> getUserHash());

//reset user object
$chat -> getUser() -> logout();

//echo $chat -> getObserver() :: getLogs(3);
?>