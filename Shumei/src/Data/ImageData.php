<?php

namespace App\Extend\SensitiveInformation\Shumei\src\Data;

use App\Extend\SensitiveInformation\Src\BaseData;

class ImageData extends BaseData
{
    public array $eventId = [
        "message", //私聊
        "article", //帖子
        "groupMessage" //群聊
    ];
    public string $URL_SYNC  = 'http://api-img-bj.fengkongcloud.com/images/v4';
    public string $URL_ASYNC = 'http://api-img-sh.fengkongcloud.com/v4/saas/async/imgs';
    public function __construct()
    {
        parent::__construct();
        $this->setUrl($this->URL_SYNC);
        $this->setKeyData("eventId",$this->eventId[0]);
        $this->setKeyData("type","IMGTEXTRISK");

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
