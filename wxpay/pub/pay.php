<?php
session_start();
ini_set('date.timezone','Asia/Shanghai');
//error_reporting(E_ERROR);
require_once "../lib/WxPay.Api.php";
require_once "WxPay.JsApiPay.php";

require_once 'log.php';
//初始化日志
$logHandler= new CLogFileHandler("../logs/".date('Y-m-d').'.log');
$log = Log::Init($logHandler, 15);



// param from POST Request
$openid     = $_POST['openid'];
$grouptype  = $_POST['grouptype'];
$outtradeno = $_POST['outtradeno'];


if(isset($openid) && isset($grouptype) && isset($openid))
{
    //            body content
    $body         = $grouptype != "家庭跑" ? "100元一般跑" : "200元家庭跑";
    $fee          = $grouptype != "家庭跑" ? "10000" : "20000";

    // generate JsApi
    $input = new WxPayUnifiedOrder();
    $input->SetDevice_info("WEB");
    $input->SetBody($body);
    $input->SetOut_trade_no($outtradeno);
    $input->SetTotal_fee($fee);
    $input->SetTime_start(date("YmdHis"));
    $input->SetTime_expire(date("YmdHis", time() + 600));
    $input->SetGoods_tag($body);
    $input->SetAttach($body);
    $input->SetTrade_type("JSAPI");
    $input->SetOpenid($openid);
    $order           = WxPayApi::unifiedOrder($input);

    getUnifiedOrderResult($order);

    $tools           = new JsApiPay();
    return $tools->GetJsApiParameters($order);
}else
{
    Log::DEBUG("===timeStamp:".date("YmdHis")." pay.php, request wxpayapi fail, because missing must param.");

    return json_encode(["result" => "FAIL", "message" => "missing must param"]);
}


function getUnifiedOrderResult($UnifiedOrderResult)
{
    $result = "SUCCESS";

    if(!array_key_exists("appid", $UnifiedOrderResult)
        || !array_key_exists("prepay_id", $UnifiedOrderResult)
        || $UnifiedOrderResult['prepay_id'] == "")
    {
        $result = "FAIL, message: ".$UnifiedOrderResult['return_code'].$UnifiedOrderResult['return_msg'];

        Log::DEBUG("===timeStamp:".date("YmdHis")." pay.php, request wxpayapi fail, result: ".$result);
    }
}