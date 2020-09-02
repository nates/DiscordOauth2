<?php

class discordOauth {

    /** @var string Discord application's client ID. */
    private $oauth_client_id;

    /** @var string Discord application's client secret. */
    private $oauth_client_secret;

    /** @var string Discord application's scope. */
    private $scope;

    /** @var string Discord application's redirect URI. */
    private $redirect_uri;

    /**
     * Redirect user to Discord's authorization page.
     * @return void
     */
    public function login() {
        $parameters = array('client_id' => $this->oauth_client_id, 'scope' => $this->scope, 'redirect_uri' => $this->redirect_uri, 'response_type' => 'code');
        header('Location: https://discordapp.com/api/oauth2/authorize?' . http_build_query($parameters));
        die();
    }

    /**
     * Redirect user to Discord's revocation page.
     * @return void
     */
    public function logout() {
        $parameters = array('client_id' => $this->oauth_client_id, 'client_secret' => $this->oauth_client_secret, 'token' => $_SESSION['access_token']);
        $this->curl('https://discordapp.com/api/oauth2/token/revoke');
        unset($_SESSION['access_token']);
        header('Location: ' . $_SERVER['PHP_SELF']);
        die();
    }

    /**
     * Returns a user's info.
     * Requires the identify scope.
     * @return string
     */
    public function getUser() {
        return $this->curl('https://discord.com/api/users/@me');
    }

    /**
     * Returns a user's guilds.
     * Requires the guilds scope.
     * @return string
     */
    public function getGuilds() {
        return $this->curl('https://discord.com/api/users/@me/guilds');
    }

    /**
     * Returns true if user is logged in.
     * @return bool
     */
    public function loggedIn() {
        return isset($_SESSION['access_token']);
    }

    /**
     * Get a user's access token.
     * @return void
     */
    public function getAccessToken($code) {
        $parameters = array('client_id' => $this->oauth_client_id, 'client_secret' => $this->oauth_client_secret, 'redirect_uri' => $this->redirect_uri, 'grant_type' => 'authorization_code', 'code' => $code);
        $token = $this->curl('https://discord.com/api/oauth2/token', $parameters);
        if(isset($token->access_token)) $_SESSION['access_token'] = $token->access_token;
        header('Location: ' . $_SERVER['PHP_SELF']);
        die();
    }

    /**
     * Used to curl to Discord's API.
     * @return string
     */
    private function curl($url, $params = false) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        if($params) curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));

        $headers = array('Accept: application/json');
        if(isset($_SESSION['access_token'])) array_push($headers, 'Authorization: Bearer ' . $_SESSION['access_token']);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $response = curl_exec($ch);
        return json_decode($response);
    }

    /**
     * Construct the class.
     * @return void
     */
    public function __construct($client_id, $client_secret, $scope, $redirect_uri = '') {
        $this->oauth_client_id = $client_id;
        $this->oauth_client_secret = $client_secret;
        $this->scope = $scope;
        $this->redirect_uri = $redirect_uri;
        session_start();
    }

}

?>
