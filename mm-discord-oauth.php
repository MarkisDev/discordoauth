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
 * Version:           0.1
 * Author:            Breakthrough Contributors
 * Author URI:        https://breakthroughparty.org.uk/
 */
	require_once($_SERVER['DOCUMENT_ROOT'] . "/wp-load.php");
	require_once($_SERVER['DOCUMENT_ROOT'] . "/wp-content/plugins/membermouse/includes/mm-constants.php");
	require_once($_SERVER['DOCUMENT_ROOT'] . "/wp-content/plugins/membermouse/includes/init.php");
	require_once("resources/config.php");
	require_once("resources/discord.php");

 add_shortcode( 'join_discord', function ( $atts ) {
	#$atts = shortcode_atts( array(
	#	'foo' => 'no foo'
	#), $atts, 'join_discord' );
    global $client_id, $redirect_url, $scopes;
    $isactivemember = mm_member_decision(array("isMember"=>"true", "membershipId"=>"2|3|4|5|6"));
    if ($isactivemember == true) {
        $auth_url = url($client_id, $redirect_url, $scopes);
    }
    else {
        return wp_kses_post( "Error validating membership, please <a href='mailto:membership@breakthroughparty.org.uk'>contact the membership team</a> for help." );
    }
	$discordlinked = mm_member_decision(array("customField_9"=>"mm_cb_on"));
	if ($discordlinked == true) {
		$discorduser = mm_member_data(array("name"=>"customField_7"));
		return wp_kses_post(
		"You are already in our discord server.
		<br>Log into Discord with the with your username {$discorduser} <a href='https://discord.com/app'>here</a> to access the server."
		);
		$auth_url = "/link-discord-account-completed";
	}
    return wp_kses_post("<a href='{$auth_url}'>Login with Discord</a>");
}
);
?>