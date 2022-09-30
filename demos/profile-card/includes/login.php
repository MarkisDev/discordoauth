<?php

/* Discord Oauth v.4.1
 * Demo Login Script
 * @author : MarkisDev
 * @copyright : https://markis.dev
 */

# Including all the required scripts for demo
require __DIR__ . "/discord.php";
require "../config.php";

# Initializing all the required values for the script to work
init($redirect_url, $client_id, $secret_id, $bot_token);

# Fetching user details | (identify scope)
get_user();

# Adding user to guild | (guilds.join scope)
join_guild('SERVER_ID_HERE');

# Redirecting to home page once all data has been fetched
header('Location: ../');
exit;