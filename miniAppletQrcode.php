<?php
/**
 * Curl 处理请求，忽略 http https
 * @param $url
 * @return mixed
 * @author wanggaobo
 */
public function http_curl($url){
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);//这个是重点。
    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
}

/*
 * 获取TokenAccess
 */
public function getTokenAccess(){
    $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$this->appid}&secret={$this->secret}";
    $res = $this->http_curl($url);
    $result = json_decode($res, true);
    $access_token = $result["access_token"];
    return $access_token;
}


/**
 * FN:生成永久二维码
 * 接口A 接口C $params = ['path' => 'XXX']
 * 接口B $params = ['scene'=>'XXX',  'page' => 'XXX']
 * A、B 参数是 path
 * C 参数是 page
 */
public function createMiniPermanentQrCode(){
    $token = $this->getTokenAccess();
    $input = $this->input->get();
    //生成小程序二维码 接口A：适用于需要的码数量较少的业务场景
    $api = "https://api.weixin.qq.com/wxa/getwxacode?access_token={$token}";
    //生成小程序二维码 接口B：适用于需要的码数量极多的业务场景
    //$api = "https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token={$token}";
    /**
    $params = [
        'scene' => urlencode($input['scene']),
        'page' => 'pages/gongdilist/gongdilist',
    ];
    **/
    //生成小程序二维码 接口C：适用于需要的码数量较少的业务场景
    //$api = "https://api.weixin.qq.com/cgi-bin/wxaapp/createwxaqrcode?access_token={$token}";
    $params = [
        'path' => 'pages/XX/XX',
    ];
    $response_obj = $this->curl->post($api, json_encode($params));
    $data='image/png;base64,'.base64_encode($response_obj);
    echo '<img src="data:'.$data.'">';
}