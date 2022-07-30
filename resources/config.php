<?php

$settings = get_option('discord_oauth_plugin_options');

# CLIENT ID
# https://i.imgur.com/GHI2ts5.png (screenshot)
$client_id = $settings['client_id'];

# CLIENT SECRET
# https://i.imgur.com/r5dYANR.png (screenshot)
$secret_id = $settings['client_secret'];
# SCOPES SEPARATED BY + SIGN
# example: identify+email+guilds+connections
# $scopes = "identify+email";
$scopes = "identify+guilds.join";

# REDIRECT URL
# example: https://mydomain.com/includes/login.php
# example: https://mydomain.com/test/includes/login.php
$wpurl = get_site_url();
$redirect_url = "{$wpurl}/wp-content/plugins/mm-discord-oauth/resources/login.php";

$bot_token = $settings['bot_token'];
?>
