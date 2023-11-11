<?php

namespace App\Extend\SensitiveInformation\WangYiYunDun\src\Data;

use App\Extend\SensitiveInformation\Src\BaseData;

class ImageData extends BaseData
{
    public array $eventId = [
        "message", //私聊
        "article", //帖子
        "groupMessage" //群聊
    ];
    public string $URL_SYNC  = 'http://as.dun.163.com/v5/image/check';
    public string $URL_ASYNC = 'http://as.dun.163.com/v5/image/asyncCheck';
    protected string $businessId = '1823a4dffb5d6eb14efaa04b5b00cf89';
    public function __construct()
    {
        parent::__construct();
        $this->setKeyData("dataType", $this->eventId[0]);
        $this->setKeyData("businessId", $this->businessId);
        $this->setUrl($this->URL_SYNC);
    }

    public function setDataCheckType($str): void
    {
        // TODO: Implement setTest() method.
    }

    public function setSynchronousAsynchronous($str): void
    {
        // TODO: Implement setSynchronousAsynchronous() method.
    }
}
