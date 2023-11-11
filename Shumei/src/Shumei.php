<?php

namespace App\Extend\SensitiveInformation\Shumei\src;

use App\Extend\SensitiveInformation\Shumei\src\Data\AudioData;
use App\Extend\SensitiveInformation\Shumei\src\Data\ImageData;
use App\Extend\SensitiveInformation\Shumei\src\Data\TextData;
use App\Extend\SensitiveInformation\Shumei\src\Data\VideoData;
use App\Extend\SensitiveInformation\Src\BaseData;
use App\Extend\SensitiveInformation\Src\Enums;
use App\Extend\SensitiveInformation\Src\Response;
use App\Extend\SensitiveInformation\Src\Result;
use App\Extend\SensitiveInformation\Src\SecurityInterFace;
use App\Traits\CommonTrait;
use GuzzleHttp\Client;

class Shumei implements SecurityInterFace
{
    use CommonTrait;
    public function checkSecurity(BaseData $data)
    {
        $data->setKeyData("accessKey", "uQUwYBEPLF61ejXNAtAY");
        $data->setKeyData("appId", "1779883591");
        $http = new Client();
        $re   = $http->post($data->getUrl(), [
            "body" => json_encode($data->getData(), true)
        ]);

        $result = new Result(new Response($re));
        $response = $result->getResponse()->getData();

        $textData = new TextData();
        $imageData = new ImageData();
        $audioData = new AudioData();
        if ($textData->URL_SYNC == $data->getUrl()){
            if ($response['code'] == 1100 && $response['riskLevel'] == 'PASS'){
                return ['code' => 0, 'message' => '成功！'];
            }else if ($response['code'] == 1100 && $response['riskLevel'] == 'REVIEW'){
                return ['code' => 1, 'message' => '输入内容存在嫌疑，涉嫌'. $response['riskDescription'] .'请重新输入!'];
            }else{
                return ['code' => 2, 'message' => '输入内容违规'. $response['riskDescription'] .'，请重新输入!'];
            }
        }else if ($imageData->URL_SYNC == $data->getUrl()){
            if ($response['code'] == 1100){
                $imgCount = count($response['imgs']);
                for ($j=0;$j<$imgCount;$j++){
                    if ($response['imgs'][$j]['code'] == 1100){
                        if ($response['imgs'][$j]['riskLevel'] == 'PASS'){
                            continue;
                        }else if ($response['imgs'][$j]['riskLevel'] == 'REVIEW'){
                            return ['code' => 1, 'message' => '输入内容存在嫌疑，涉嫌'. $response['imgs'][$j]['riskDescription'] .'请重新输入!'];
                        }else{
                            return ['code' => 2, 'message' => '输入内容未通过绿色公约检测，请重新输入!'];
                        }
                    }else{
                        return ['code' => 2, 'message' => $response['imgs'][$j]['message']];
                    }
                }
                return ['code' => 0, 'message' => '成功!'];
            }else{
                return ['code' => 2, 'message' => '输入内容未通过绿色公约检测，请重新输入!'];
            }
        }else if($audioData->URL_SYNC == $data->getUrl()){
            if ($response['code'] == 1100){
                if ($response['detail']['audioDetail'] == 'PASS'){
                    return ['code' => 0, 'message' => '成功!'];
                }else if ($response['detail']['audioDetail'] == 'REVIEW'){
                    $audioCount = count($response['detail']['audioDetail']);
                    $str = '';
                    for ($i=0;$i<$audioCount;$i++){
                        $str .= !isset($response['detail']['audioDetail'][$i]) && empty($response['detail']['audioDetail'][$i]['riskDescription'])
                            ? '' : $response['detail']['audioDetail'][$i]['riskDescription'] . '，' ;
                    }
                    return ['code' => 1, 'message' => '输入内容存在嫌疑，涉嫌'. $str .'请重新输入!'];
                }else{
                    return ['code' => 2, 'message' => '输入内容未通过绿色公约检测，请重新输入!'];
                }

            }else{
                return ['code' => 2, 'message' => '输入内容未通过绿色公约检测，请重新输入!'];
            }
        }else{
            if ($response['code'] == 1100){
                return ['code' => 0, 'message' => '成功!'];
            }else{
                return ['code' => 2, 'message' => '失败!'];
            }
        }

    }

    public function setTextData(string $text, $row): BaseData
    {
        // TODO: Implement setTextData() method.
        $data = new TextData();
        $data->setKeyData('data',[
            'text' => $text,
            'tokenId' => $this->generateUniqueString(64),
        ]);

        return $data;
    }

    public function setImageData(array $data, $row): BaseData
    {
        $imageData = new ImageData();

        if (isset($row['syncAsync']) && $row['syncAsync'] == Enums::ASYNC){
            $imageData->setUrl($imageData->URL_ASYNC);
        }

        $row = [];
        foreach ($data as $key => $val){
            $row[] = [
                'btId' => "".$key+1,
                'img' => $val,
            ];
        }
        $imageData->setKeyData('data',[
            "tokenId" => $this->generateUniqueString(64),
            "imgs"    => $row
        ]);
        return $imageData;
    }

    public function setVideoData(string $video, $row): BaseData
    {
        // TODO: Implement setVideoDta() method.
        $data = new VideoData();
        $data->setKeyData('data',[
            'url'     => $video,
            'tokenId' => $this->generateUniqueString(64),
            'btId'    => $this->generateUniqueString(8),
        ]);

        return $data;
    }

    public function setAudioData(string $audio, $row): BaseData
    {
        // TODO: Implement setAudioData() method.
        $audioData = new AudioData();

        if (isset($row['syncAsync']) && $row['syncAsync'] == Enums::ASYNC){
            $audioData->setUrl($audioData->URL_ASYNC);
        }else{
            $audioData->setUrl($audioData->URL_SYNC);
        }

        $audioData->setKeyData('btId',$this->generateUniqueString(16));
        $audioData->setKeyData('content', $audio);

        if (isset($row['dataCheckType'])){
            if ($row['dataCheckType'] == Enums::DATA_CHECK_TYPE_URL){
                $audioData->setKeyData('contentType', Enums::AUDIO_DATA_CHECK_TYPE_URL);
            }else{
                $audioData->setKeyData('contentType', Enums::AUDIO_DATA_CHECK_TYPE_BASE);
            }
        }else{
            $audioData->setKeyData('contentType', Enums::AUDIO_DATA_CHECK_TYPE_URL);
        }

        $audioData->setKeyData('data',[
            'tokenId' => $this->generateUniqueString(64),
        ]);

        return $audioData;
    }
}
