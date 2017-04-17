<?php
session_start();
/* 
inputs 
 - user
 - Opened Chatrooms
*/

/******************
LOAD CHABIT CLASSES
******************/
 require_once(__DIR__.'./Classes/PSR4Loader.php');

// instantiate the loader
$loader = new \chabit\Psr4AutoloaderClass;

// register the autoloader
 $loader->register();

// register the base directories for the namespace prefix
$loader->addNamespace('chabit', __DIR__.'/Classes/');





?>