<?php


namespace app\home\service;


use think\facade\Cache;
use think\facade\Session;

class WxpayService
{

    const KEY = "";
    const CODEURL = "https://open.weixin.qq.com/connect/oauth2/authorize?";
    const APPID = "";
    const MCHID = "";
    const OPENURL = "https://api.weixin.qq.com/sns/oauth2/access_token?";  //获取用户openid
    const SECRET = "";
    const UNURL = "https://api.mch.weixin.qq.com/pay/unifiedorder";     //统一下单地址

    /*
     * 生成签名并且返回签名
     */
    public function getSign($arr)
    {
        //去除参数中的空值
        array_filter($arr);
        if (isset($arr['sign'])) {
            unset($arr['sign']);
        }
        //按照键值字典排序
        ksort($arr);
        $str = self::ArrToUrl($arr) . '&key=' . self::KEY;
        return strtoupper(md5($str));
    }

    /*
     * 获取带签名的数据
     */

    public function setSign($arr)
    {
        $arr['sign'] = $this->getSign($arr);

        return $arr;
    }

    /*
     * 验证签名
     */
    public function checkSign($arr)
    {
        $sign = $this->getSign($arr);
//        halt($arr['sign']);
        if ($sign == $arr['sign']) {
            return true;
        } else {
            return false;
        }
    }

    /*
     * 解码url字符串
     */

    public function ArrToUrl($arr)
    {
        return urldecode(http_build_query($arr));
    }

    /*
     * 获取用户的openid
     */
    public function getOpenId()
    {

        if (Session::has('openid')) {
//            halt(Cache::get('openid'));
            return Session::get('openid');
        } else {
            //1.用户访问一个地址获取code
            //2.根据获取的code获取openID
            if (!isset($_GET['code'])) {
                //构建跳转地址
//                $redurl = $_SERVER['REQUEST_SCHEME'] . '//' . $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
//                $redurl = 'http://m.gsdblog.cn';
                $str = time();
                $redurl = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
//                halt($redurl);
                $url = self::CODEURL . 'appid=' . self::APPID . '&redirect_uri=' . urlencode($redurl) . '&response_type=code&scope=snsapi_base&state=' . $str . '#wechat_redirect';
//                halt($url);
                header("location:$url");
//                halt($url);
            } else {
//                halt($_GET['code']);
                $openidurl = self::OPENURL . "appid=" . self::APPID . "&secret=" . self::SECRET . "&code=" . $_GET['code'] . "&grant_type=authorization_code";
                $data = file_get_contents($openidurl);
                $arrs = json_decode($data, true);
                Session::set('openid',$arrs['openid']);
                $openid = Session::get('openid');
                return $openid;

                //调取接口获取openID
            }
        }
    }

    /*
     * 统一下单
     * @params appid 公众号appid
     * @params mch_id 商户号
     * @params nonce_str 随机机字符串
     * @params body 内容的描述
     * @params out_trade_no 每个商户号都是唯一的
     * @params total_fee 订单金额(已分为单位计算)
     * @params notify_url 支付成功后回调的地址
     * @params trade_type 支付的类型
     * @params product_id  商品的id(根据自己需求自定义)
     * @params openid 用户的唯一标识(必填项)
     */
    public function unifiedOrder()
    {
        //1构建原始数据
        //2加入签名
        //3将数据转换成XML格式
        //4将XML数据发送给接口地址

        $params = [
            'appid' => self::APPID,
            'mch_id' => self::MCHID,
            'nonce_str' => md5(time()),
            'body' => '公众号支付测试',
            'out_trade_no' => $this->makeOrderNo(),
            'total_fee' => 1,
            'notify_url' => 'http://m.gsdblog.cn/',
            'trade_type' => 'JSAPI',
            'openid' => $this->getOpenId()
        ];

        $params = $this->setSign($params);
        $xmldata = $this->ArrToXml($params);
        $resdata = $this->postXml(self::UNURL, $xmldata);
//        halt($resdata);
        $result = $this->XmlToArr($resdata);
//        halt($result);

        return $result;
    }

