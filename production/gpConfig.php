<?php
session_start();

//Include Google client library 
include_once 'src/Google_Client.php';
include_once 'src/contrib/Google_Oauth2Service.php';

/*
 * Configuration and setup Google API
 */
$clientId = '498014574508-in6k2gdmln3dd3sulg5nia8e49b65rft.apps.googleusercontent.com'; //Google client ID
$clientSecret = 'Z7_FvnZZTQlm7XyI8ZCgHCRp'; //Google client secret
$redirectURL = 'http://localhost/st/gentelella-master/production/index.php'; //Callback URL

//Call Google API
$gClient = new Google_Client();
$gClient->setApplicationName('Login to CodexWorld.com');
$gClient->setClientId($clientId);
$gClient->setClientSecret($clientSecret);
$gClient->setRedirectUri($redirectURL);

$google_oauthV2 = new Google_Oauth2Service($gClient);
?>