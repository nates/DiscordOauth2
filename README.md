# DiscordOAuth2
An easy to use Discord Oauth2 PHP class. An example of this being used is for a website to integrate a Discord login system.

## Example usage
```php
require('discordOAuth.php');

// Find your information here: https://discord.com/developers/applications
$discord = new discordOauth("Application Client ID", "Application Client Secret", "OAuth2 scope", "Redirect URI");

// After using login function Discord will return a code to the Redirect URI. Only put this on your Redirect URI page.
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
    $guilds = $discord->getGuilds();
    var_dump($user);
    var_dump($guilds);
}
```
