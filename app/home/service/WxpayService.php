<?php


namespace app\home\service;


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


    /**
     * 通过跳转获取用户的openid，跳转流程如下：
     * 1、设置自己需要调回的url及其其他参数，跳转到微信服务器https://open.weixin.qq.com/connect/oauth2/authorize
     * 2、微信服务处理完成之后会跳转回用户redirect_uri地址，此时会带上一些参数，如：code
     *  return 返回用户的openID
     */
    public function GetOpenid()
    {
        //通过code获得openid
        if (Session::has('openid')) {
////            halt(Cache::get('openid'));
            return Session::get('openid');
        } else {
            if (!isset($_GET['code'])) {
                //触发微信返回code码
                $baseUrl = urlencode($_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
                $url = $this->__CreateOauthUrlForCode($baseUrl);
                Header("Location: $url");
                exit();
            } else {
                //获取code码，以获取openid
                $code = $_GET['code'];
                $openid = $this->getOpenidFromMp($code);
                Session::set('openid', $openid);
//                p($openid);
                return $openid;
            }
        }
    }

    /**
     * 通过code从工作平台获取openid机器access_token
     * @param string $code 微信跳转回来带上的code
     * return openid
     */
    public function GetOpenidFromMp($code)
    {
        $url = $this->__CreateOauthUrlForOpenid($code);
//        $res = self::curlGet($url);
        $res = file_get_contents($url);
        //取出openid
        $data = json_decode($res, true);
//        $this->data = $data;
        $openid = $data['openid'];
        return $openid;
    }

    /**
     * 构造获取open和access_toke的url地址
     * @param string $code
     */
    private function __CreateOauthUrlForOpenid($code)
    {
        $openid_url = self::OPENURL . "appid=" . self::APPID . "&secret=" . self::SECRET . "&code=" . $code . "&grant_type=authorization_code";

        return $openid_url;
    }

    /**
     * 构造获取code的url连接
     * @param string $redirectUrl 微信服务器回跳的url，需要url编码
     * @return
     */
    private function __CreateOauthUrlForCode($redirectUrl)
    {

        $url = self::CODEURL . 'appid=' . self::APPID . '&redirect_uri=' . $redirectUrl . '&response_type=code&scope=snsapi_base&state=STATE#wechat_redirect';

        return $url;
    }


    /*
     * 统一下单
     * @params appid 公众号appid
     * @params mch_id 商户号
     * @params nonce_str 随机机字符串
     * @params body 内容的描述
     * @params out_trade_no 每个商户号都是唯一的
     * @params total_fee 订单金额(以分为单位计算)
     * @params notify_url 支付成功后回调的地址
     * @params trade_type 支付的类型
     * @params product_id  商品的id(根据自己需求自定义)
     * @params openid 用户的唯一标识(必填项)
     * @params attach 自定义的附加参数
     */
    public function unifiedOrder($body, $total_fee,$attach)
    {
        //1构建原始数据
        //2加入签名
        //3将数据转换成XML格式
        //4将XML数据发送给接口地址

        $params = [
            'appid' => self::APPID,
            'mch_id' => self::MCHID,
            'nonce_str' => md5(time()),
            'body' => $body,
            'attach' => $attach,
            'out_trade_no' => $this->makeOrderNo(),
            'total_fee' => $total_fee * 100,
            'notify_url' => 'https://m.gsdblog.cn/home/receiveNotify',
            'trade_type' => 'JSAPI',
            'openid' => $this->GetOpenid()
        ];

        $params = $this->setSign($params);
//        halt($params);
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

//    public function GetPrepayId()
//    {
//        $data = $this->unifiedOrder();
//
//        halt($data, Session::get('openid'));
//
//        return $data['prepay_id'];
//    }

    /*
     * 获取公众号支付所需要的的参数返回给前端进行支付
     * @params appId 公众号id
     * @params timeStamp 时间戳(注:官方格式为字符串类型，不转换无法l拉起支付)
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
        //禁止引用外部xml实体
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

//    /*
//     * 支付回调通知
//     */
//    public function notify()
//    {
//        $xml = $this->GetPostData();
//        p($xml);
//        $arr = $this->XmlToArr($xml);
//        p($arr);
//        if ($this->checkSign($arr)) {
//            if ($arr['return_code'] == "SUCCESS" && $arr['result_code'] == "SUCCESS") {
//                if ($arr['total_fee'] == 1) {    //根据订单号查询出订单金额进行验证
//
////                    echo "交易成功";          //修改订单状态
//                    Log::record('交易成功');
//                    //发送应答给商户平台
//                    $result = [
//                        'return_code' => 'SUCCESS',
//                        "return_msg" => "OK"
//                    ];
//
//                    return $this->ArrToXml($result);
//
//                } else {
//                    Log::record('金额有误');
////                    return "金额有误";
//                }
//            } else {
//                Log::record('业务结果不正确');
////                return "业务结果不正确";
//            }
//        } else {
//            Log::record('业务结果不正确');
////            return "签名验证失败";
//        }
//    }


}