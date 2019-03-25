<?php
namespace api\models;

class Sign
{
    /**
     * 生成签名
     *
     * @param array $data 要进行签名的数据
     * @param string $secret
     * @param string $token
     * @return string 签名
     */
    public function create($data, $secret, $token = '')
    {
        // 签名步骤一：按字典序排序参数
        $string = $this->formatBizQueryParaMap($data);

        // 签名步骤二：在string后加入KEY
        $string .= $secret;

        // 签名步骤三：MD5加密
        $sign = md5($string);

        // 签名步骤四：所有字符转为大写
        $sign = strtoupper($sign);

        return $sign;
    }

    /**
     * 格式化参数
     *
     * @param array $paraMap 要格式化的参数
     * @return string
     */
    protected function formatBizQueryParaMap($paraMap)
    {
        $buff = "";
        ksort($paraMap);

        foreach ($paraMap as $k => $v) {
            $v = $this->trimString($v);
            if ($k != 'sign' && $v !== null && ! is_array($v)) {
                $buff .= $k . "=" . $v . "&";
            }
        }

        return trim($buff, "&");
    }

    protected function trimString($value)
    {
        $ret = null;
        if ($value !== null) {
            $ret = trim($value);
            if ( !is_string($value) || strlen($ret) == 0) {
                $ret = null;
            }
        }
        return $ret;
    }
}