<?php

namespace App\Extend\SensitiveInformation\WangYiYunDun\src\Data;

use App\Extend\SensitiveInformation\Src\BaseData;

class TextData extends BaseData
{

    public array $eventId = [
        "message", //私聊
        "article", //聊天室
        "groupMessage" //群聊
    ];
    public string $URL_SYNC  = 'http://as.dun.163.com/v5/text/check';
    public string $URL_ASYNC = 'http://as.dun.163.com/v5/text/async-check';
    protected string $businessId = 'a62b7f61beadf26b7ad0f73b3d332e6e';
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
