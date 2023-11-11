<?php

namespace App\Extend\SensitiveInformation\WangYiYunDun\src\utils;

class VisaVerification
{
    const INTERNAL_STRING_CHARSET = 'auto';
     /**
     * 将输入数据的编码统一转换成utf8
     * @params 输入的参数
     */
    public static function toUtf8($params): array
    {
        $utf8s = array();
        foreach ($params as $key => $value) {
            $utf8s[$key] = is_string($value) ? mb_convert_encoding($value, "utf8", VisaVerification::INTERNAL_STRING_CHARSET) : $value;
        }
        return $utf8s;
    }

    /**
     * 生成签名信息
     * $secretKey 产品私钥
     * $params 接口请求参数，不包括signature参数
     */
    public static function genSignature($secretKey,$params): string
    {
        ksort($params);
        $buff = "";
        foreach($params as $key=>$value){
            $buff .=$key;
            $buff .=$value;
        }
        $buff .= $secretKey;
        return md5(mb_convert_encoding($buff, "utf8", "auto"));
    }
}
