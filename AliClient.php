<?php
/**
 * User: 伯仁 (爱用)
 * email: liuzailin@loveapp.cn
 * Date: 2014-10-30
 */
class AliClient
{
	public $appkey;

	public $secretKey;
	
	public $api;

	public $gatewayUrl = "http://gw.open.1688.com/openapi/";
	
	public $urlPath="param2/1/cn.alibaba.open/";
	
	

	/** 是否打开入参check**/
	public $checkRequest = true;
	
	protected $signMethod = "sha1";
	
	protected $sdkVersion = "ali-sdk-php-20141030";
	
	protected function generateSign($params)
	{
		ksort($params);
		$sign_str = "";
    	foreach ($params as $key=>$val)
        $sign_str .= $key . $val;
		$sign_str = $this->urlPath.$this->api.'/'.$this->appkey. $sign_str;
		//echo '<br/>sign url:'.$sign_str.'<br/>';
    	$code_sign = strtoupper(bin2hex(hash_hmac($this->signMethod, $sign_str, $this->secretKey, true)));
		return $code_sign;
	}
	
	
	public function execute($request, $session = null)
	{
		if($this->checkRequest) {
			try {
				$request->check();
			} catch (Exception $e) {
				$result->code = $e->getCode();
				$result->msg = $e->getMessage();
				return $result;
			}
		}
		
		//获取业务参数
		$apiParams = $request->getApiParas();
		//组装系统参数
		if (null != $session)
		{
			$apiParams["access_token"] = $session;
		}
		 
		//$dt = new DateTime();
		//$apiParams['_aop_timestamp']= $dt->getTimestamp();
		//签名
		$apiParams['_aop_signature'] = $this->generateSign($apiParams);
		//echo '   _aop_signature:'.$apiParams['_aop_signature'];
		$requestUrl = $this->gatewayUrl . $this->urlPath .$this->api.'/'.$this->appkey;
		$postData = "";
		
		foreach ($apiParams as $sysParamKey => $sysParamValue)
		{
			$postData .= "$sysParamKey=" . urlencode($sysParamValue) . "&";
		}
		
		//$postData = substr($postData, 0, -1);
		//echo  '<br/>    post:'.$postData.'<br/>';
		//发起HTTP请求
		try
		{
			$resp = $this->curl($requestUrl, $postData);
		}catch (Exception $e)
		{
			return json_encode($e);
		}
		
		return $resp;
		
	}
	
	
	public function curl ($post_url,$postData)
	{
		$header = array("content-type: application/x-www-form-urlencoded;charset=UTF-8");
		$ch = curl_init();
		
		curl_setopt($ch,CURLOPT_HTTPHEADER,$header);
        curl_setopt($ch, CURLOPT_URL,$post_url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        $pagecontent = curl_exec($ch);
        curl_close($ch);
		
		return $pagecontent;
	}
	
}
?>