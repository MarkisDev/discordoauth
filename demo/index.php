<?php

/* Home Page
 * The home page of the working demo of oauth2 script.
 * @author : MarkisDev
 * @copyright : https://markis.dev
 */
 
#Enable this to have Error Logging
#Click on OAuth Link to log in
# Enabling error display
#error_reporting(E_ALL);
#ini_set('display_errors', 1);
 

# Including all the required scripts for demo
require __DIR__ . "/functions.php";
require __DIR__ . "/discord.php";

# ALL VALUES ARE STORED IN SESSION!
# RUN `echo var_export([$_SESSION]);` TO DISPLAY ALL THE VARIABLE NAMES AND VALUES.
# FEEL FREE TO JOIN MY SERVER FOR ANY QUERIES - https://join.markis.dev

?>

 <html>
     <title>Demo - Discord Oauth</title>
     <h1>A Simple Working Demo of the Script </h2>
     <h2 style="color:red; font-weight:900;"> LOGIN WITH THE LINK BELOW TO SEE IT WORK! </h3>
     <h1> User Details :</h1>
     <p> Name : <?php echo $_SESSION['username'] . '#'. $_SESSION['discrim']; ?></p>
     <p> ID : <?php echo $_SESSION['user_id']; ?></p>
     <p> Profile Picture : <img src="https://cdn.discordapp.com/avatars/<?php $extention = is_animated($_SESSION['user_avatar']); echo $_SESSION['user_id'] . "/" . $_SESSION['user_avatar'] . $extention; ?>" /></p>
     <p> Response : <?php echo json_encode($_SESSION['user']); ?></p>
     <br />
     <h1> User Guilds :</h1>
     <p> <?php echo json_encode($_SESSION['guilds']); ?></p>
     <h3 style="display:inline;"><a href="
     <?php echo url("378191060242792449", "http://127.0.0.1/demo/login.php", "identify guilds"); ?>
     ">OAUTH LINK </a></h3>
     <?php
     # Displaying logout url only if user is logged in
     if(isset($_SESSION['user']))
     {
         echo '<h3 style="color:red; display:inline;"><a style="color:red; padding-left:10px;" href="logout.php">LOGOUT</a></h3>';
     }
     ?>
 </html>
