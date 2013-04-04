<?php
require_once 'config.php';
require_once 'googleclient/Google_Client.php';
require_once 'googleclient/contrib/Google_Oauth2Service.php';
require_once 'googleclient/contrib/Google_PlusService.php';

session_start();

$client = new Google_Client();
$client->setApplicationName($GoogleApplicationName);
// Visit https://code.google.com/apis/console to generate your
// oauth2_client_id, oauth2_client_secret, and to register your oauth2_redirect_uri.
$client->setClientId($GoogleClientId);
$client->setClientSecret($GoogleClientSecret);
$client->setRedirectUri($GoogleRedirectUri);
$client->setScopes(array('https://www.googleapis.com/auth/userinfo.profile', 'https://www.googleapis.com/auth/userinfo.email'));

if (isset($_REQUEST['logout'])) {
  unset($_SESSION['access_token']);
}

if (isset($_GET['code'])) {
  $client->authenticate($_GET['code']);
  $_SESSION['access_token'] = $client->getAccessToken();
  header('Location: https://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']);
}

if (isset($_SESSION['access_token'])) {
  $client->setAccessToken($_SESSION['access_token']);
}

if ($client->getAccessToken()) {
  $plus = new Google_Oauth2Service($client);
  $userinfo = $plus->userinfo;
  //Array ( [id] => 110745130342195140515 [email] => overeemm@gmail.com [verified_email] => 1 [name] => Michiel Overeem [given_name] => Michiel [family_name] => Overeem [link] => https://plus.google.com/110745130342195140515 [picture] => https://lh4.googleusercontent.com/-Bv2KVWe2670/AAAAAAAAAAI/AAAAAAAABEE/LeSIWHhWLIg/photo.jpg [gender] => male [birthday] => 0000-06-15 [locale] => en ) 
  
  $userinfoArray = $userinfo->get();
  $email = $userinfoArray["email"];

  if($email === "overeemm@gmail.com" || stripos($email, '@overeemtelecom.nl')) {

    $_SESSION['email'] = $userinfoArray["email"];
    $_SESSION['name'] = $userinfoArray["name"];
    // The access token may have been updated lazily.
    $_SESSION['access_token'] = $client->getAccessToken();
  } else {
    header('Location: http://www.overeemtelecom.nl');
  }

  header('Location: /');
} else {
  header('Location: ' . $client->createAuthUrl());
}

?><!doctype html>
<html>
<head>
  <meta charset="utf-8">
</head>
<body>
</body>
</html>