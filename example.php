<?php
require('discordOAuth.php');

// Find your information here: https://discord.com/developers/applications
$discord = new discordOauth("Application Client ID", "Application Client Secret", "OAuth2 scope", "Redirect URI");

// After using login function Discord will return a code to the Redirect URI.
if(isset($_GET['code'])) {
    $discord->getAccessToken($_GET['code']);
}

if(!$discord->loggedIn()) {
    $discord->login();
} else {
    if(isset($_GET['logout'])) {
        $discord->logout();
    }
    $user = $discord->getUser();
    var_dump($user);
}
?>
