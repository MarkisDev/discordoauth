<?php
/**
 * Breakthrough discord oauth.
 *
 * @package mm-discord-oauth
 *
 * @wordpress-plugin
 * Plugin Name:       Breakthrough Discord Oauth
 * Description:       Provides "Login with Discord" via a shortcode, then uses the user's oauth token to get their discord username, populate the field on the member profile and add them to the server after validating their membership.
 * Plugin URI:        https://github.com/BreakthroughParty
 * Version:           0.44-dev
 * Author:            Breakthrough Contributors
 * Author URI:        https://breakthroughparty.org.uk/
 */
	require_once($_SERVER['DOCUMENT_ROOT'] . "/wp-load.php");
	require_once($_SERVER['DOCUMENT_ROOT'] . "/wp-content/plugins/membermouse/includes/mm-constants.php");
	require_once($_SERVER['DOCUMENT_ROOT'] . "/wp-content/plugins/membermouse/includes/init.php");
	require_once("resources/config.php");
	require_once("resources/discord.php");
	require_once("resources/settingspage.php");
	$settings = get_option('discord_oauth_plugin_options');
 add_shortcode( 'join_discord', function ( $atts ) {
	#$atts = shortcode_atts( array(
	#	'foo' => 'no foo'
	#), $atts, 'join_discord' );
    global $client_id, $redirect_url, $scopes;
    $isactivemember = mm_member_decision(array("isMember"=>"true"));
    if ($isactivemember == true) {
        $auth_url = url($client_id, $redirect_url, $scopes);
    }
    else {
        return wp_kses_post( "Error validating membership" );
    }
	$discordlinked = mm_member_decision(array("customField_9"=>"mm_cb_on"));
	if ($discordlinked == true) {
		$discorduser = mm_member_data(array("name"=>"customField_7"));
		return wp_kses_post(
		"You have already joined our discord server.
		<br>Log into Discord with your username {$discorduser} <a href='https://discord.com/app'>here</a> to access the server."
		);
	}
    return wp_kses_post("<a href='{$auth_url}' class='nobg'><img src='/wp-content/plugins/mm-discord-oauth/resources/discord_button.png'></img></a>");
}
);
$bot_token = $settings["bot_token"];
$guildid = $settings['guild_id'];
$mmkey = $settings['mm_key'];
$notificationchannel = $settings['notification_channel_id'];
$mmsecret = $settings['mm_secret'];
$wpurl = get_site_url();
define("BOT_TOKEN",$bot_token);
function discord_notify($options) {
	global $notificationchannel;
	$msgobj=json_encode($options, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
	$dAPI_SendMessage = "https://discordapp.com/api/channels/{$notificationchannel}/messages";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HTTPHEADER,
		array( "Authorization: Bot " . BOT_TOKEN,
				'Content-Type: application/json',
				'Referer: https://discordapp.com/channels/@me'
		));

	curl_setopt_array( $ch, [
		CURLOPT_URL => $dAPI_SendMessage,
		CURLOPT_POST => true,
		CURLOPT_POSTFIELDS => $msgobj]);
		
	$msgresponse = curl_exec( $ch );
	curl_close( $ch );
	return $msgresponse;
}

function UncheckDiscordLinked($memberid) {
	global $mmkey, $mmsecret, $wpurl;
    $inputParams = "apikey={$mmkey}&apisecret={$mmsecret}&";
    $inputParams .= "member_id={$memberid}&"; 
	$inputParams .= "custom_field_9=mm_cb_off";
    $apiCallUrl = "{$wpurl}/wp-content/plugins/membermouse/api/request.php?q=/updateMember";
    $ch = curl_init($apiCallUrl); 

    curl_setopt($ch, CURLOPT_POST, 1); 
    curl_setopt($ch, CURLOPT_POSTFIELDS, $inputParams); 
    curl_setopt($ch, CURLOPT_HEADER, 0); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
    $result = curl_exec($ch); 
    curl_close($ch);
}
function RemoveMemberFromDiscord($data) {
	global $guildid;
	$memberstatus = $data["status"];
	$statustext = $data["status_name"];
	$discorduserid = $data["cf_10"];
	$discordname = $data["cf_7"];
	$memberid = $data["member_id"];
	//if status is cancelled(2) or expired(8)
	if ($memberstatus == 2 || $memberstatus == 8) {
		$msgobj = [
			"content" => "Member ID {$memberid}'s membership has gone to {$statustext} status.",
		];
		$m=discord_notify($msgobj);
		//if we have the discord user ID
		if (!empty($discorduserid)) {
			UncheckDiscordLinked($memberid);
			$response = kick_user($guildid, $discorduserid);
			if (empty($response)) {
				$responsetext = "Success!";
			}
			else {
			$responsetext = "Discord API is unhappy:" . var_dump($response);
			}
			
			$msgobj = [
			"content" => "Attempting to kick associated Discord User: <@{$discorduserid}> {$responsetext}",
		];
		$m=discord_notify($msgobj);
			
		}
		elseif (empty($discorduserid)) {
			$msgobj = [
			"content" => "No discord ID for member {$memberid}. They will need to be kicked manually!",
		];
		$m=discord_notify($msgobj);
		if (!empty($discordname)) {
			$msgobj = [
				"content" => "They have a username on file: {$discordname}",
			];
			$m=discord_notify($msgobj);
		}
		}
		
	}
}
add_action('mm_member_status_change', 'RemoveMemberFromDiscord');
?>