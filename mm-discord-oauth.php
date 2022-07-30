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
 * Version:           0.3
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
		"You have already joined our discord server.
		<br>Log into Discord with your username {$discorduser} <a href='https://discord.com/app'>here</a> to access the server."
		);
	}
    return wp_kses_post("<a href='{$auth_url}' class='nobg'><img src='/wp-content/plugins/mm-discord-oauth/resources/discord_button.png'></img></a>");
}
);
function discord_oauth_add_settings_page() {
    add_options_page( 'Discord OAuth', 'Discord OAuth', 'manage_options', 'mm-discord-oauth', 'discord_oauth_render_plugin_settings_page' );
}
add_action( 'admin_menu', 'discord_oauth_add_settings_page' );
function discord_oauth_render_plugin_settings_page() {
    ?>
    <h2>Discord Oauth for MemberMouse Settings</h2>
    <form action="options.php" method="post">
        <?php 
        settings_fields( 'discord_oauth_plugin_options' );
        do_settings_sections( 'mm_discord_oauth' ); ?>
        <input name="submit" class="button button-primary" type="submit" value="<?php esc_attr_e( 'Save' ); ?>" />
    </form>
    <?php
}
function discord_oauth_register_settings() {
    register_setting( 'discord_oauth_plugin_options', 'discord_oauth_plugin_options', 'discord_oauth_plugin_options_validate' );
    add_settings_section( 'discord_api_settings', 'Discord API Settings', 'discord_oauth_dapi_section_text', 'mm_discord_oauth' );
	add_settings_section( 'discord_server_settings', 'Discord Server Settings', 'discord_oauth_dserver_section_text', 'mm_discord_oauth' );
	add_settings_section( 'mm_api_settings', 'MemberMouse API Settings', 'discord_oauth_mmapi_section_text', 'mm_discord_oauth' );
	add_settings_section( 'site_settings', 'Your Site Settings', 'discord_oauth_site_section_text', 'mm_discord_oauth' );
	//discord settings
    add_settings_field( 'discord_oauth_setting_client_id', 'Client ID', 'discord_oauth_setting_client_id', 'mm_discord_oauth', 'discord_api_settings' );
    add_settings_field( 'discord_oauth_setting_client_secret', 'Client Secret', 'discord_oauth_setting_client_secret', 'mm_discord_oauth', 'discord_api_settings' );
	add_settings_field( 'discord_oauth_setting_bot_token', 'Bot Token', 'discord_oauth_setting_bot_token', 'mm_discord_oauth', 'discord_api_settings' );
	//discord server settings
	add_settings_field( 'discord_oauth_setting_guild_id', 'Discord Guild/Server ID', 'discord_oauth_setting_guild_id', 'mm_discord_oauth', 'discord_server_settings' );
	add_settings_field( 'discord_oauth_setting_role_id', 'Discord Role ID to Grant', 'discord_oauth_setting_role_id', 'mm_discord_oauth', 'discord_server_settings' );
	//mm settings
	add_settings_field( 'discord_oauth_setting_mm_key', 'MemberMouse API Key', 'discord_oauth_setting_mm_key', 'mm_discord_oauth', 'mm_api_settings' );
	add_settings_field( 'discord_oauth_setting_mm_secret', 'MemberMouse Secret', 'discord_oauth_setting_mm_secret', 'mm_discord_oauth', 'mm_api_settings' );
	//site settings
	add_settings_field( 'discord_oauth_setting_login_url', 'Login Page URL', 'discord_oauth_setting_login_url', 'mm_discord_oauth', 'site_settings' );
	add_settings_field( 'discord_oauth_setting_member_url', 'Member Area URL', 'discord_oauth_setting_member_url', 'mm_discord_oauth', 'site_settings' );
	add_settings_field( 'discord_oauth_setting_success_url', 'Success Page URL', 'discord_oauth_setting_success_url', 'mm_discord_oauth', 'site_settings' );
}
add_action( 'admin_init', 'discord_oauth_register_settings' );

