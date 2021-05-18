<?php

//Booked Settings

//Uses the Google PHP SDK Client Library to get the user information
//Needs to be installed using Composer-> composer require google/apiclient:"^2.0"
//Or manually installed

//Error Message
define("DEFAULT_ERROR_MESSAGE", "Something went wrong, with google login.");

/*This config file must be filled out before using the facebook sign in
a facebook aplication must be created from
https://developers.facebook.com 
The credentials must then be inserted below
The redirect URI path has to be absolute and pointing to /facebookAuth.php in
your instalation. The domain must be set on the app page on the facebook developers portal.
in my case I used localhost this must be changed.*/

//Facebook Services Constants
//Facebook APP ID
define("APP_ID","308761970692425");
//Facebook APP secret 
define("APP_SECRET","da8a6645d2032b17d2beb55bfdd2b5a1");
//URL to Web/facebookAuth.php in your Web folder
define("APP_URL","http://localhost/BookedScheduler-develop/Web/facebookAuth.php"); 

error_reporting(E_ALL & ~E_NOTICE);
