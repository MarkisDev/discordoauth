<?php
/* Discord Oauth v.4.1
 * This file will logout a user logged in via the oauth.
 * @author : MarkisDev
 * @copyright : https://markis.dev
 */

# Starting the session
session_start();

# Closing the session and deleting all values associated with the session
session_destroy();

# Redirecting the user back to login page
header('Location: ../');
exit;

?>
