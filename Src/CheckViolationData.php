<?php

namespace App\Extend\SensitiveInformation\Src;

use Closure;
use Exception;

class CheckViolationData
{

    protected string $driver;


    protected string $checkType;


    protected BaseData $baseData;


    protected SecurityInterFace $securityObj;


    protected SecurityManager $securityManager;

    protected BaseDataManager $dataManager;


    public function __construct($driver = null)
    {
        $this->setDriver($driver ?? $this->getDefaultDriver());
        $this->setSecurityManager(new SecurityManager());
        $this->setDataManager(new BaseDataManager());

        $this->setSecurityObj($this->getSecurityManager()->getSecurityByDriver($this->getDriver()));
    }


    /**
     * @return SecurityManager
     */
    public function getSecurityManager(): SecurityManager
    {
        return $this->securityManager;
    }

    /**
     * @param SecurityManager $securityManager
     */
    public function setSecurityManager(SecurityManager $securityManager): void
    {
        $this->securityManager = $securityManager;
    }

    /**
     * @return BaseDataManager
     */
    public function getDataManager(): BaseDataManager
    {
        return $this->dataManager;
    }

    /**
     * @param BaseDataManager $dataManager
     */
    public function setDataManager(BaseDataManager $dataManager): void
    {
        $this->dataManager = $dataManager;
    }


    /**
     * @return string
     */
    public function getCheckType(): string
    {
        return $this->checkType;
    }

    /**
     * @param string $checkType
     */
    public function setCheckType(string $checkType): void
    {
        $this->checkType = $checkType;
    }

    /**
     * @return BaseData
     */
    public function getBaseData(): BaseData
    {
        return $this->baseData;
    }

    /**
     * @param BaseData $baseData
     */
    public function setBaseData(BaseData $baseData): void
    {
        $this->baseData = $baseData;
    }

    /**
     * @return SecurityInterFace
     */
    public function getSecurityObj(): SecurityInterFace
    {
        return $this->securityObj;
    }

    /**
     * @param SecurityInterFace $securityObj
     */
    public function setSecurityObj(SecurityInterFace $securityObj): void
    {
        $this->securityObj = $securityObj;
    }


    /**
     * @return string
     */
    public function getDriver(): string
    {
        return $this->driver;
    }

    /**
     * @param string $driver
     */
    public function setDriver(string $driver): void
    {
        $this->driver = $driver;
    }


    protected function getDefaultDriver(): string
    {
        //从配置文件获取
        return config('security.platform','shumei');
    }


    /**
     * @throws Exception
     */
    public function check($type, $data, ?Closure $fun = null)
    {
        if ($fun){
            $row = $fun(new BaseData());
            $this->setBaseData($this->getDataManager()->getDataObj($type, $data, $this->getSecurityObj(), $row->getData()));
        }else{
            $this->setBaseData($this->getDataManager()->getDataObj($type, $data, $this->getSecurityObj()));
        }

        return $this->getSecurityObj()->checkSecurity($this->getBaseData());
    }


}
