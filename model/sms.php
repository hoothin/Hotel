<?php 
require $_SERVER['DOCUMENT_ROOT'] . '/sdk/vendor/autoload.php';

use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;

function sendOrderSuccess($phone, $name, $startDate, $endDate, $hotel, $room, $pos){
    AlibabaCloud::accessKeyClient('LTAI4Fx8NhyDbtmpVphcYdwL', 'GE3WwRdrtwmvTw31NUY7dY5Zaz7WkV')
                        ->regionId('cn-hangzhou')
                        ->asDefaultClient();
    try {
        $result = AlibabaCloud::rpc()
                              ->product('Dysmsapi')
                              // ->scheme('https') // https | http
                              ->version('2017-05-25')
                              ->action('SendSms')
                              ->method('POST')
                              ->host('dysmsapi.aliyuncs.com')
                              ->options([
                                            'query' => [
                                              'RegionId' => "cn-hangzhou",
                                              'PhoneNumbers' => $phone,
                                              'TemplateParam' => "{'name':'".$name."','startDate':'".$startDate."','endDate':'".$endDate."','hotel':'".$hotel."','room':'".$room."','pos':'".$pos."'}",
                                              'SignName' => "Hoothin酒店集团",
                                              'TemplateCode' => "SMS_175582184",
                                            ],
                                        ])
                              ->request();
        print_r($result->toArray());
    } catch (ClientException $e) {
        echo $e->getErrorMessage() . PHP_EOL;
    } catch (ServerException $e) {
        echo $e->getErrorMessage() . PHP_EOL;
    }
}
/**
 * 发送阿里云短信
 * @param  [type] $phone 手机号
 * @param  [type] $code  验证码
 * @param  [type] $type  类型 
 * 0 身份验证验证码 SMS_175350500
 * 1 登录确认验证码 SMS_175350499
 * 2 用户注册验证码 SMS_175350497
 * @return [type]        
 */
function sendSms($phone, $code, $type){
    switch ($type) {
        case 0:
            $type="SMS_175350500";
            break;
        case 1:
            $type="SMS_175350499";
            break;
        case 2:
            $type="SMS_175350497";
            break;
        default:
            $type="SMS_175350500";
            break;
    }
    AlibabaCloud::accessKeyClient('LTAI4Fx8NhyDbtmpVphcYdwL', 'GE3WwRdrtwmvTw31NUY7dY5Zaz7WkV')
                        ->regionId('cn-hangzhou')
                        ->asDefaultClient();
    try {
        $result = AlibabaCloud::rpc()
                              ->product('Dysmsapi')
                              // ->scheme('https') // https | http
                              ->version('2017-05-25')
                              ->action('SendSms')
                              ->method('POST')
                              ->host('dysmsapi.aliyuncs.com')
                              ->options([
                                            'query' => [
                                              'RegionId' => "cn-hangzhou",
                                              'PhoneNumbers' => $phone,
                                              'TemplateParam' => "{'code':'".$code."'}",
                                              'SignName' => "Hoothin酒店集团",
                                              'TemplateCode' => $type,
                                            ],
                                        ])
                              ->request();
        print_r($result->toArray());
    } catch (ClientException $e) {
        echo $e->getErrorMessage() . PHP_EOL;
    } catch (ServerException $e) {
        echo $e->getErrorMessage() . PHP_EOL;
    }
}
?>
