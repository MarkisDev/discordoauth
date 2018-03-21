<?php

/* Discord Oauth v.3.0
 * Demo Login Script
 * @owner : Rijuth Menon A.K.A Markis
 * @copyright : https://rijuthmenon.me | https://markis.pw
 * #MarkisOauth (+) CUSTOM CODES (+) [Let's get series]
 */
 
 // Let's show errors
 error_reporting(E_ALL);
 ini_set('display_errors', 1);
 
 // Let's include our Oauth script and functions
 require "discord.php";
 require "functions.php";
 
 // Let's initialize the required variables for Oauth via script
 // ** LEAVE THE FIRST PARAMETER OF init() EMPTY, IT IS FOR A CODE THAT WILL BE RECEIVED LATER ON AUTOMATICALLY
 init("", "YOUR REDIRECT URI HERE", "YOUR CLIENT ID HERE", "YOUR CLIENT SECRET ID HERE");
 
 // Let's get users details via script
 get_user();
 
 // Let's get users guilds via script
 $_SESSION['guilds'] = get_guilds();
 
 // Let's redirect to home page when all data has been collected
  redirect("YOUR HOME PAGE URL HERE");
  ?>
 
 
