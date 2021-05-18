<?php
//Defining the root directory

define('ROOT_DIR', '../');

//Loading config constants fom config file & load the google API
require_once(ROOT_DIR . 'WebServices/Facebook/facebookConfig.php');
require_once(ROOT_DIR . 'WebServices/Facebook/vendor/autoload.php');

//Init the Facebook SDK
session_start();
$facebook_Client = new Facebook\Facebook([
    'app_id'                => APP_ID,
    'app_secret'            => APP_SECRET,
    'default_graph_version' => 'v2.5'
]);

$helper = $facebook_Client->getRedirectLoginHelper();
$permissions = ['email'];

try {
    if (isset($_SESSION['facebook_access_token'])) {
        $acesstoken = $_SESSION['facebook_access_token'];
    } else {
        $acesstoken = $helper->getAccessToken();
    }
} catch (\Facebook\Exceptions\FacebookResponseException $e) {
    //If graph returns issues
    echo 'Graph Returned an error: ' .$e->getMessage();
    exit;
} catch (\Facebook\Exceptions\FacebookSDKException $e){
    //If SDK returns errors
    echo 'Facebook SDK returned an error: ' .$e->getMessage();
}

if (isset($acesstoken)) {
    if (isset($_SESSION['facebook_access_token'])) {
        $facebook_Client->setDefaultAccessToken($_SESSION['facebook_access_token']);
    } else {
        //Get short lived acess token
        $_SESSION['facebook_access_token'] = (string) $acesstoken;
        //OAUTH 2.0 Client Handler
        $oAuth2Client = $facebook_Client->getOAuth2Client();
        //Exchange a short lived acess token for a long lived one
        $longLivedAcessToken = $oAuth2Client->getLongLivedAccessToken($_SESSION['facebook_access_token']);
        $_SESSION['facebook_access_token'] = (string) $longLivedAcessToken;
        //Set default aess token to b used in the script
        $facebook_Client->setDefaultAccessToken($_SESSION['facebook_access_token']);
    }
    if (isset($_GET['code'])) {
        header("Location: ".ROOT_DIR."Web/external-auth.php?type=fb&code=".$code);
    }
    try {
        $profile_request    = $facebook_Client ->get('/me?fields=name,first_name,last_name,email');
        $profile            = $profile_request ->getGraphUser();

        $fb_Email       = $profile   ->getField('email');
        $fb_First_Name  = $profile   ->getField('first_name');
        $fb_Last_Name   = $profile   ->getField('last_name');

        $_SESSION['fb_email']       = $fb_Email;
        $_SESSION['fb_first_name']  = $fb_First_Name;
        $_SESSION['fb_last_name']   = $fb_Last_Name;
    } catch (\Facebook\Exceptions\FacebookResponseException $e) {
        //Grapsh errors
        echo 'Graph returned an error: ' .$e->getMessage();
        session_destroy();
        header("Location: ./");
        exit;
    } catch (\Facebook\Exceptions\FacebookSDKException $e){
        echo "Facebook SDK returned an error: ".$e->getMessage();
        exit;
    }
} else {
    //replace your website URL same as in the developers.facebook.com/apps
    $loginURL = $helper->getLoginUrl(APP_URL, $permissions);
    header("Location: ".$loginURL);
}
