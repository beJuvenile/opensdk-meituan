# opensdk-meituan

### 介绍
本类库是对美团开放平台API的封装  


#### 使用示例
~~~php
require 'vendor/autoload.php';

use OpenSDK\Meituan\Client;
use OpenSDK\Meituan\Requests\MeituanOrdersQueryGetRequest;

$c = new Client();
$c->appKey = $this->appKey;
$c->appSecret = $this->appSecret;
$c->gatewayUrl = $this->url;
$req = new MeituanOrdersQueryGetRequest();

$oid = '';
//订单id
$req->setOid($oid);
//平台唯一标识
$req->setSid('326c433346a4760ef5e2817b84de0b4f');
$req->setType(4);

$c->execute($req);
$response = $c->result();

if(empty($response) || !isset($response[0]['order']['orderid'])){
    echo '无结果';exit;
}
var_dump($response);exit;
~~~