<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Google\Client;
use Google\Service\Gmail;
use Google\Service\Gmail\Message;
use App\Models\GoogleToken;
use Illuminate\Support\Carbon;

class EmailController extends Controller
{
    private $client;

    public function __construct()
    {
        // $this->client = new Client();
        // $this->client->setClientId(env('GOOGLE_CLIENT_ID'));
        // $this->client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
        // $this->client->setRedirectUri(env('GOOGLE_REDIRECT_URI'));
        // $this->client->addScope(Gmail::GMAIL_SEND);


    }



    
    public function handleCallback(Request $request)
    {
        if (!$request->has('code')) {
            return redirect('/')->with('error', 'Authorization code not available');
        }

        $token = $this->client->fetchAccessTokenWithAuthCode($request->query('code'));

        if (isset($token['error'])) {
            return redirect('/')->with('error', 'Failed to retrieve tokens');
        }

        GoogleToken::updateOrCreate(
            ['user_id' => auth()->id()],
            [
                'access_token' => $token['access_token'],
                'refresh_token' => $token['refresh_token'] ?? null,
                'expires_in' => $token['expires_in'],
            ]
        );
        $this->sendEmailToPatient();
        return redirect('/')->with('success', 'Authorization successful!');
    }

    public function redirectToAuthUrl()
    {
        $token = GoogleToken::where('user_id', auth()->id())->first();

        if ($token && !$this->tokenIsExpired($token)) {
            // Token exists and is still valid, set it on the client
            $this->client->setAccessToken($token->access_token);

            // Directly call the method to send an email
            $this->sendHtmlEmail();
            return redirect('/')->with('success', 'Authorization successful!');
        }

        // If token is missing or expired, generate the authorization URL
        $authUrl = $this->client->createAuthUrl();
        return redirect($authUrl);
    }

    private function setAccessToken()
    {
        $token = GoogleToken::where('user_id', auth()->id())->first();

        if (!$token) {
            return redirect()->route('redirect-to-auth-url')->with('error', 'Authorization required');
        }

        if ($this->tokenIsExpired($token)) {
            $newToken = $this->client->fetchAccessTokenWithRefreshToken($token->refresh_token);

            if (isset($newToken['error'])) {
                return redirect()->route('redirect-to-auth-url')->with('error', 'Failed to refresh tokens');
            }

            $token->update([
                'access_token' => $newToken['access_token'],
                'expires_in' => $newToken['expires_in'],
            ]);
        }

        $this->client->setAccessToken($token->access_token);
    }

    private function tokenIsExpired($token)
    {
        return Carbon::now()->diffInSeconds($token->updated_at) >= $token->expires_in;
    }

    public function sendEmailToPatient()
    {
        $this->setAccessToken();

        $to = "anang123julian@gmail.com";
        $subject = "Voucher Orders";
        $messageText = 'This is a test email sent to multiple recipients using the Gmail API from a Laravel application.';

        $message = new Message();

        $rawMessageString = "To: " . $to . "\r\n";
        $rawMessageString .= "Subject: {$subject}\r\n";
        $rawMessageString .= "MIME-Version: 1.0\r\n";
        $rawMessageString .= "Content-Transfer-Encoding: quoted-printable\r\n\r\n";
        $rawMessageString .= "<p>{$messageText}</p>";

        // URL-safe base64 encode the message
        $rawMessage = base64_encode($rawMessageString);
        $rawMessage = str_replace(['+', '/', '='], ['-', '_', ''], $rawMessage); // URL-safe

        $message->setRaw($rawMessage);

        $service = new Gmail($this->client);
        try {
            $service->users_messages->send('me', $message);
            return 'Email sent successfully.';
        } catch (\Exception $e) {
            return 'An error occurred: ' . $e->getMessage();
        }
    }

    public function sendHtmlEmail()
    {
        $this->setAccessToken();

        $to = 'anang123julian@gmail.com';
        $subject = 'Hello, HTML Email!';
        $htmlContent = view('emails.evoucher')->render();

        // MIME Type message
        $boundary = uniqid(rand(), true);
        $subjectCharset = $charset = 'utf-8';

        $messageBody = "--{$boundary}\r\n";
        $messageBody .= "Content-Type: text/html; charset={$charset}\r\n";
        $messageBody .= "Content-Transfer-Encoding: quoted-printable\r\n\r\n";
        $messageBody .= "{$htmlContent}\r\n";
        $messageBody .= "--{$boundary}--";

        $rawMessage = "To: {$to}\r\n";
        $rawMessage .= "Subject: =?{$subjectCharset}?B?" . base64_encode($subject) . "?=\r\n";
        $rawMessage .= "MIME-Version: 1.0\r\n";
        $rawMessage .= "Content-Type: multipart/alternative; boundary=\"{$boundary}\"\r\n\r\n";
        $rawMessage .= $messageBody;

        $rawMessage = base64_encode($rawMessage);
        $rawMessage = str_replace(['+', '/', '='], ['-', '_', ''], $rawMessage); // URL-safe

        $gmailMessage = new Message();
        $gmailMessage->setRaw($rawMessage);

        $service = new Gmail($this->client);
        try {
            $service->users_messages->send('me', $gmailMessage);
            return 'HTML email sent successfully.';
        } catch (\Exception $e) {
            return 'An error occurred: ' . $e->getMessage();
        }
    }

    public function sendEmailWithAttachments()
    {
        $this->setAccessToken();

        $subject = 'Subject with Attachments';
        $to = 'anang123julian@gmail.com';
        $messageText = 'This is a test email with attachments sent through the Gmail API from a Laravel application.';

        // Construct the MIME message with attachment
        $boundary = uniqid(rand(), true);
        $subjectCharset = $charset = 'utf-8';

        $messageBody = "--{$boundary}\r\n";
        $messageBody .= "Content-Type: text/plain; charset={$charset}\r\n";
        $messageBody .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
        $messageBody .= "{$messageText}\r\n";

        // Attachments
        $filePath = storage_path('app/vouchers/Anang Julyanto/V202410141001.pdf');
        $fileName = 'V202410141001.pdf';
        $fileData = file_get_contents($filePath);
        $base64File = base64_encode($fileData);

        $messageBody .= "--{$boundary}\r\n";
        $messageBody .= "Content-Type: application/pdf; name={$fileName}\r\n";
        $messageBody .= "Content-Description: {$fileName}\r\n";
        $messageBody .= "Content-Disposition: attachment; filename={$fileName}; size=" . filesize($filePath) . "\r\n";
        $messageBody .= "Content-Transfer-Encoding: base64\r\n\r\n";
        $messageBody .= "{$base64File}\r\n";
        $messageBody .= "--{$boundary}--";

        $rawMessage = "To: {$to}\r\n";
        $rawMessage .= "Subject: =?{$subjectCharset}?B?" . base64_encode($subject) . "?=\r\n";
        $rawMessage .= "MIME-Version: 1.0\r\n";
        $rawMessage .= "Content-Type: multipart/mixed; boundary=\"{$boundary}\"\r\n\r\n";
        $rawMessage .= $messageBody;

        $rawMessage = base64_encode($rawMessage);
        $rawMessage = str_replace(['+', '/', '='], ['-', '_', ''], $rawMessage); // URL-safe

        $gmailMessage = new Message();
        $gmailMessage->setRaw($rawMessage);

        $service = new Gmail($this->client);
        try {
            $service->users_messages->send('me', $gmailMessage);
            return 'Email with attachments sent successfully.';
        } catch (\Exception $e) {
            return 'An error occurred: ' . $e->getMessage();
        }
    }
}
