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

require_once($_SERVER['DOCUMENT_ROOT'] . "/wp-load.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/wp-content/plugins/membermouse/includes/mm-constants.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/wp-content/plugins/membermouse/includes/init.php");
require_once("discord.php");
require("config.php");

$MemberID = mm_member_data(array("name"=>"id"));
if (!$MemberID) {
	#die("A fatal error has occurred: No membermouse login session");
	header('Location: /login');
	die();
}
elseif (!$_GET['code']) {
	# Handle user cancelling the discord auth prompt.
	header('Location: /my-breakthrough');
	die();
}
# Enabling error display
#error_reporting(E_ALL);
#ini_set('display_errors', 1);

# Initializing all the required values for the script to work
init($redirect_url, $client_id, $secret_id, $bot_token);

# Fetching user details | (identify scope) (optionally email scope too if you want user's email) [Add identify AND email scope for the email!]
get_user();

# Uncomment this for using it WITH email scope and comment line 32.
#get_user($email=True);

#Update member Discord Username field
    $discordID = $_SESSION['user_id'];
    $discordUser = $_SESSION['username'] . '#' . $_SESSION['discrim'];
    $inputParams = "apikey={your membermouse api key}&apisecret={your membermouse api secret}&";
    $inputParams .= "member_id={$MemberID}&"; 
    $inputParams .= "custom_field_7={$discordUser}&";
	$inputParams .= "custom_field_9=mm_cb_on&";
	$inputParams .= "custom_field_10={$discordID}";

    $apiCallUrl = "{your membermouse api url}?q=/updateMember";
    $ch = curl_init($apiCallUrl); 

    curl_setopt($ch, CURLOPT_POST, 1); 
    curl_setopt($ch, CURLOPT_POSTFIELDS, $inputParams); 
    curl_setopt($ch, CURLOPT_HEADER, 0); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
    $result = curl_exec($ch); 
    curl_close($ch);


# Adding user to guild | (guilds.join scope)
$guildid = "{discord server id to add too}";
join_guild($guildid);

#grant server role
$roleid = "{discord role id to grant}";
grant_role($guildid, $roleid);


# clear session
session_destroy();
# Redirecting to success page
header('Location: /link-discord-account-completed');