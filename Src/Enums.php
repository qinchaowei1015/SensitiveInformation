<?php

namespace App\Extend\SensitiveInformation\Src;

class Enums
{
    /**
     * 网易云盾 音频检测类型，默认0-url，1-语音内容base64
     */
    const DATA_CHECK_TYPE_URL  = 0;
    const DATA_CHECK_TYPE_BASE = 1;

    /**
     * 网易云盾 图片检测类型，默认1-url，2-base64
     */
    const IMG_DATA_CHECK_TYPE_URL  = 1;
    const IMG_DATA_CHECK_TYPE_BASE = 2;

    /**
     * 数美 图片检测类型，默认URL-url，RAW-base64
     */
    const AUDIO_DATA_CHECK_TYPE_URL  = 'URL';
    const AUDIO_DATA_CHECK_TYPE_BASE = 'RAW';

    /**
     * 同步异步 1 同步 2 异步
     */
    const SYNC  = 1;
    const ASYNC = 2;

}
