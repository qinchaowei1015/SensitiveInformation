<?php

namespace App\Extend\SensitiveInformation\WangYiYunDun\src\Data;

use App\Extend\SensitiveInformation\Src\BaseData;

class VideoData extends BaseData
{
    public array $eventId = [
        "VIDEODEFAULT",
    ];

    public string $URL_ASYNC = 'http://as.dun.163.com/v4/video/submit';
    protected string $businessId = '';
    public function __construct()
    {
        parent::__construct();
        $this->setKeyData("dataType", $this->eventId[0]);
        $this->setKeyData("businessId", $this->businessId);
        $this->setUrl($this->URL_ASYNC);
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
