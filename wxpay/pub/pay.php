<?php
session_start();
ini_set('date.timezone','Asia/Shanghai');
//error_reporting(E_ERROR);
require_once "../lib/WxPay.Api.php";
require_once "WxPay.JsApiPay.php";
require_once 'log.php';

// param
$openid      = $_POST['openid'];
$grouptype   = $_POST['grouptype'];

// body content
$body        = $grouptype != "family" ? "100元一般跑" : "200元家庭跑";
$fee         = $grouptype != "family" ? "10000" : "20000";
$outtradeno  = WxPayConfig::MCHID.date("YmdHis");
$tools       = new JsApiPay();

// generate JsApi
$input = new WxPayUnifiedOrder();
$input->SetBody($body);
$input->SetAttach($body);
$input->SetOut_trade_no($outtradeno);
$input->SetTotal_fee($fee);
$input->SetTime_start(date("YmdHis"));
$input->SetTime_expire(date("YmdHis", time() + 600));
$input->SetGoods_tag($body);
$input->SetNotify_url("https://pay.wechat.createcdigital.com/molirun-wxpayapi/wxpay/pub/notify.php");
$input->SetTrade_type("JSAPI");
$input->SetOpenid($openid);
$order = WxPayApi::unifiedOrder($input);
$jsApiParameters = $tools->GetJsApiParameters($order);

echo $jsApiParameters;