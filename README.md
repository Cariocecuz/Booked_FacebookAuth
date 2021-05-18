# Booked_FacebookAuth
Change to how the booked app does the facebook login. User now need to create a new facebook app and add their credentials.

## Configuration
Configuration is pretty straight forward after copying files into you booked instalation, go into the config file and add your facebook app credentials If you don't have your own you can crete one here: https://developers.facebook.com/apps

You are going to need to add your APP ID, secret and your redirect URI in the config file. THE REDIRECT PATH NEEDS TO BE ABSOLUTE POINTING TO "facebookAuth.php" IN YOUR WEB FOLDER.

Uses the Facebook PHP SDK API to get the user information so it must be installed into /WebService/Facebook this is were I assume it will be installed and were the paths are set too. Needs to be installed manually or using Composer:
For Composer you can open the cmd line and -> composer require facebook/graph-sdk:"~5.0" 
