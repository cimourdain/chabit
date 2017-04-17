<?php

include_once('./main.php');

/*$_GET['target_userhash'] = '5f1ca74b80c00926fd0bd21821d616f0';
$_GET['message'] = 'Bonjour';*/

// instantiate chabit object
$chat = new chabit\chabit();

$chat -> PostNexInPrivateConv($_GET['target_userhash'], $_GET['message']);

//echo $chat -> getObserver() :: getLogs(3);
?>