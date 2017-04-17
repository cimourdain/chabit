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
    <chat>
      <div class="chatbox" id="chabit_chat_main">
        <div class="subchatbox" id="chabit_online_users_list">
          <div class="subchatboxheader">
            <div class="title">Online Users</div>
            <div class="cb_buttons">
              <span class="minimise">-</span>
              <span class="close">x</span>
            </div>
          </div><!--subchatboxheader-->
          <div class="subchatboxcontent">
            <ul class="user_list">
              <li class="active alert">Active user</li>
              <li class="afk">AFK user</li>
              <li class="inactive">Inactive user</li>
            </ul>
          </div><!--subchatboxcontent-->
          <div class="subchatboxfooter">
              Login : <input type="text" id="input_login" /><br />
              Magic Pass: <input type="text" id="input_pass" /><br />
              <button id="login_btn">Login</button>
          </div><!--subchatboxfooter-->
        </div><!--subchatbox-->

        <div class="subchatbox" id="chabit_chatrooms_list">
          <div class="subchatboxheader">
            <div class="title">Chatrooms</div>
            <div class="cb_buttons">
              <span class="minimise">-</span>
              <span class="close">x</span>
            </div>
          </div><!--subchatboxheader-->
          <div class="subchatboxcontent">
            <ul class="chatroom_list">
              <li class="active">#Chatroom1</li>
              <li class="afk">#Chatroom2</li>
              <li class="inactive">#Chatroom3</li>
            </ul>
          </div><!--subchatboxcontent-->
          <div class="subchatboxfooter">
            New: <input type="text" /><br />
            <button id="new_chatbox_btn">Create</button>
          </div><!--subchatboxfooter-->
        </div><!--subchatbox-->
      </div><!--chatbox-->

      <div class="chatbox" id="chabit_chatrooms">
        <div class="subchatbox chatroom" name="chatroom1">
          <div class="subchatboxheader">
            <div class="title">Chatroom1</div>
            <div class="cb_buttons">
              <span class="minimise">-</span>
              <span class="close">x</span>
            </div>
          </div><!--subchatboxheader-->
          <div class="subchatboxcontent">
            <div class="chat-messages">
              <div class="chat-message">
                <div class="chat-message-username">user1</div>
                <div class="chat-message-text">Bonjour</div>
              </div>
              <div class="chat-message">Bonsoir ceci est un très long message de la mort</div>
              <div class="chat-message user-message">Je suis un message de l'utilisateur</div>
              <div class="chat-message">Bonsoir</div>
              <div class="chat-message">Bonsoir ceci est un très long message de la mort</div>
              <div class="chat-message">Bonsoir ceci est un très long message de la mort</div>
              <div class="chat-message">Bonsoir ceci est un très long message de la mort</div>
              <div class="chat-message">Bonsoir ceci est un très long message de la mort</div>
              <div class="chat-message">Bonsoir ceci est un très long message de la mort</div>
            </div>
          </div><!--subchatboxcontent-->
          <div class="subchatboxfooter">
            <input type="text" class="post_text" target="chatroom1"/>
          </div><!--subchatboxfooter-->
        </div>

      </div><!--chatbox-->

      <div class="chatbox" id="chabit_pms">
      </div><!--chatbox-->

    </chat>
    <script type="text/javascript" src="js/chabit.js"></script>

    <script>
    
    </script>
  </body>


</html>