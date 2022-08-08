<?php
function discord_oauth_add_settings_page() {
    add_options_page( 'Discord OAuth', 'Discord OAuth', 'manage_options', 'mm-discord-oauth', 'discord_oauth_render_plugin_settings_page' );
}
add_action( 'admin_menu', 'discord_oauth_add_settings_page' );
function discord_oauth_render_plugin_settings_page() {
    ?>
    <h2>Discord oAuth for MemberMouse Settings</h2>
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
    add_settings_field( 'discord_oauth_setting_notification_channel_id', 'Discord Admin Notification Channel ID', 'discord_oauth_setting_notification_channel_id', 'mm_discord_oauth', 'discord_server_settings' );
	//mm settings
	add_settings_field( 'discord_oauth_setting_mm_key', 'MemberMouse API Key', 'discord_oauth_setting_mm_key', 'mm_discord_oauth', 'mm_api_settings' );
	add_settings_field( 'discord_oauth_setting_mm_secret', 'MemberMouse Secret', 'discord_oauth_setting_mm_secret', 'mm_discord_oauth', 'mm_api_settings' );
	//site settings
	add_settings_field( 'discord_oauth_setting_login_url', 'Login Page URL', 'discord_oauth_setting_login_url', 'mm_discord_oauth', 'site_settings' );
	add_settings_field( 'discord_oauth_setting_member_url', 'Member Area URL', 'discord_oauth_setting_member_url', 'mm_discord_oauth', 'site_settings' );
	add_settings_field( 'discord_oauth_setting_success_url', 'Success Page URL', 'discord_oauth_setting_success_url', 'mm_discord_oauth', 'site_settings' );
}
add_action( 'admin_init', 'discord_oauth_register_settings' );

// validate settings

function discord_oauth_plugin_options_validate( $input ) {
	$validatedinput = $input;
	if ( ! preg_match( '/[0-9]{18}/', $validatedinput['client_id'] ) ) {
		$validatedinput['client_id'] = '';
		add_settings_error('discord_oauth_setting_client_id', 'client_id_error', 'Invalid Discord Client ID. Client ID should be 18 numbers.', 'error');
	}
	if ( ! preg_match( '/[a-zA-Z0-9]{32}/', $validatedinput['client_secret'] ) ) {
		$validatedinput['client_secret'] = '';
		add_settings_error('discord_oauth_setting_client_secret', 'client_secret_error', 'Invalid Discord Client Secret. Client Secret should be 32 alphanumeric characters.', 'error');
	}
	
	
	
	return $validatedinput;
	
	}

// Settings form

//discord api section
function discord_oauth_dapi_section_text() {
	global $redirect_url;
    echo "<p>Set the Discord API credentials here.</p><p><b>Please Note:</b> You must add <b>{$redirect_url}</b> to the Redirects section<br>in the OAuth2 tab of the Discord Developer Portal or Discord oAuth will fail.";
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
function discord_oauth_setting_notification_channel_id() {
    $options = get_option( 'discord_oauth_plugin_options' );
    echo '<p>This is where system notifications will be sent, it is advisable to use a private channel only admins/moderators can access.</p>';
    echo "<input id='discord_oauth_setting_notification_channel_id' size='70' name='discord_oauth_plugin_options[notification_channel_id]' type='text' value='" . esc_attr( $options['notification_channel_id'] ) . "' />";
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