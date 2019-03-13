<?php
namespace common\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\web\UploadedFile;

class Common
{
    /**
     *过滤公共方法
     * $type  1是纯文本过滤，2是HTML过滤
     */
    public static function filter($text, $type = 1)
    {
        if ($type == 1) {
            $text = trim($text);
            return Html::encode($text);
        } else if ($type == 2) {
            return HtmlPurifier::process($text);
        }
    }

    public static function filter_decode($text, $type = 1)
    {
        if ($type == 1) {
            return htmlspecialchars_decode($text);
        } else {
            return HtmlPurifier::process($text);
        }
    }

    /**
     * @param string $name 上传表单名称
     * @param string $type 上传文件类型 image video audio file
     * @return mixed
     */
    public static function upload($name = 'file', $type = 'image')
    {
        $model = new UploadFile();
        $model->file = UploadedFile::getInstanceByName($name);
        return $model->upload($type);
    }

    /**
     * 截取并处理字符串
     * $str 处理的字符串
     * $len 截取的长度
     * $add 后面是否加...
     */
    public static function subStr($str, $len = 0, $add = true)
    {
        if ($len < mb_strlen($str, 'utf8') && $len && $add) {
            $str = mb_substr($str, 0, $len, 'utf-8') . '...';
        } else {
            $str = mb_substr($str, 0, $len, 'utf-8');
        }
        return $str;
    }

    /**
     * 获取设备信息
     * @return integer
     * 1,安卓，2,ios,3,其他
     */
    public static function getSystem()
    {
        $agent = strtolower($_SERVER['HTTP_USER_AGENT']);
        $is_pc = (strpos($agent, 'windows nt')) ? true : false;
        $is_mac = (strpos($agent, 'mac os')) ? true : false;
        $is_iphone = (strpos($agent, 'iphone')) ? true : false;
        $is_android = (strpos($agent, 'android')) ? true : false;
        $is_ipad = (strpos($agent, 'ipad')) ? true : false;
        if ($is_android) {
            return 1;
        } else if ($is_iphone) {
            return 2;
        } elseif ($is_pc) {
            return 3;
        } else {
            return 4;
        }
    }

    /**
     * 发送短信阿里
     */
    public static function sendSms($tel, $param, $temp, $sign = '卡农社区')
    {
        require_once '/sms/TopSdk.php';
        //$code=rand(100000,999999);
        $appkey = self::getSysInfo(5);
        $secret = self::getSysInfo(6);
        $c = new \TopClient;
        $c->appkey = $appkey;
        $c->secretKey = $secret;
        $c->format = 'json';

        $req = new \AlibabaAliqinFcSmsNumSendRequest;
        //$req->setExtend($code);
        $req->setSmsType('normal');
        $req->setSmsFreeSignName($sign); //发送的签名
        $req->setSmsParam($param);//根据模板进行填写
        $req->setRecNum($tel);//接收着的手机号码
        $req->setSmsTemplateCode($temp);//短信模板
        $resp = $c->execute($req);
        return $resp->result->err_code;
    }

    /**
     * 二维数组按照指定的键值进行排序
     * @param $arr
     * @param $keys
     * @param string $type
     * @return array
     */
    public static function arraySort($arr, $keys, $type = 'asc')
    {
        $keysvalue = $new_array = array();
        foreach ($arr as $k => $v) {
            $keysvalue[$k] = $v[$keys];
        }
        if (strtolower($type) == 'asc') {
            asort($keysvalue);
        } else {
            arsort($keysvalue);
        }
        reset($keysvalue);
        foreach ($keysvalue as $k => $v) {
            $new_array[$k] = $arr[$k];
        }
        return $new_array;
    }

