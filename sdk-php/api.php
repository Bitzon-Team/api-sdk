<?php

class Api{

    public $access_key = '';

    public $secret_key = '';

    public $api = 'https://api.bitzon.com';

    public function __construct($access_key,$secret_key)
    {
        $this->access_key = $access_key;
        $this->secret_key = $secret_key;
    }

    public function getMatchresults($symbol,$offsetId = 0,$limit = 20,$startDate = false,$endDate = false)
    {
        $query = [
            'symbol' => $symbol
        ];
        if(!empty($offsetId)){
            $query['offsetId'] = $offsetId;
        }
        if(!empty($limit)){
            $query['limit'] = $limit;
        }
        if(!empty($startDate)){
            $query['startDate'] = $startDate;
        }
        if(!empty($endDate)){
            $query['endDate'] = $endDate;
        }
        ksort($query);
        $this->api = $this->api.'/v1/trade/orders/matchresults?'.http_build_query($query);
        $header = $this->createGetHeaderSign();
        return $this->curlGet($this->api,$header);
    }

    public function cancelOrder($order_id)
    {
          $this->api = $this->api.'/v1/trade/orders/'.$order_id.'/cancel';
          $data = [
              'orderId' => $order_id
          ];
          $header = $this->createPostHeaderSign($data);
          return $this->curlPost($this->api,$data,$header);
    }

    public function createOrder($datas = [])
    {
        $this->api = $this->api.'/v1/trade/orders/';
        $orders = json_encode($datas);
        $header = $this->createPostHeaderSign($orders,true);
        return $this->curlPost($this->api,$orders,$header);
    }

    public function getOrderById($order_id)
    {
        $this->api = $this->api.'/v1/trade/orders/'.$order_id;
        $header = $this->createGetHeaderSign();
        return $this->curlGet($this->api,$header);
    }

    public function getOrders($offsetId = 0,$limit = 100,$symbol = '')
    {
        $query = [];
        if(!empty($offsetId)){
            $query['offsetId'] = $offsetId;
        }
        if(!empty($limit)){
            $query['limit'] = $limit;
        }
        if(!empty($symbol)){
            $query['symbol'] = $symbol;
        }
        ksort($query);
        $this->api = $this->api.'/v1/trade/orders?'.http_build_query($query);
        $header = $this->createGetHeaderSign();
        return $this->curlGet($this->api,$header);
    }

    public function getAccount()
    {
        $this->api = $this->api.'/v1/user/accounts';
        $header = $this->createGetHeaderSign([]);
        return $this->curlGet($this->api,$header);
    }

    public function createGetHeaderSign()
    {
        $parseUrl = $this->getParseUrl($this->api);
        $query = isset($parseUrl['query']) ? $parseUrl['query'] : '';
        $sign_data = [
            'GET',
            $parseUrl['host'],
            $parseUrl['path'],
            $query
        ];
        $header = $this->getHeader();
        $sign_str = implode("\n",array_merge($sign_data,$header)) ."\n";
        array_push($header,'API-SIGNATURE: '.hash_hmac('sha256',$sign_str, $this->secret_key));
        return $header;
    }

    public function createPostHeaderSign($params = [],$is_json = false)
    {
        $parseUrl = $this->getParseUrl($this->api);
        $query = $is_json == false ? http_build_query($params) : '';
        $sign_data = [
            'POST',
            $parseUrl['host'],
            $parseUrl['path'],
            $query,
        ];
        $header = $this->getHeader();
        $sign_str = implode("\n",array_merge($sign_data,$header)) ."\n";
        if($is_json == true){
            $sign_str .=  $params;
            array_push($header,'Content-Type: application/json');
        }
        array_push($header,'API-SIGNATURE: '.hash_hmac('sha256',$sign_str, $this->secret_key));
        return $header;
    }

    public function getMicrotime()
    {
        return (int)(microtime(true)*1000);
    }

    public function getParseUrl($url)
    {
        return parse_url($url);
    }

    public function getHeader()
    {
        return  [
            'API-KEY: '.$this->access_key,
            'API-SIGNATURE-METHOD: HmacSHA256',
            'API-SIGNATURE-VERSION: 1',
            'API-TIMESTAMP: '.$this->getMicrotime(),
        ];
    }


    public function curlGet($url,$headers = '')
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        if(!empty($headers)){
            curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $sContent = curl_exec($ch);
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        curl_close($ch);
        return $sContent ? substr($sContent, $header_size) : false;
    }


    public function curlPost($url, $data, $headers=[], $timeout=60)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        if(!empty($headers)){
            curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $sContent = curl_exec($ch);
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        curl_close($ch);
        return $sContent ? substr($sContent, $header_size) : false;
    }
}

/**
 *  create order demo
 *
 *
    $Api = new Api('xxxxxx','xxxx');
    $order = [
    'amount'   => '0.001',
    'type'     => 'BUY_LIMIT',
    'symbol'   => 'BTC_USDT',
    'price'    => '3984.00'
    ];
    var_dump($Api->createOrder($order));
 *
 */


/**
 *  get account demo
 *
    $Api = new Api('xxxxx','xxx');
    var_dump($Api->getAccount());
 *
 */


/**
 *  get orders demo
 *
   $Api = new Api('xxxxxx','xxxx');
   var_dump($Api->getOrders(0,100,'ETH_USDT'));
 *
 */


/**
 *  get order by id demo
 *
   $Api = new Api('xxxxxx','xxxxx');
   var_dump($Api->getOrderById('1'));
 *
 */

/**
 *  cancel order
 *
    $Api = new Api('xxxxxx','xxxxx');
    var_dump($Api->cancelOrder('1'));
 *
 */

/**
 *  get my transaction records
 *
    $Api = new Api('xxxxxx','xxxxx');
    var_dump($Api->getMatchresults('btcusdt'));
 *
 */