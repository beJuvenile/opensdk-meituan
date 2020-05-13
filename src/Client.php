<?php
/**
 * 客户端
 *
 * User: Ken.Zhang <kenphp@yeah.net>
 * Date: 2019/9/22
 * Time: 20:54
 */
namespace OpenSDK\MeiTuan;

use OpenSDK\MeiTuan\Libs\Http;
use OpenSDK\MeiTuan\Interfaces\Request;

class Client
{

    /**
     * 接口地址
     *
     * @var string
     */
    public $gatewayUrl;

    /**
     * AppKey
     *
     * @var string
     */
    public $appKey;

    /**
     * AppSecret
     *
     * @var string
     */
    public $appSecret;

    /**
     * 数据类型
     *
     * @var string
     */
    public $format = 'json';

    /**
     * request对象
     * 
     * @var object
     */
    public $request;

    /**
     * result对象
     *
     * @var object
     */
    public $result = [];

    /**
     * 签名方式
     *
     * @var object
     */
    public $signMethod = 'md5';

    public function __construct($appKey='', $appSecret='', $gatewayUrl='')
    {
        $this->appKey = $appKey;
        $this->appSecret = $appSecret;
        $this->gatewayUrl = $gatewayUrl;
    }
    
    //执行
    public function execute(Request $request)
    {
        $this->request = $request;

        // 系统级参数
        $sysParams = [];

        //获取业务参数
        $apiParams = $request->getApiParas();

        $apiParams['key'] = $this->appKey;
        $apiParams['full'] = 1;
        //签名
        $apiParams["sign"] = $this->generateSign($apiParams);
        //发起HTTP请求
        try{
            //系统参数放入GET请求串
            $requestUrl = $this->gatewayUrl . $this->request->getApiMethodName() . ($sysParams ? '?' : '');
            foreach ($sysParams as $sysParamKey => $sysParamValue){
                $requestUrl .= "$sysParamKey=" . urlencode($sysParamValue) . "&";
            }
            $requestUrl = rtrim($requestUrl, '&');

            if (strtolower($request->requestType)=='post') {
                $resp = Http::post($requestUrl, $apiParams, [], $request->dataType);
            } else {
                $resp = Http::get($requestUrl, $apiParams);
            }
            $respObject = [];
            //解析TOP返回结果
            if ("json" == $this->format){
                $respObject = json_decode($resp,true);
            }else if("xml" == $this->format){
                $respObject = @simplexml_load_string($resp);
            }
            if($apiParams['oid']){
                $this->result[0] = $respObject;
            }else{
                $this->result = $respObject;
            }
        }catch (\Exception $e){
            $this->result = [];
        }
    }

    //生成签名
    public function generateSign($params)
    {
        ksort($params);
        $secret = $this->appSecret;
        $str = $secret; // $secret为分配的密钥
        foreach($params as $key => $value) {
            $str .= $key . $value;
        }
        $str .= $secret;
        $sign = md5($str);
        return $sign;
    }

    //获取真实ip
    public function getip() {
        $unknown = 'unknown';
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] && strcasecmp($_SERVER['HTTP_X_FORWARDED_FOR'], $unknown)) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], $unknown)) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        /*
        处理多层代理的情况
        或者使用正则方式：$ip = preg_match("/[\d\.]{7,15}/", $ip, $matches) ? $matches[0] : $unknown;
        */
        if (false !== strpos($ip, ',')) $ip = reset(explode(',', $ip));
        return $ip;
    }


    /**
     * 获取执行结果
     *
     * @return  mixed
     */
    public function result()
    {
        return $this->request->getResult($this->result);
    }

}