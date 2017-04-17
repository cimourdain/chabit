<?php

include_once('./main.php');

/*$_GET['userhash'] = '5f1ca74b80c00926fd0bd21821d616f0';*/


// instantiate chabit object
$chat = new chabit\chabit();

$chat -> newPrivateConv($_GET['userhash']);

echo $chat -> getObserver() :: getLogs(3);
?>