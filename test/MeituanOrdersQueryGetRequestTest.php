<?php
/**
 * Created by PhpStorm.
 * User: Ken.Zhang
 * Date: 2019/9/23
 * Time: 11:46
 */
require '../vendor/autoload.php';

use OpenSDK\MeiTuan\Client;
use OpenSDK\MeiTuan\Requests\MeituanOrdersQueryGetRequest;

class MeituanOrdersQueryGetRequestTest
{

    private $appKey = 'bc4ec48acd2b9d01136778f3734e3443529';

    private $appSecret = 'd7f10683a67ea46d';

    private $url = 'https://runion.meituan.com';

    public function __invoke()
    {
        $c = new Client();
        $c->appKey = $this->appKey;
        $c->appSecret = $this->appSecret;
        $c->gatewayUrl = $this->url;
        $req = new MeituanOrdersQueryGetRequest();

        $oid = '';
        $req->setOid($oid);
        $req->setSid('326c433346a4760ef5e2817b84de0b4f');
        $req->setType(4);

        $c->execute($req);
        $response = $c->result();

        if(empty($response) || !isset($response[0]['order']['orderid'])){
            echo '无结果';exit;
        }
        var_dump($response);exit;

    }

}

(new MeituanOrdersQueryGetRequestTest())();

