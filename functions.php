<?php
/* Useful Functions 
 * Just some functions
 * @owner Rijuth Menon A.K.A Markis
 * @copyright https://markis.pw | https://rijuthmenon.me
 */
 
// Redirect function
function redirect($url)
{
    if (!headers_sent())
    {    
        header('Location: '.$url);
        exit;
        }
    else
        {  
        echo '<script type="text/javascript">';
        echo 'window.location.href="'.$url.'";';
        echo '</script>';
        echo '<noscript>';
        echo '<meta http-equiv="refresh" content="0;url='.$url.'" />';
        echo '</noscript>';
        exit;
    }
}

	// Get's clients ip even if behind direct proxy # Returns client ip
	function client_ip() {
		if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			return $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			return $_SERVER['REMOTE_ADDR'];
		}
	}
	
		// Checks if Discord Avatar String is gif or not
	function is_animated($ava_str) {
		$das = substr($ava_str, 0, 2);
		if ($das == "a_") {
			return ".gif";
		} else {
			return ".png";
		}
	}
	


?>
