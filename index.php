<?php

/* Home Page
 * Demo Working Script 
 * @owner : Rijuth Menon A.K.A Markis
 * @copyright : https://rijuthmenon.me | https://markis.pw
 * #MarkisHome ------> CUSTOM CODES <---------
 */
 
 // Let's show errors
 error_reporting(E_ALL);
 ini_set('display_errors', 1);
 
 // Let's include our Oauth script and functions script
 require "discord.php";
 require "functions.php";
 
 ?>
 <html>
     <title> Demo Oauth </title>
     <h2 style="color : red;">A Simple Working Demo of the Script </h2>
     <h1> User Details :</h1>
     <p> Name : <? echo $_SESSION['username'] . '#'. $_SESSION['discrim']; ?></p>
     <p> ID : <? echo $_SESSION['user_id']; ?></p>
     <p> Profile Picture : <img src="https://cdn.discordapp.com/avatars/<?echo $_SESSION['user_id']; ?>/<? echo $_SESSION['user_avatar']; $extention = is_animated($_SESSION['user_avatar']); echo $extention; ?>" /></p>
     <p> Response : <? echo json_encode($_SESSION['response']); ?></p>
     <br />
     <h1> User Guilds :</h1>
     <p> <? echo json_encode($_SESSION['guilds']); ?></p>
     <h3 style="color:purple;"><a href="<? echo url("YOUR CLIENT ID", "YOUR REDIRCT URI", "YOUR SCOPE(S) SEPARATED BY A SPACE"); ?>">Oauth Link </a></h3>
 </html>
