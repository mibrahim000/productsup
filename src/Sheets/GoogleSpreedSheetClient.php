<?php


namespace App\Sheets;

use Google_Client;
use Google_Service_Sheets;

class GoogleSpreedSheetClient
{
    public function __construct(
        protected Google_Client $googleClient,
        protected string $configPath
    )
    {
        $this->boot();
        $this->getToken();
    }

    protected function boot()
    {
        $this->googleClient->setApplicationName('Google Sheets API PHP Quickstart');
        $this->googleClient->setScopes(Google_Service_Sheets::SPREADSHEETS);
        $this->googleClient->setAuthConfig(__DIR__ . '/../../'.$this->configPath);
        $this->googleClient->setAccessType('offline');
        $this->googleClient->setPrompt('select_account consent');
    }

    protected function getToken()
    {
        $tokenPath = 'google_token.json';

        if (file_exists($tokenPath)) {
            $accessToken = json_decode(file_get_contents($tokenPath), true);
            $this->googleClient->setAccessToken($accessToken);
        }

        if ($this->googleClient->isAccessTokenExpired()) {
            // Refresh the token if possible, else fetch a new one.
            if ($this->googleClient->getRefreshToken()) {
                $this->googleClient->fetchAccessTokenWithRefreshToken($this->googleClient->getRefreshToken());
            } else {
                // Request authorization from the user.
                $authUrl = $this->googleClient->createAuthUrl();
                printf("Open the following link in your browser:\n%s\n", $authUrl);
                print 'Enter verification code: ';
                $authCode = trim(fgets(STDIN));

                // Exchange authorization code for an access token.
                $accessToken = $this->googleClient->fetchAccessTokenWithAuthCode($authCode);
                $this->googleClient->setAccessToken($accessToken);

                // Check to see if there was an error.
                if (array_key_exists('error', $accessToken)) {
                    throw new $this->googleClient(join(', ', $accessToken));
                }
            }

            // Save the token to a file.
            if (!file_exists(dirname($tokenPath))) {
                mkdir(dirname($tokenPath), 0700, true);
            }

            file_put_contents($tokenPath, json_encode($this->googleClient->getAccessToken()));
        }
    }

    public function getService(): Google_Service_Sheets
    {
        return new Google_Service_Sheets($this->googleClient);
    }
}