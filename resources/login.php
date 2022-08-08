<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/wp-load.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/wp-content/plugins/membermouse/includes/mm-constants.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/wp-content/plugins/membermouse/includes/init.php");
$wpurl = get_site_url();
require_once("discord.php");
require("config.php");
$settings = get_option('discord_oauth_plugin_options');

$loginurl = $settings['login_url'];
$memberurl = $settings['member_url'];
$successurl = $settings['success_url'];

$MemberID = mm_member_data(array("name"=>"id"));
if (!$MemberID) {
	#die("A fatal error has occurred: No membermouse login session");
	header('Location: ' .$loginurl);
	die();
}
elseif (!$_GET['code']) {
	# Handle user cancelling the discord auth prompt.
	header('Location: ' .$memberurl);
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
	$mmkey = $settings['mm_key'];
	$mmsecret = $settings['mm_secret'];
    $discordID = $_SESSION['user_id'];
	$discordUser = $_SESSION['username'] . '#' . $_SESSION['discrim'];
    $inputParams = "apikey={$mmkey}&apisecret={$mmsecret}&";
    $inputParams .= "member_id={$MemberID}&"; 
    $inputParams .= "custom_field_7={$discordUser}&";
	$inputParams .= "custom_field_9=mm_cb_on&";
	$inputParams .= "custom_field_10={$discordID}";
    $apiCallUrl = "{$wpurl}/wp-content/plugins/membermouse/api/request.php?q=/updateMember";
    $ch = curl_init($apiCallUrl); 

    curl_setopt($ch, CURLOPT_POST, 1); 
    curl_setopt($ch, CURLOPT_POSTFIELDS, $inputParams); 
    curl_setopt($ch, CURLOPT_HEADER, 0); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
    $result = curl_exec($ch); 
    curl_close($ch);


# Adding user to guild | (guilds.join scope)
$guildid = $settings['guild_id'];
join_guild($guildid);

#grant server role
$roleid = $settings['role_id'];
grant_role($guildid, $roleid);

#notify admin channel
$msgobj = [
    "content" => "Member ID {$MemberID} joined Discord via oAuth with Discord User {$DiscordUser} and ID {$DiscordID}",
];
$m=discord_notify($msgobj);

# clear session
session_destroy();
# Redirecting to success page
header('Location: ' .$successurl);