    /**
     * 5d7e0157327f4b90be8a785aa1dbc12e
     * 生成不带横杠的UUID
     * @return string
     */
    public static function getUid()
    {
        return sprintf('%04x%04x%04x%04x%04x%04x%04x%04x',
            // 32 bits for "time_low"
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            // 16 bits for "time_mid"
            mt_rand(0, 0xffff),
            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand(0, 0x0fff) | 0x4000,
            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand(0, 0x3fff) | 0x8000,
            // 48 bits for "node"
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }

    /**
     * 获取客户端IP
     * @return string 返回ip地址,如127.0.0.1
     */
    public static function getClientIp()
    {
        $onlineip = 'Unknown';
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ips = explode(",", $_SERVER['HTTP_X_FORWARDED_FOR']);
            $real_ip = $ips['0'];
            if ($_SERVER['HTTP_X_FORWARDED_FOR'] && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $real_ip)) {
                $onlineip = $real_ip;
            } elseif ($_SERVER['HTTP_CLIENT_IP'] && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_CLIENT_IP'])) {
                $onlineip = $_SERVER['HTTP_CLIENT_IP'];
            }
        }
        if ($onlineip == 'Unknown' && isset($_SERVER['HTTP_CDN_SRC_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_CDN_SRC_IP'])) {
            $onlineip = $_SERVER['HTTP_CDN_SRC_IP'];
            $c_agentip = 0;
        }
        if ($onlineip == 'Unknown' && isset($_SERVER['HTTP_NS_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER ['HTTP_NS_IP'])) {
            $onlineip = $_SERVER ['HTTP_NS_IP'];
            $c_agentip = 0;
        }
        if ($onlineip == 'Unknown' && isset($_SERVER['REMOTE_ADDR']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['REMOTE_ADDR'])) {
            $onlineip = $_SERVER['REMOTE_ADDR'];
            $c_agentip = 0;
        }
        return $onlineip;
    }

    /**
     * 读取文本末尾n行
     * @param $fileName
     * @param $n
     * @param int $base
     * @return array
     */
    public static function tail($fileName, $n, $base = 5)
    {
        $fp = fopen($fileName, "r+");
        $pos = $n + 1;
        $lines = array();
        while (count($lines) <= $n) {
            try {
                fseek($fp, -$pos, SEEK_END);
            } catch (Exception $e) {
                fseek(0);
                break;
            }
            $pos *= $base;
            while (!feof($fp)) {
                array_unshift($lines, fgets($fp));
            }
        }
        //echo implode ( "", array_reverse ( $lines ) );
        return array_reverse(array_slice($lines, 0, $n));
    }

    /**
     * 对象排序
     * @param $orderby
     * @param $key
     * @return string
     */
    public static function sortClass($orderby, $key)
    {
        $data = explode(' ', $orderby);
        $sortClass = 'class="sorting"';
        if (count($data) > 0) {
            if (empty($data[0]) == false && $data[0] == $key) {
                if (empty($data[1]) == false && $data[1] == 'desc') {
                    $sortClass = 'class="sorting_desc"';

                } else {
                    $sortClass = 'class="sorting_asc"';
                }
            }
        }
        return $sortClass;
    }

    /**
     * 提交表单
     * @param $url
     * @param $model
     */
    public static function build_form($url, $model)
    {
        $sHtml = "<!DOCTYPE html><html><head><title>Waiting...</title>";
        $sHtml .= "<meta http-equiv='content-type' content='text/html;charset=utf-8'></head>
	    <body><form id='submit' name='submit' action='" . $url . "' method='POST'>";
        foreach ($model as $key => $value) {
            $sHtml .= "<input type='hidden' name='" . $key . "' value='" . $value . "' style='width:90%;'/>";
        }
        $sHtml .= "</form>正在提交信息...";
        $sHtml .= "<script>document.forms['submit'].submit();</script></body></html>";
        exit($sHtml);
    }

    /**
     * 腾讯云短信发送
     * @param $phone ||手机号
     * @param $temp ||模板ID
     * @param $random ||随机数
     * @return int
     */
    public static function setCode($phone, $temp, $random)
    {
        $sj = 3;
        $curTime = time();
        $wholeUrl = "https://yun.tim.qq.com/v5/tlssmssvr/sendsms?sdkappid=1400057341&random=" . $random;
        // 按照协议组织 post 包体
        $data = new \stdClass();
        $tel = new \stdClass();
        $tel->nationcode = "" . "86";
        $tel->mobile = "" . $phone;
        $data->tel = $tel;
        $data->sig = hash("sha256",
            "appkey=b6dd54e10f14f0507720b3a9588b41aa&random=" . $random . "&time="
            . $curTime . "&mobile=" . $phone, FALSE);
        $data->tpl_id = $temp;
        $data->params = array($random, $sj);
        $data->time = $curTime;
        $data->sign = '云肆网络';
        $data->extend = '';
        $data->ext = '';
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $wholeUrl);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        $ret = curl_exec($curl);
        $res = json_decode($ret, true);
        if ($res['errmsg'] == 'OK') {//发送成功
            return 200;
        }
    }

    /**
     * @param $model
     * @return mixed
     */
    public static function getLabels($model){
        foreach($model as $key => $list){
            $model[$key]->labels = explode(',',$list->labels);
        }
        return $model;
    }

}