// Settings form

//discord api section
function discord_oauth_dapi_section_text() {
    echo '<p>Set the Discord API credentials here.</p>';
}

function discord_oauth_setting_client_id() {
    $options = get_option( 'discord_oauth_plugin_options' );
    echo "<input id='discord_oauth_setting_client_id' size='70' name='discord_oauth_plugin_options[client_id]' type='text' value='" . esc_attr( $options['client_id'] ) . "' />";
}

function discord_oauth_setting_client_secret() {
    $options = get_option( 'discord_oauth_plugin_options' );
    echo "<input id='discord_oauth_setting_client_secret' size='70' name='discord_oauth_plugin_options[client_secret]' type='text' value='" . esc_attr( $options['client_secret'] ) . "' />";
}

function discord_oauth_setting_bot_token() {
    $options = get_option( 'discord_oauth_plugin_options' );
    echo "<input id='discord_oauth_setting_bot_token' size='70' name='discord_oauth_plugin_options[bot_token]' type='text' value='" . esc_attr( $options['bot_token'] ) . "' />";
}

//discord server section
function discord_oauth_dserver_section_text() {
    echo '<p>Set the Discord server settings here.</p>';
}

function discord_oauth_setting_guild_id() {
    $options = get_option( 'discord_oauth_plugin_options' );
    echo "<input id='discord_oauth_setting_guild_id' size='70' name='discord_oauth_plugin_options[guild_id]' type='text' value='" . esc_attr( $options['guild_id'] ) . "' />";
}

function discord_oauth_setting_role_id() {
    $options = get_option( 'discord_oauth_plugin_options' );
    echo "<input id='discord_oauth_setting_role_id' size='70' name='discord_oauth_plugin_options[role_id]' type='text' value='" . esc_attr( $options['role_id'] ) . "' />";
}

//mm api section
function discord_oauth_mmapi_section_text() {
    echo '<p>Set the MemberMouse API credentials here.</p>';
}

function discord_oauth_setting_mm_key() {
    $options = get_option( 'discord_oauth_plugin_options' );
    echo "<input id='discord_oauth_setting_mm_key' size='70' name='discord_oauth_plugin_options[mm_key]' type='text' value='" . esc_attr( $options['mm_key'] ) . "' />";
}

function discord_oauth_setting_mm_secret() {
    $options = get_option( 'discord_oauth_plugin_options' );
    echo "<input id='discord_oauth_setting_mm_secret' size='70' name='discord_oauth_plugin_options[mm_secret]' type='text' value='" . esc_attr( $options['mm_secret'] ) . "' />";
}

//site settings section
function discord_oauth_site_section_text() {
    echo '<p>Set your site settings here</p>';
}

function discord_oauth_setting_login_url() {
    $options = get_option( 'discord_oauth_plugin_options' );
	echo '<p>Location of the MemberMouse Login page. Anyone not logged in will be sent here if they somehow hit the discord login script.</p>';
    echo "<input id='discord_oauth_setting_login_url' size='70' name='discord_oauth_plugin_options[login_url]' type='text' value='" . esc_attr( $options['login_url'] ) . "' />";
}

function discord_oauth_setting_member_url() {
    $options = get_option( 'discord_oauth_plugin_options' );
	echo '<p>Location of the MemberMouse Member Home page. Users will be sent here if they cancel the Discord permissions prompt.</p>';
    echo "<input id='discord_oauth_setting_member_url' size='70' name='discord_oauth_plugin_options[member_url]' type='text' value='" . esc_attr( $options['member_url'] ) . "' />";
}

function discord_oauth_setting_success_url() {
    $options = get_option( 'discord_oauth_plugin_options' );
	echo '<p>Location of the success page. Users will be sent here after successfully authenticating with Discord and joining the server.</p>';
    echo "<input id='discord_oauth_setting_success_url' size='70' name='discord_oauth_plugin_options[success_url]' type='text' value='" . esc_attr( $options['success_url'] ) . "' />";
}
?>