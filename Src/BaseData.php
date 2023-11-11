<?php

namespace App\Extend\SensitiveInformation\Src;

class BaseData implements BaseDataInterFace
{
    protected array $data = [];

    protected string $url = "";

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     */
    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    public function __construct()
    {

    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }


    public function setData($data): array
    {
        $this->data = $data;
    }


    public function setKeyData($key, $value): void
    {
        $this->data[$key] = $value;
    }

    public function getKeyData($key)
    {
        return $this->data[$key];
    }


    public function toArray(): array
    {
        return $this->getData();
    }

    public function setDataCheckType($str): void
    {
        $this->setKeyData('dataCheckType', $str);
    }

    public function setSynchronousAsynchronous($str): void
    {
        $this->setKeyData('syncAsync', $str);
    }
}
