<?php

namespace App\Services;

use Exception;
use Twilio\Rest\Client;

class OtpService
{
    protected $client;
    protected $accountPhone;
    protected $accountSid;
    protected $accountToken;

    public function __construct()
    {
        $this->accountSid = env('TWILIO_SID');
        $this->accountToken = env('TWILIO_TOKEN');
        $this->accountPhone = env('TWILIO_PHONE');
        $this->client = new Client($this->accountSid, $this->accountToken);
    }

    public function sendOtp($phoneNumber, $otp)
    {
        $message = "\n\nVerifikasi Code OTP Kamu Adalah: $otp\n\nHormat kami,\nPungliCare Customer Service\n\nJika ada permasalahan, bisa menghubungi email pungli.care@gmail.com";

        $this->client->messages->create(
            $phoneNumber,
            [
                'from' => $this->accountPhone,
                'body' => $message,
            ]
        );
    }

    public function generateOtp()
    {
        return rand(1000, 9999);
    }

    public function formatPhoneNumber($phoneNumber)
    {
        if (strpos($phoneNumber, '08') === 0) {
            return '+62' . substr($phoneNumber, 1);
        }
        throw new Exception('Nomor telepon harus dimulai dengan 08');
    }
}