    /*
     * 生成订单编号
     */

    public static function makeOrderNo()
    {
        $yCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
        $orderSn = $yCode[intVal(date('Y')) - 2021] . strtoupper(dechex(date('m')))
            . date('d') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('%02d', rand(0, 99));

        return $orderSn;
    }

    /*
     * 获取prepay_id   预付单号标识
     */

    public function GetPrepayId()
    {
        $data = $this->unifiedOrder();

        halt($data,Session::get('openid'));

        return $data['prepay_id'];
    }

    /*
     * 获取公众号支付所需要的的参数返回给前端进行支付
     * @params appId 公众号id
     * @params timeStamp 时间戳(注:官方格式为字符串类型，不转换无法进行支付)
     * @params nonceStr 随机机字符串
     * @params package 预付单号的标识(没有该值将无法进行支付)
     * @params signType 签名类型
     * 注：以上参数格式必须严格按照官方提供的格式进行填写，否则将无法唤起支付
     */

    public function GetJsParams($prepay_id)
    {
        $params = [
            'appId' => self::APPID,
            'timeStamp' => strval(time()),
            'nonceStr' => md5(time()),
            'package' => 'prepay_id=' . $prepay_id,
            'signType' => 'MD5'
        ];

        $params['paySign'] = $this->getSign($params);

        return json_encode($params);
    }


    //数组转xml
    function ArrToXml($arr)
    {
        if (!is_array($arr) || count($arr) == 0) return '';

        $xml = "<xml>";
        foreach ($arr as $key => $val) {
            if (is_numeric($val)) {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            } else {
                $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
            }
        }
        $xml .= "</xml>";
        return $xml;
    }

    //Xml转数组
    function XmlToArr($xml)
    {
        if ($xml == '') return '';
        libxml_disable_entity_loader(true);
        $arr = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $arr;
    }

    //发送postxml
    public function postXml($url, $postfields)
    {
        $ch = curl_init();
//        $headers = [
//            //"Content-Type:text/html;charset=UTF-8", "Connection: Keep-Alive"
//        ];
//        $params[CURLOPT_HTTPHEADER] = $headers; //自定义header
        $params[CURLOPT_URL] = $url;    //请求url地址
        $params[CURLOPT_HEADER] = false; //是否返回响应头信息
        $params[CURLOPT_RETURNTRANSFER] = true; //是否将结果返回
        $params[CURLOPT_FOLLOWLOCATION] = true; //是否重定向
        $params[CURLOPT_POST] = true;
        $params[CURLOPT_POSTFIELDS] = $postfields;

        //不验证https证书
        $params[CURLOPT_SSL_VERIFYPEER] = false;
        $params[CURLOPT_SSL_VERIFYHOST] = false;

        curl_setopt_array($ch, $params); //传入curl参数
        $content = curl_exec($ch); //执行

        curl_close($ch); //关闭连接

        return $content;
    }


    /*
     * 获取POST发送提交的订单数据
     */
    public function GetPostData()
    {
        return file_get_contents('php://input');
    }

    /*
     * 支付回调通知
     */
    public function notify()
    {
        $xml = $this->GetPostData();
        $arr = $this->XmlToArr($xml);
        if ($this->checkSign($arr)) {
            if ($arr['return_code'] == "SUCCESS" && $arr['result_code'] == "SUCCESS") {
                if ($arr['total_fee'] == 1) {    //根据订单号查询出订单金额进行验证

                    echo "交易成功";          //修改订单状态

                    //发送应答给商户平台
                    $result = [
                        'return_code' => 'SUCCESS',
                        "return_msg" => "OK"
                    ];

                    return $this->ArrToXml($result);

                } else {
                    return "金额有误";
                }
            } else {
                return "业务结果不正确";
            }
        } else {

            return "签名验证失败";
        }
    }


}