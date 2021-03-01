<?php

/* Discord Oauth v.4.1
 * Demo Login Script
 * @author : MarkisDev
 * @copyright : https://markis.dev
 */

# IMPORTANT READ THIS:
# - This requires 'guilds.join' scope to be active in url() function in index.php
# - The below function requries the client to be a BOT application with CREATE_INSTANT_INVITE permissions to be a member in the server.
# - Set the `$bot_token` to your bot token if you want to use guilds.join scope in the init() function
# - The below function HAS to be called after get_user() as it adds the user who has logged in
# - The bot DOES NOT have to be online, just a member in the server.
# - Uncomment line 35 to enable the function

# FEEL FREE TO JOIN MY SERVER FOR ANY QUERIES - https://join.markis.dev

# Enabling error display
error_reporting(E_ALL);
ini_set('display_errors', 1);
 
# Including all the required scripts for demo
require __DIR__ . "/discord.php";
require __DIR__ . "/functions.php";
require "../config.php";
 
# Initializing all the required values for the script to work
init($redirect_url, $client_id, $secret_id, $bot_token);
 
# Fetching user details | (identify scope)
get_user();

# Adding user to guild | (guilds.join scope)
# join_guild('SERVER_ID_HERE');

# Fetching user guild details | (guilds scope)
$_SESSION['guilds'] = get_guilds();
 
# Redirecting to home page once all data has been fetched
redirect("../index.php");

?>
 
 
