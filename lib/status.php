<?php

include_once('./main.php');

/******************
Create chabit object
******************/

// instantiate chabit object
$chat = new chabit\chabit();

/*
return json with
- Online users
- Chatroom list
- Opened Chatroom updates
- New PM 
*/


echo $chat -> getStatusJSON();

//echo $chat -> getObserver() :: getLogs(3);
?>