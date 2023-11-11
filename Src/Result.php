<?php

namespace App\Extend\SensitiveInformation\Src;

class Result
{
    protected Response $response;

    protected ?string $error = null;

    /**
     * @return string|null
     */
    public function getError(): ?string
    {
        return $this->error;
    }

    /**
     * @param string|null $error
     */
    public function setError(?string $error): void
    {
        $this->error = $error;
    }

    /**
     * @return Response
     */
    public function getResponse(): Response
    {
        return $this->response;
    }

    /**
     * @param Response $response
     */
    public function setResponse(Response $response): void
    {
        $this->response = $response;
    }

    public function __construct(Response $response)
    {
        $this->setResponse($response);
    }




}
