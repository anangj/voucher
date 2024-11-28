<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Google\Client;
use Google\Service\Gmail;
use Google\Service\Gmail\Message;
use Illuminate\Support\Facades\Cache;

class SendGmailApiEmail implements ShouldQueue
{
    private $client;

    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $to;
    protected $subject;
    protected $messageText;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($to, $subject, $messageText)
    {
        $this->to = $to;
        $this->subject = $subject;
        $this->messageText = $messageText;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $client = new Client();
        $client->setClientId(env('GOOGLE_CLIENT_ID'));
        $client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
        $client->setRedirectUri(env('GOOGLE_REDIRECT'));
        $client->setAccessToken($this->getAccessToken());

        $gmail = new Gmail($client);
        $gmailMessage = new Message();

        $rawMessageString = "To: {$this->to}\r\n";
        $rawMessageString .= "Subject: {$this->subject}\r\n";
        $rawMessageString .= "Content-Type: text/plain; charset=utf-8\r\n\r\n";
        $rawMessageString .= $this->messageText;

        // Base64 encode and make it URL-safe
        $rawMessage = base64_encode($rawMessageString);
        $rawMessage = str_replace(['+', '/', '='], ['-', '_', ''], $rawMessage);
        $gmailMessage->setRaw($rawMessage);

        $result = $gmail->users_messages->send('me', $gmailMessage);
        return $result;
    }

    protected function getAccessToken()
    {
        // Attempt to get the access token from cache
        $accessToken = Cache::get('google_access_token');

        // If no access token or token expired, refresh it
        if (!$accessToken || $this->isTokenExpired($accessToken)) {
            $client = new Client();
            $client->setClientId(env('GOOGLE_CLIENT_ID'));
            $client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
            $client->setRedirectUri(env('GOOGLE_REDIRECT'));
            $client->setAccessToken(env('GOOGLE_REFRESH_TOKEN'));

            // Refresh the token if needed
            if ($client->isAccessTokenExpired()) {
                $client->fetchAccessTokenWithRefreshToken(env('GOOGLE_REFRESH_TOKEN'));
                $newAccessToken = $client->getAccessToken();
                
                // Cache the new access token
                Cache::put('google_access_token', $newAccessToken, now()->addSeconds($newAccessToken['expires_in']));

                return $newAccessToken['access_token'];
            }

            return $client->getAccessToken()['access_token'];
        }
        dd($accessToken['access_token']);
        return $accessToken['access_token'];
    }

    protected function isTokenExpired($token)
    {
        return $token['created'] + $token['expires_in'] < time();
    }

}
