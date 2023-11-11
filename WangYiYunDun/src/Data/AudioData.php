<?php

namespace App\Extend\SensitiveInformation\WangYiYunDun\src\Data;

use App\Extend\SensitiveInformation\Src\BaseData;

class AudioData extends BaseData
{
    public array $eventId = [
        "AUDIO",
    ];
    public string $URL_SYNC  = 'http://as.dun.163.com/v2/audio/check';
    public string $URL_ASYNC = 'http://as.dun.163.com/v4/audio/submit';

    public function __construct()
    {
        parent::__construct();
        $this->setKeyData("title",$this->eventId[0]);
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
