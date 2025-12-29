<?php

namespace App\Http\Services\Message\SMS;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class MeliPayamakService
{
    private $username;
    private $password;

    public function __construct()
    {
        // خواندن اطلاعات از SiteSetting
        $settings = \App\Models\SiteSetting::getSettings();
        $this->username = $settings->melipayamak_username ?? '09223618018';
        $this->password = $settings->melipayamak_password ?? 'ML8Z9';
    }

    /**
     * Send SMS using Melipayamak API
     * استفاده از همان متد send که در کد اصلی بود
     *
     * @param string $from شماره خط ارسال کننده
     * @param string|array $to شماره موبایل گیرنده
     * @param string $text متن پیامک
     * @param bool $isFlash آیا پیامک فلش است یا نه
     * @return mixed RecId در صورت موفقیت یا false در صورت خطا
     */
    public function send($from, $to, $text, $isFlash = true)
    {
        try {
            $username = $this->username;
            $password = $this->password;
            
            // بررسی وجود کلاس MelipayamakApi
            if (class_exists('Melipayamak\MelipayamakApi')) {
                // استفاده از پکیج MelipayamakApi
                $api = new \Melipayamak\MelipayamakApi($username, $password);
                $sms = $api->sms();
                
                // تبدیل $to به رشته در صورت آرایه بودن
                if (is_array($to)) {
                    $to = implode(',', $to);
                }
                
                $response = $sms->send($to, $from, $text);
                $json = json_decode($response);
                
                if (isset($json->Value)) {
                    Log::info('SMS sent successfully (via API)', [
                        'to' => $to,
                        'from' => $from,
                        'rec_id' => $json->Value
                    ]);
                    return $json->Value; // RecId or Error Number
                } else {
                    Log::error('SMS sending failed (via API)', [
                        'to' => $to,
                        'from' => $from,
                        'response' => $response
                    ]);
                    return false;
                }
            } else {
                // استفاده از HTTP API در صورت عدم وجود پکیج
                $toArray = is_array($to) ? $to : [$to];
                
                $response = Http::asForm()->post('https://rest.payamak-panel.com/api/SendSMS/SendSMS', [
                    'username' => $username,
                    'password' => $password,
                    'to' => implode(',', $toArray),
                    'from' => $from,
                    'text' => $text,
                    'isFlash' => $isFlash
                ]);
                
                $result = $response->json();
                
                if (isset($result['RetStatus']) && $result['RetStatus'] == 1) {
                    if (isset($result['RecId']) && $result['RecId'] > 0) {
                        Log::info('SMS sent successfully (via HTTP)', [
                            'to' => implode(',', $toArray),
                            'from' => $from,
                            'rec_id' => $result['RecId']
                        ]);
                        return $result['RecId'];
                    }
                    Log::info('SMS sent successfully (via HTTP)', [
                        'to' => implode(',', $toArray),
                        'from' => $from
                    ]);
                    return true;
                } else {
                    Log::error('SMS sending failed (via HTTP)', [
                        'to' => implode(',', $toArray),
                        'from' => $from,
                        'response' => $result
                    ]);
                    return false;
                }
            }
        } catch (\Exception $e) {
            Log::error('SMS service exception', [
                'message' => $e->getMessage(),
                'to' => is_array($to) ? implode(',', $to) : $to,
                'from' => $from,
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }

    /**
     * Get account credit
     *
     * @return mixed موجودی حساب یا false در صورت خطا
     */
    public function getCredit()
    {
        try {
            // بررسی وجود کلاس MelipayamakApi
            if (class_exists('Melipayamak\MelipayamakApi')) {
                $api = new \Melipayamak\MelipayamakApi($this->username, $this->password);
                $sms = $api->sms();
                $response = $sms->getCredit();
                $json = json_decode($response);
                
                if (isset($json->Value)) {
                    return $json->Value;
                }
                return false;
            } else {
                // استفاده از HTTP API
                $response = Http::asForm()->post('https://rest.payamak-panel.com/api/SendSMS/GetCredit', [
                    'username' => $this->username,
                    'password' => $this->password
                ]);
                
                $result = $response->json();
                
                if (isset($result['RetStatus']) && $result['RetStatus'] == 1 && isset($result['Value'])) {
                    return $result['Value'];
                }
                return false;
            }
        } catch (\Exception $e) {
            Log::error('Get credit exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }
}






