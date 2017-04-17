<?php 
session_start();

//$_SESSION['username'] = "toto";
?>
<!doctype html>
<html class="no-js" lang="fr">
  <head>
    <meta charset="UTF-8">
    <!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge"><![endif]-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <link rel="icon" href="img/nyan.ico" />
    <title>Chab.it</title>
    <link rel="stylesheet" href="css/styles.css" media="all">
    <link rel="stylesheet" href="css/chat.css" media="all">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
  </head>
  <body>
    <h1>Chab.it</h1>
    <chat id="chabit">
    </chat>
    <script type="text/javascript" src="js/CommonFunctions.js"></script>
    <script type="text/javascript" src="js/chabit.js"></script>

    <script >
      chat = new chabit();
      //setInterval(function(){ chat.updateChat()}, 5000);
    </script>
  </body>


</html>