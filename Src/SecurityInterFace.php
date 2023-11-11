<?php

namespace App\Extend\SensitiveInformation\Src;

interface SecurityInterFace
{
    public function checkSecurity(BaseData $data);

    public function setTextData(string $text, $row): BaseData;

    public function setImageData(array $data, $row): BaseData;

    public function setVideoData(string $video, $row): BaseData;

    public function setAudioData(string $audio, $row): BaseData;
}
