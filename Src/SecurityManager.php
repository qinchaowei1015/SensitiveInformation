<?php

namespace App\Extend\SensitiveInformation\Src;


use App\Extend\SensitiveInformation\Shumei\src\Shumei;
use App\Extend\SensitiveInformation\WangYiYunDun\src\WangYiYunDun;

class SecurityManager
{


    public function getSecurityByDriver($driver): SecurityInterFace
    {
        //todo:
        switch ($driver) {
            case 'shumei':
                return new Shumei();
            case 'wangyiyundun':
                return new WangYiYunDun();
            // 添加更多的驱动选项
            default:
                throw new \InvalidArgumentException("Invalid driver provided.");
        }
    }

}
