<?php

/* Home Page
* The home page of the Profile Card demo.
* @author : F-O
*/

# Including all the required scripts for demo
require __DIR__ . "/includes/discord.php";
require __DIR__ . "/config.php";

function is_animated($image)
{
	$ext = substr($image, 0, 2);
	if ($ext == "a_")
	{
		return ".gif";
	}
	else
	{
		return ".png";
	}
}
?>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Discord Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha2/css/bootstrap.min.css" integrity="sha384-DhY6onE6f3zzKbjUPRc2hOzGAdEf4/Dz+WJwBvEYL/lkkIsI3ihufq9hk9K4lVoK" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
	<main class="p-0 base">
	<?php
	// Our output here
	if(isset($_SESSION['user'])) {
		// User is logged in
		$avatar_url = "https://cdn.discordapp.com/avatars/".$_SESSION['user_id']."/".$_SESSION['user_avatar'].is_animated($_SESSION['user_avatar']);
		if(isset($_SESSION['user_banner'])) $banner_url = "https://cdn.discordapp.com/banners/".$_SESSION['user_id']."/".$_SESSION['user_banner'].is_animated($_SESSION['user_banner']);
		?>

		<div class="user-card">

			<div class="header-banner" style="background:#<?=str_pad(dechex($_SESSION['accent_color']), 4, "0", STR_PAD_LEFT)?>">
				<?php echo (isset($banner_url)?'<img src="'.$banner_url.'?size=300">':"");?>
			</div>

			<div class="header-top">
				<div class="header-avatar">
					<img src="<?=$avatar_url?>" height="94" />
				</div>
				<div class="header-text">
					<span class="header-username">
						<?=$_SESSION['username']?>
					</span>
					<span class="header-discrim">
						#<?=$_SESSION['discrim']?> 
					</span>
				</div>
				<p class="text-muted"><small><?=$_SESSION['user_id']?></small></p>
				<div class="header-badges">
					<?php
						// Show the users profile badges
						for ($i = 0; $i < 20; $i++){
							if ($_SESSION['user_flags'] & (1 << $i))
								echo '<img src="assets/img/badges/' . $i . '.svg" height="22"/>';
						}
						if($_SESSION['user_premium'] > 0) echo '<img src="assets/img/badges/nitro.svg" height="22"/>';
						if($_SESSION['user_premium'] > 1) echo '<img src="assets/img/badges/boost.svg" height="22"/>';
					?>
				</div>
			</div>
			<div class="body-wrapper">
				<div class="body">
					<a class="btn btn-lg btn-danger btn-block" href="includes/logout.php">LOG OUT</a>
				</div>
			</div>

		</div>
	<?php
	} else {
		// User is not logged in
		?>
		<a class="btn btn-lg btn-discord btn-block" href="<?=$auth_url = url($client_id, $redirect_url, $scopes)?>">LOG IN</a>
		<?php
	}

	// Check if we have an invite link and server ID set
	if (defined('DISCORD_SERVER_ID') && defined('DISCORD_SERVER_INVITE')) {
		?>
		<a href="<?=DISCORD_SERVER_INVITE?>">
			<img class="mt-4 mb-2" height="28px" alt="Join our Discord!" src="https://img.shields.io/discord/<?=DISCORD_SERVER_ID?>?color=7289DA&label=discord%20server&logo=discord&logoColor=7289DA&style=for-the-badge"/>
		</a>
		<?php
	}
	?>
	</main>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha2/js/bootstrap.bundle.min.js" integrity="sha384-BOsAfwzjNJHrJ8cZidOg56tcQWfp6y72vEJ8xQ9w6Quywb24iOsW913URv1IS4GD" crossorigin="anonymous"></script>
</body>
