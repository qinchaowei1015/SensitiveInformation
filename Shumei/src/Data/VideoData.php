<?php

namespace App\Extend\SensitiveInformation\Shumei\src\Data;

use App\Extend\SensitiveInformation\Src\BaseData;

class VideoData extends BaseData
{
    public array $eventId = [
        "VIDEODEFAULT",
    ];
    public string $URL_ASYNC  = 'http://api-video-bj.fengkongcloud.com/video/v4';

    public function __construct()
    {
        parent::__construct();
        $this->setKeyData("eventId",$this->eventId[0]);
        $this->setUrl($this->URL_ASYNC);
        $this->setKeyData("imgType","POLITY_EROTIC_VIOLENT_IMGTEXTRISK");
        $this->setKeyData("audioType","POLITICAL_PORN_MOAN");
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
