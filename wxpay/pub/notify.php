<?php
ini_set('date.timezone','Asia/Shanghai');
error_reporting(E_ERROR);

require_once "../lib/WxPay.Api.php";
require_once '../lib/WxPay.Notify.php';
require_once 'log.php';

//初始化日志
$logHandler= new CLogFileHandler("../logs/".date('Y-m-d').'.log');
$log = Log::Init($logHandler, 15);

class PayNotifyCallBack extends WxPayNotify
{
	//查询订单
	public function Queryorder($transaction_id)
	{
		$input = new WxPayOrderQuery();
		$input->SetTransaction_id($transaction_id);
		$result = WxPayApi::orderQuery($input);
		Log::DEBUG("===timeStamp:".date("YmdHis")." notify.php, query order: " . json_encode($result));
		if(array_key_exists("return_code", $result)
			&& array_key_exists("result_code", $result)
			&& $result["return_code"] == "SUCCESS"
			&& $result["result_code"] == "SUCCESS")
		{
			return true;
		}
		return false;
	}

	//重写回调处理函数
	public function NotifyProcess($data, &$msg)
	{
		Log::DEBUG("===timeStamp:".date("YmdHis")." notify.php, call back: " . json_encode($data));
		$notfiyOutput = array();

		if(!array_key_exists("transaction_id", $data)){
			$msg = "输入参数不正确";
			return false;
		}
		//查询订单，判断订单真实性
		if(!$this->Queryorder($data["transaction_id"])){
			$msg = "订单查询失败";
			return false;
		}

		if(!$this->Queryorder($data["out_trade_no"])){
			$msg = "缺少商户订单号";
			return false;
		}

		// CALL BACK
		$this->UpdatePayStatusTODB($data);

		return true;
	}

	// 自定义的支付成功回调更新支付状态接口
	public function UpdatePayStatusTODB($data)
	{
		Log::DEBUG("===timeStamp:".date("YmdHis")." notify.php, start UpdatePayStatusTODB. transaction_id: ".$data["transaction_id"]);

		//url-ify the data for the POST
		foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
		rtrim($fields_string,'&');

		$ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, WxPayConfig::CALLBACK_URL);
	    curl_setopt($ch, CURLOPT_POST, count($data));
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    $res = curl_exec($ch);
	    curl_close($ch);
	}
}

Log::DEBUG("===timeStamp:".date("YmdHis")." notify.php, begin notify");
$notify = new PayNotifyCallBack();
$notify->Handle(false);
