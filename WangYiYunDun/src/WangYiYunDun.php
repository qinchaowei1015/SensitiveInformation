<?php

namespace App\Extend\SensitiveInformation\WangYiYunDun\src;

use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\AddShortUrlResponseBody\data;
use App\Extend\SensitiveInformation\Src\Enums;
use App\Extend\SensitiveInformation\WangYiYunDun\src\Data\AudioData;
use App\Extend\SensitiveInformation\WangYiYunDun\src\Data\ImageData;
use App\Extend\SensitiveInformation\WangYiYunDun\src\Data\TextData;
use App\Extend\SensitiveInformation\WangYiYunDun\src\Data\VideoData;
use App\Extend\SensitiveInformation\Src\BaseData;
use App\Extend\SensitiveInformation\Src\Response;
use App\Extend\SensitiveInformation\Src\Result;
use App\Extend\SensitiveInformation\Src\SecurityInterFace;
use App\Extend\SensitiveInformation\WangYiYunDun\src\utils\VisaVerification;
use App\Traits\CommonTrait;
use GuzzleHttp\Client;
use function Symfony\Component\Translation\t;

class WangYiYunDun implements SecurityInterFace
{
    use CommonTrait;

    protected string $secretId = '07417ac3cb5bd68206da0994811f77e4';
    protected string $secretKey = '3382c51ddeb76d9da2f19f0d54a57f50';
    /**
     * 网易云盾 分类信息，100：色情，200：广告，260：广告法，300：暴恐，400：违禁，500：涉政，600：谩骂，700：灌水，900：其他，1100：涉价值观
     */
    public array $wyydTextGetOutOfLineType = [
        '100'  => '色情',
        '200'  => '广告',
        '300'  => '广告法',
        '400'  => '暴恐',
        '500'  => '违禁',
        '600'  => '涉政',
        '700'  => '谩骂',
        '800'  => '灌水',
        '900'  => '其他',
        '1100' => '涉价值观',
    ];

    /**
     * 过检分类列表：100：色情，110：性感低俗，200：广告，210：二维码，260：广告法，300：暴恐，400：违禁，500：涉政，800：恶心类，900：其他，1100：涉价值观
     */
    public array $wyydImgGetOutOfLineType = [
        '100'  => '色情',
        '110'  => '性感低俗',
        '200'  => '广告',
        '210'  => '二维码',
        '260'  => '广告法',
        '300'  => '暴恐',
        '400'  => '违禁',
        '500'  => '涉政',
        '800'  => '恶心类',
        '900'  => '其他',
        '1100' => '涉价值观',
    ];

    /**
     * 0：正常，100：色情，200：广告，260：广告法，300：暴恐，400：违禁，500：涉政，600：谩骂，1100：涉价值观
     */
    public array $wyydAudioGetOutOfLineType = [
        '0'    => '正常',
        '100'  => '色情',
        '200'  => '广告',
        '260'  => '广告法',
        '300'  => '暴恐',
        '400'  => '违禁',
        '500'  => '涉政',
        '600'  => '谩骂',
        '1100' => '涉价值观',
    ];

