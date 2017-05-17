<?php
/**
 * Discord OAuth v.1.0.0 
 * @copyright rijuthmenon.me
 * @author Markis
 */




/***
 * Initializing GuzzleHttp for requests
 **/
include('vendor/autoload.php');
use GuzzleHttp\Client;
$http = new Client([
    'base_uri' => 'https://discordapp.com',
    'verify' => false,
]);



/***
 * Initializing first request variables
 **/
$code = $_GET['code'];
$redirect = "YOUR_REDIRECT_URI";
$clientid = "YOUR_CLIENT_ID";
$clientsecretid= "YOUR_CLIENT_SECRET";
$data = "grant_type=authorization_code&code=$code&redirect_uri=$redirect&client_id=$clientid&client_secret=$clientsecretid";



/***
 * Initiate first request to get userinfo with email (identify scope)
 **/
$response = $http->request('POST', '/api/oauth2/token', [
			'form_params' => [
				'client_id' => $clientid,
				'client_secret' => $clientsecretid,
				'grant_type' => 'authorization_code',
				'code' => $code,
				'redirect_uri' => $redirect,
			]
		]);
		$responseBody1 = $response->getBody(true);


 
/***
 * Saving body content from request
 **/
$results= json_decode($responseBody1, true);
$auth = $results['access_token'];



/***
 * Initiate second request
 **/
 
$response = $http->request('GET', '/api/users/@me', [
    'headers' => [
        'Authorization' => 'Bearer '.$auth
    ]
]);

$responseBody2 = $response->getBody(true); 
$responsea = json_decode($responseBody2, true);
$user = $responsea['username'].'#'.$responsea['discriminator']; // store username#discrim from the array
$id =  $responsea['id']; // store userid from the array
echo $responseBody2;



/***
 * Initiate second request to get Guilds Information (guilds scope)
 **/
$response = $http->request('GET', '/api/users/@me/guilds', [
    'headers' => [
        'Authorization' => 'Bearer '.$auth
    ]
]);
$responseB = $response->getBody(true); 
$response2 = json_decode($responseB, true);
echo $responseB;

?>