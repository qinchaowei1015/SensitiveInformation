<?php

namespace App\Extend\SensitiveInformation\Src;

interface BaseDataInterFace
{
    /**
     * @param $str
     * @return void
     * 检测类型，默认0-url，1-语音内容base64
     */
    public function setDataCheckType($str): void;

    /**
     * @param $str
     * @return void
     * 1 同步 2 异步
     */
    public function setSynchronousAsynchronous($str): void;

}
