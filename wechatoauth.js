/* ==========================================================================
 * WeChat OAuth
 * ---------------------------------------------------------------------------
 * Author: Coton
 * Email: coton.chen@createcdigital.com
 * ========================================================================== */
(function(){
	var weChatOAuthURL = 'http://molirun.wechat.createcdigital.com/oauth?redirecturl=';

	var getWehcatUserInfo = function (){
		if(getCookie('wechatUserInfo'))
		{
			document.getElementsByTagName("head")[0]
						.setAttribute("data-wechatuserinfo", "1");
			return getCookie('wechatUserInfo');
		}else
		{
			if(!/micromessenger/i.test(navigator.userAgent))
			{
				setCookie('wechatUserInfo', debugWechatUserInfoBase64(), 30)
			}else
			{
				var wechatUserInfoBase64 = getUrlParameter("data");
				if(wechatUserInfoBase64)
				{
					setCookie('wechatUserInfo', wechatUserInfoBase64, 30);
				}else
				{
					wechatoAuth();
				}
			}
		}
	};

	var wechatoAuth = function(){
		var oAtuhUrl = weChatOAuthURL + btoa(window.location.href);
		window.location.href = oAtuhUrl;
	};

	var getUrlParameter = function(sParam) {
	    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
	        sURLVariables = sPageURL.split('&'),
	        sParameterName,
	        i;

	    for (i = 0; i < sURLVariables.length; i++) {
	        sParameterName = sURLVariables[i].split('=');

	        if (sParameterName[0] === sParam) {
	            return sParameterName[1] === undefined ? true : sParameterName[1];
	        }
	    }
	}

	var setCookie = function(c_name,value,expiredays)
	{
		var exdate=new Date()
		exdate.setDate(exdate.getDate()+expiredays);
		document.cookie = c_name + "=" + escape(value)
						+((expiredays==null) ? "" : ";expires="+exdate.toGMTString());
	}

	var getCookie = function(c_name)
	{
		if (document.cookie.length>0)
		{
			var c_start=document.cookie.indexOf(c_name + "=");
			if (c_start!=-1)
			{
				c_start=c_start + c_name.length+1;
				var c_end=document.cookie.indexOf(";",c_start);
				if (c_end==-1) c_end=document.cookie.length
					return unescape(document.cookie.substring(c_start,c_end));
			}
		}
		return ""
	}

	var debugWechatUserInfoBase64 = function(){
		return "eyJpZCI6Im9JeXYwdy1UamVtNFBMczFscVpUWVF1Z04talkiLCJuYW1lIjoiY290b25fY2hlbiIsIm5pY2tuYW1lIjoiY290b25fY2hlbiIsImF2YXRhciI6Imh0dHA6XC9cL3d4LnFsb2dvLmNuXC9tbW9wZW5cL2FqTlZkcUhaTExEQlVINUIxZTNiMEpRMXZBN3lhZXQ5RXhmMjZIOWJsdmxkSEpGOUxROUw1QVlpYVJUOHZUVlcyd0VZWE8xQU81bUh4S1Z5TG1YaWFhOEFcLzAiLCJlbWFpbCI6bnVsbCwib3JpZ2luYWwiOnsib3BlbmlkIjoib0l5djB3LVRqZW00UExzMWxxWlRZUXVnTi1qWSIsIm5pY2tuYW1lIjoiY290b25fY2hlbiIsInNleCI6MSwibGFuZ3VhZ2UiOiJlbiIsImNpdHkiOiJcdTk3NTlcdTViODkiLCJwcm92aW5jZSI6Ilx1NGUwYVx1NmQ3NyIsImNvdW50cnkiOiJcdTRlMmRcdTU2ZmQiLCJoZWFkaW1ndXJsIjoiaHR0cDpcL1wvd3gucWxvZ28uY25cL21tb3BlblwvYWpOVmRxSFpMTERCVUg1QjFlM2IwSlExdkE3eWFldDlFeGYyNkg5Ymx2bGRISkY5TFE5TDVBWWlhUlQ4dlRWVzJ3RVlYTzFBTzVtSHhLVnlMbVhpYWE4QVwvMCIsInByaXZpbGVnZSI6W119LCJ0b2tlbiI6eyJhY2Nlc3NfdG9rZW4iOiJsV2VEb1dyTXVzLThkSWVxSm0zYk9rV1JnNFVwbFJoUTJPUFkwV241Y25jYW9KRjRsZDVjWUVnbmRPMmFCcEJyQzl5Y3BuR1p1Wm9yYW50Z0JHSk1uNXpVM2JXZWYxMWM3OU1SOWRJVmtybyIsImV4cGlyZXNfaW4iOjcyMDAsInJlZnJlc2hfdG9rZW4iOiJlam82UG1pNm03Wnc4Z3pMcmZXbXgtV1pqUUY4SkxpOHFUM1VQVEg4UmgtM0FNMVdjNmh3djhGcDk3eWVMWTBFOHgzRmdyS09jSG1uSUdZZ2xGSXpVb2NVUTlKMGhkZnlZREhwM1QxeVh6RSIsIm9wZW5pZCI6Im9JeXYwdy1UamVtNFBMczFscVpUWVF1Z04talkiLCJzY29wZSI6InNuc2FwaV91c2VyaW5mbyJ9fQ";
	};

	getWehcatUserInfo();
})();