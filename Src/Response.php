<?php

namespace App\Extend\SensitiveInformation\Src;


class Response
{

    protected array $data = [];

    protected string $body;


    protected \GuzzleHttp\Psr7\Response $response;

    /**
     * @return \GuzzleHttp\Psr7\Response
     */
    public function getResponse(): \GuzzleHttp\Psr7\Response
    {
        return $this->response;
    }

    /**
     * @param \GuzzleHttp\Psr7\Response $response
     */
    public function setResponse(\GuzzleHttp\Psr7\Response $response): void
    {
        $this->response = $response;
    }

    public function __construct(\GuzzleHttp\Psr7\Response $response)
    {
        $this->response = $response;
        $this->body     = $response->getBody()->getContents();
        $this->setData(json_decode($this->body, true));
    }


    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param array $data
     */
    public function setData(array $data): void
    {
        $this->data = $data;
    }

    public function getBody(): array
    {
        return $this->body ? json_decode($this->body, true) : [];
    }


    protected function getDataValueByKey($key, $default = null)
    {
        return $this->data[$key] ?? $default;
    }


    public function getRawBody(): string
    {
        return $this->body;
    }

    public function __get(string $name)
    {
        return $this->getDataValueByKey($name);
    }


}
