<?php

namespace App\Utility;

use App\Models\Config;
use SoapClient;
use stdClass;

trait SMSUtility
{
    public function getConfig()
    {
        $configs = Config::whereIn('id', [
            'sms_username',
            'sms_password',
            'sms_number',
            'sms_url',
        ])->pluck('value', 'key')->toArray();

        $result = new stdClass();
        $result->url = $configs['sms_url'];
        $result->number = $configs['sms_number'];
        $result->username = $configs['sms_username'];
        $result->password = $configs['sms_password'];
        return $result;
    }

    public function soap($url)
    {
        ini_set("soap.wsdl_cache_enabled", "0");
        $sms_client = new SoapClient($url, array('encoding' => 'UTF-8'));
        return $sms_client;
    }

    public function parameters($number, $username, $password, $mobile)
    {
        $parameters['username'] = $username;
        $parameters['password'] = $password;
        $parameters['from'] = $number;
        $parameters['to'] = $mobile;
        return $parameters;
    }

    public function sendSMS($text, $mobile)
    {
        $url = '/Send.asmx?wsdl';
        $config = $this->getConfig();
        $sms_client = $this->soap($config->url . $url);
        $parameters = $this->parameters($config->number, $config->username, $config->password, $mobile);
        $parameters['text'] = $text;
        $parameters['isflash'] = false;
        $send = $sms_client->SendSimpleSMS2($parameters)->SendSimpleSMS2Result;
        return $send;
    }

    public function sendVoice($mobile, $text)
    {
        $url = '/Voice.asmx?wsdl';
        $config = $this->getConfig();
        $sms_client = $this->soap($config->url . $url);
        $parameters = $this->parameters($config->number, $config->username, $config->password, $mobile);
        $parameters['smsBody'] = $text;
        $parameters['speechBody'] = $text;
        $voice = $sms_client->SendSMSWithSpeechText($parameters)->SendSMSWithSpeechTextResult;
        return $voice;
    }

    public function sendServiceSMS($mobile, $text, $bodyId)
    {
        $url = '/Send.asmx?wsdl';
        $config = $this->getConfig();
        $sms_client = $this->soap($config->url, $url);
        $parameters = $this->parameters($config->number, $config->username, $config->password, $mobile);
        $parameters['text'] = $text;
        $parameters['bodyId'] = $bodyId;
        $serviceSMS = $sms_client->SendByBaseNumber($parameters)->SendByBaseNumberResult;
        return $serviceSMS;
    }

    public function getSMS($recId)
    {
        $url = '/Send.asmx?wsdl';
        $config = $this->getConfig();
        $sms_client = $this->soap($config->url, $url);
        $parameters['recId'] = $recId;
        $parameters['username'] = env('SMS_USERNAME');
        $parameters['password'] = env('SMS_PASSWORD');
        $get = $sms_client->GetDeliveries2($parameters)->GetDeliveries2Result;
        return $get;
    }
}
