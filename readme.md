# MemberMouse Discord Oauth
Wordpress plugin that adds a [join_discord] shortcode which depending on if the member is logged in with valid membership and hasn't already linked their discord account will display the login with discord link.

When the member is returned to the website from Discord's auth page, their membership status is checked before adding them to the Discord server, granting them a specified role in the server and redirecting them to a success page.

# PROCEED WITH CAUTION! This Plugin is a Work in Progress!

This plugin, in it's current state is only suitable for use on the site it was created for. This is because there are some parts still hardcoded that are specific to that website, like the IDs of the custom fields in MemberMouse.

Work is currently underway to move everything to a settings page.

Until we reach 1.0 and everything is available in the settings page, this plugin is only recommended for those that understand and can adapt the code.

# Credits
This repo is forked from MarkisDev/discordoauth and is using his Discord oAuth functions. Thank you Markis.
