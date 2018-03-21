<?php
/**
 * Discord OAuth v.3.0.0 
 * @copyright rijuthmenon.me
 * @author Markis
 */

 // Starting Session
 session_start();

// Setting up GuzzleHttp for our requests
include('guzzle/autoload.php');
use GuzzleHttp\Client;
$http = new Client([
    'base_uri' => 'https://discordapp.com',
    'verify' => false,
]);

// Generate redirect url (Login with discord URL)
function url($clientid, $redirect, $scope)
{
	return 'https://discordapp.com/oauth2/authorize?response_type=code&client_id=' . $clientid . '&redirect_uri=' . $redirect . '&scope=' . $scope;
}

// Get code and initialize the variables
function init($code, $redirect, $clientid, $clientsecretid)
{
$code = $_GET['code'];
$data = "grant_type=authorization_code&code=$code&redirect_uri=$redirect&client_id=$clientid&client_secret=$clientsecretid";

// Get authorization code by posting to discord's API
$response = $GLOBALS['http']->request('POST', '/api/oauth2/token', [
			'form_params' => [
				'client_id' => $clientid,
				'client_secret' => $clientsecretid,
				'grant_type' => 'authorization_code',
				'code' => $code,
				'redirect_uri' => $redirect,
			]
		]);
		$responseBody1 = $response->getBody(true);
		$results= json_decode($responseBody1, true);
		$_SESSION['auth_token'] = $results['access_token'];
}

// Function to get users details and store them in SESSION variables (identify scope)
function get_user()
{
$response = $GLOBALS['http']->request('GET', '/api/users/@me', [
    'headers' => [
        'Authorization' => 'Bearer ' . $_SESSION['auth_token']
    ]
]);

$responseBody = $response->getBody(true); 
$response = json_decode($responseBody, true);
$_SESSION['user'] = $responseBody;
$_SESSION['username'] = $response['username'];
$_SESSION['discrim'] = $response['discriminator'];
$_SESSION['user_id'] = $response['id'];
$_SESSION['user_avatar'] = $response['avatar'];
}

// Function to get logged in users guilds information. (guilds scope)
function get_guilds()
{
$response = $GLOBALS['http']->request('GET', '/api/users/@me/guilds', [
    'headers' => [
        'Authorization' => 'Bearer '.$_SESSION['auth_token']
    ]
]);
$responseBody = $response->getBody(true); 
$response = json_decode($responseBody, true);
return $response;
}

// Function to get a single guild information Returns guilds object
function get_guild($id)
{
	$response = $GLOBALS['http']->request('GET', '/api/guilds/' . $id, [
		'headers' => [
			'Authorization' => 'Bearer ' . $_SESSION['auth_token']
		]
	]);


$responseBody = $response->getBody(true);
$response = json_decode($responseBody, true);
return $reponse;
}
?>
