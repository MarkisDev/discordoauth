<?php
	require_once('discord.php');
	if (!array_key_exists('user', $_SESSION))
	{
		init("", 'INSERT_CALLBACK_URL','INSERT_CLIENT_ID', 'INSERT_CLIENT_SECRET');
		get_user();
		
	}
	echo $_SESSION['user']['username'];

?>