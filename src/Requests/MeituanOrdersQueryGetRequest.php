<?php
/**
 * API: meituan.orders.query.get 查询美团订单
 *
 * @author limo
 * @since 1.0, 2018.07.25
 */
namespace OpenSDK\MeiTuan\Requests;
use OpenSDK\MeiTuan\Interfaces\Request;

class MeituanOrdersQueryGetRequest implements Request
{

    public $requestType = 'get';

    public $dataType = 'json';

    //订单类型  外卖4 酒店2
    public $type = 4;

    //订单id
    public $oid = '';

    //用户sid，作为平台的唯一标识
    public $sid = '';

    public $apiParas = array();

    public function setType($type)
    {
        $this->type = $type;
        $this->apiParas["type"] = $type;
    }

    public function setOid($oid)
    {
        $this->oid = $oid;
        $this->apiParas["oid"] = $oid;
    }

    public function setSid($sid)
    {
        $this->sid = $sid;
        $this->apiParas["sid"] = $sid;
    }


	public function getApiMethodName()
	{
		return '/api/rtnotify';
	}

	public function getApiParas()
	{
		return $this->apiParas;
	}

	public function check()
	{

	}

    /**
     * 解析结果
     *
     * @param   $response   array
     * @return  array
     */
    public function getResult($response)
    {
        return $response;
    }

}
