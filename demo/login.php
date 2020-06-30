<?php

/* Discord Oauth v.4.0
 * Demo Login Script
 * @author : MarkisDev
 * @copyright : https://markis.dev
 */
 
# Enabling error display
error_reporting(E_ALL);
ini_set('display_errors', 1);
 
# Including all the required scripts for demo
require __DIR__ . "/discord.php";
require __DIR__ . "/functions.php";
 
# Initializing all the required values for the script to work
init ("http://127.0.0.1/demo/login.php", "378191060242792449", "jGsRpLN32NObExT15MSu_Qb9jZ_bJ8IQ");
 
# Fetching user details | (identify scope)
get_user();
 
# Fetching user guild details | (guilds scope)
$_SESSION['guilds'] = get_guilds();
 
# Redirecting to home page once all data has been fetched
redirect("index.php");

?>
 
 
