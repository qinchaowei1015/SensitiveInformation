<?php

namespace App\Extend\SensitiveInformation\Src;

use Exception;
use Closure;

class BaseDataManager
{

    /**
     * @throws Exception
     */
    public function getDataObj($type, mixed $data, SecurityInterFace $driverObj, array $row = []): BaseData
    {
        return match ($type) {
            "image" => $driverObj->setImageData($data, $row),
            "text"  => $driverObj->setTextData($data, $row),
            "video" => $driverObj->setVideoData($data, $row),
            "audio" => $driverObj->setAudioData($data, $row),
            default => throw new Exception("未定义检测驱动类型"),
        };
    }

}