    /**
     * @param BaseData $data
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function checkSecurity(BaseData $data)
    {
        $data->setKeyData("secretId", $this->secretId);
        $data->setKeyData("timestamp", time() * 1000);
        $data->setKeyData("nonce", sprintf("%d", rand()));
        $data->setKeyData("version", "v5");
        $params = VisaVerification::toUtf8($data->getData());
        $data->setKeyData("signature", VisaVerification::genSignature($this->secretKey, $params));

        $params = $data->getData();

        $http = new Client();
        $re = $http->post($data->getUrl(), [
            'headers' => ['Content-Type' => 'application/x-www-form-urlencoded'],
            "form_params" => $params,
        ]);
        $result = new Result(new Response($re));
        $response = $result->getResponse()->getData();
        $str = '';
        $imgData  = new ImageData();
        $textData = new TextData();
        $audioData = new AudioData();
        if ($data->getUrl() == $imgData->URL_SYNC){
            $countImages = count(json_decode($data->getKeyData('images'),true));
            for ($j=0;$j<$countImages;$j++){
                $antispam = $response['result'][$j]['antispam'];
                for ($i=0;$i<=8;$i++){
                    $str .= !isset($antispam['labels'][$i]) && empty($antispam['labels'][$i]['level']) ? '' : $this->wyydImgGetOutOfLineType[$antispam['labels'][$i]['label']] . '，' ;
                }
                if ($response['code'] == 200 && $antispam['suggestion'] == 0 && $antispam['status'] == 2){
                    continue;
                }else if($response['code'] == 200 && $antispam['suggestion'] == 1){
                    return ['code' => 1, 'message' => '输入内容存在嫌疑，涉嫌'. $str .'请重新输入!'];
                }else{
                    return ['code' => 2, 'message' => '输入内容未通过绿色公约检测，请重新输入!'];
                }
            }
            return ['code' => 0, 'message' => '成功!'];
        }else if ($data->getUrl() == $textData->URL_SYNC){
            $antispam = $response['result']['antispam'];
            for ($i=0;$i<=8;$i++){
                $str .= empty($antispam['labels'][$i]['level']) ? '' : $this->wyydTextGetOutOfLineType[$antispam['labels'][$i]['label']] . '，' ;
            }
            if ($response['code'] == 200 && $antispam['suggestion'] == 0){
                return ['code' => 0, 'message' => '成功!'];
            }else if($response['code'] == 200 && $antispam['suggestion'] == 1){
                return ['code' => 1, 'message' => '输入内容存在嫌疑，涉嫌'. $str .'请重新输入!'];
            }else{
                return ['code' => 2, 'message' => '输入内容未通过绿色公约检测，请重新输入!'];
            }
        }else if ($data->getUrl() == $audioData->URL_SYNC){
            $antispam = $response['result']['antispam'];
            for ($i=0;$i<=8;$i++){
                $str .= empty($antispam['segments']['labels'][$i]['level']) ? '' : $this->wyydAudioGetOutOfLineType[$antispam['segments']['labels'][$i]['label']] . '，' ;
            }
            if ($response['code'] == 200 && $antispam['suggestion'] == 0 && $antispam['status'] == 2) {
                return ['code' => 0, 'message' => '成功!'];
            }else if($response['code'] == 200 && $antispam['suggestion'] == 1){
                return ['code' => 1, 'message' => '输入内容存在嫌疑，涉嫌'. $str .'请重新输入!'];
            }else{
                return ['code' => 2, 'message' => '输入内容未通过绿色公约检测，请重新输入!'];
            }
        }else{
            if ($response['code'] == 200 && $response['result']['status'] == 0){
                return ['code' => 0, 'message' => '成功!'];
            }else{
                return ['code' => 2, 'message' => '失败!'];
            }
        }


//        return $response;
    }

    public function setTextData(string $text, $row): BaseData
    {
        // TODO: Implement setTextData() method.
        $data = new TextData();
        $data->setKeyData('content', $text);
        $data->setKeyData('dataId', $this->generateUniqueString(64));

        return $data;
    }

    public function setImageData(array $data, $row): BaseData
    {
        $imageData = new ImageData();
        if (isset($row['syncAsync']) && $row['syncAsync'] == Enums::ASYNC){
            $imageData->setUrl($imageData->URL_ASYNC);
        }

        if (isset($row['dataCheckType'])){
            if ($row['dataCheckType'] == Enums::DATA_CHECK_TYPE_URL){
                $imageData->setKeyData('dataCheckType', Enums::IMG_DATA_CHECK_TYPE_URL);
            }else{
                $imageData->setKeyData('dataCheckType', Enums::IMG_DATA_CHECK_TYPE_BASE);
            }
        }else{
            $imageData->setKeyData('dataCheckType', Enums::IMG_DATA_CHECK_TYPE_URL);
        }

        $row = [];
        foreach ($data as $val) {
            $row[] = [
                'name' => $val, // 图片名称（或图片标识）， 该字段为回调信号字段，产品可以根据业务情况自行设计，如json结构，或者为图片url均可
                'type' => $imageData->getKeyData('dataCheckType'), // 如type=1，则该值为图片URL，请求时会校验图片url协议支持http，https，不支持本地file地址检测，如type=2，则该值为图片BASE64值。
                'data' => $val,
                "tokenId" => $this->generateUniqueString(64),
            ];
        }
        $imageData->setKeyData('images', json_encode($row, JSON_UNESCAPED_UNICODE));
        return $imageData;
    }

    public function setVideoData(string $video, $row): BaseData
    {
        // TODO: Implement setVideoDta() method.
        $data = new VideoData();
        $data->setKeyData('url', $video);
        $data->setKeyData('dataId', $this->generateUniqueString(64));

        return $data;
    }

    public function setAudioData(string $audio, $row): BaseData
    {
        // TODO: Implement setAudioData() method.
        $data = new AudioData();
        if (isset($row['dataCheckType'])){
            $data->setKeyData('dataCheckType', $row['dataCheckType']);
        }else{
            $data->setKeyData('dataCheckType', Enums::DATA_CHECK_TYPE_URL);
        }

        if ($data->getKeyData('dataCheckType') == Enums::DATA_CHECK_TYPE_URL){
            $data->setKeyData('url', $audio);
        }else{
            $data->setKeyData('data', $audio);
        }
        if (isset($row['syncAsync']) && $row['syncAsync'] == Enums::ASYNC){
            $data->setUrl($data->URL_ASYNC);
        }
        $data->setKeyData('dataId', $this->generateUniqueString(64));
        return $data;
    }
}
