<?php

//namespace Sms;

use http\Env\Response;

class SendSms
{
    public array $param=array(
        "adminTemplate"=>"otp-password",
        "userTemplate"=>"otp-password",
        "adminPhoneNumber"=>"09369896983",
        "apiKey"=>"723454473278747443444E65453776625A6A706A59773167416E3768724F4336"
    );



 function sendMsgToAdmin(){

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.kavenegar.com/v1/'.$this->param['apiKey'].'/verify/lookup.json?receptor='.$this->param['adminPhoneNumber'].'&token=token%&template='.$this->param['adminTemplate'],//receptor={$receptor}&token=token%&template=verify',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);
        curl_close($curl);


    }


    //--------------------------------
    //public static function sendMsgToUser($mobile,$email){
    function sendMsgToUser($phone, $otp){
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.kavenegar.com/v1/'.$this->param['apiKey'].'/verify/lookup.json?receptor='.$phone.'&token='.$otp.'&template='.$this->param['userTemplate'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);
        curl_close($curl);
    }
}
