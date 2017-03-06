<?php
	session_start();

	require_once "weChatId.php";

	$callbackurl = str_replace('autho.php','getopenid.php','http://'.$_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"]);
	$callback = urlencode($callbackurl);
    $appid = $wAppid;

    //$scope='snsapi_userinfo';//需要用户授权公众号
    //$scope='snsapi_base';//静默授权
    $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$appid."&redirect_uri=".$callback."&response_type=code&scope=snsapi_base&state=99#wechat_redirect";

    header("Location:".$url);
?>