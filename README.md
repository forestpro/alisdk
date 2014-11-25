alisdk
======

1688 开放平台 php 版SDK
由于时间仓促，有很多request类没有验证，欢迎大家使用并验证提交代码！


Demo for php:

require_once ('/alisdk/AliClient.php');</br>
require_once ('/alisdk/RequestCheckUtil.php');</br>
</br>
require_once ('/alisdk/request/AliCompanyGetRequest.php');</br>
// 获取单个公司库信息

function(){
 $session =  $this->getSession();
  $client = new AliClient();
  $client->appkey = "you appkey";
 $client->secretKey = "you secretKey";
  $client->api = "company.get";
 
   $request = new AliCompanyGetRequest();
   $request->setMemberId($session->memberId);
   $request->setFields("memberId,companyId,companyStatus,companyName,companyNameEN,account,productionService,companyCategoryInfo,foundedPlace");
 
  $rsp =  $client->execute($request,$session->access_token);

  echo  $rsp;
 }
