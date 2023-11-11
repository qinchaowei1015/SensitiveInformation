<?php

namespace App\Extend\SensitiveInformation\Shumei\src\Data;

use App\Extend\SensitiveInformation\Src\BaseData;

class TextData extends BaseData
{
    public string $URL_SYNC  = 'http://api-text-bj.fengkongcloud.com/text/v4';
    public array $eventId = [
        "message", //私聊
        "article", //聊天室
        "groupMessage" //群聊
    ];

    public function __construct()
    {
        parent::__construct();
        $this->setKeyData("type", "TEXTRISK");
        $this->setKeyData("eventId", $this->eventId[0]);
        $this->setUrl($this->URL_SYNC);
    }

    public function setDataCheckType($str): void
    {

    }

    public function setSynchronousAsynchronous($str): void
    {
        // TODO: Implement setSynchronousAsynchronous() method.
    }
}
