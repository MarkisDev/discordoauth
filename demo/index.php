<?php

/* Home Page
 * Demo Working Script 
 * @author Markis
 */
 
 // Let's show errors
 error_reporting(E_ALL);
 ini_set('display_errors', 1);
 
 // Let's include our Oauth script and functions script
 require "functions.php";
 require "discord.php";

 // All values are being stored in SESSION. Check session.txt for the variable names and the values they contain.
 ?>
 <html>
     <title> Demo Oauth </title>
     <h2 style="color : red;">A Simple Working Demo of the Script </h2>
     <h1> User Details :</h1>
     <p> Name : <?php echo $_SESSION['username'] . '#'. $_SESSION['discrim']; ?></p>
     <p> ID : <?php echo $_SESSION['user_id']; ?></p>
     <p> Profile Picture : <img src="https://cdn.discordapp.com/avatars/<?php $extention = is_animated($_SESSION['user_avatar']); echo $_SESSION['user_id'] . "/" . $_SESSION['user_avatar'] . $extention; ?>" /></p>
     <p> Response : <?php echo json_encode($_SESSION['response']); ?></p>
     <br />
     <h1> User Guilds :</h1>
     <p> <?php echo json_encode($_SESSION['guilds']); ?></p>
     <h3 style="color:purple;"><a href="<?php echo url("YOUR CLIENT ID", "YOUR REDIRECT URI", "YOUR SCOPE(S) SEPARATED BY A SPACE"); ?>">Oauth Link </a></h3>
     <a href="logout.php"><p>Logout</p></a>
 </html>
