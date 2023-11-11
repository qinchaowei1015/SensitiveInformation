<?php

namespace App\Extend\SensitiveInformation\Shumei\src\Data;

use App\Extend\SensitiveInformation\Src\BaseData;

class AudioData extends BaseData
{
    public array $eventId = [
        "AUDIO",
    ];
    public string $URL_SYNC  = 'http://api-audio-sh.fengkongcloud.com/audiomessage/v4';
    public string $URL_ASYNC = 'http://api-audio-sh.fengkongcloud.com/audio/v4';

    public function __construct()
    {
        parent::__construct();
        $this->setKeyData("eventId",$this->eventId[0]);
        $this->setKeyData("type","POLITY_EROTIC_MOAN_ADVERT");
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
