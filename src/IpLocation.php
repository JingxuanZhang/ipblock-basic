<?php


namespace Moonpie\IpBlock;


use think\contract\Arrayable;
use think\helper\Arr;

final class IpLocation implements Arrayable
{
    /**
     * @var string 所属IP
     */
    protected $ip;
    /**
     * @var string 国家
     */
    protected $nation;
    /**
     * @var string 省
     */
    protected $province;
    /**
     * @var string 城市
     */
    protected $city;
    /**
     * @var string 区
     */
    protected $distinct;
    /**
     * @var string 行政区代码
     */
    protected $cityCode;
    /**
     * @var string 省份code
     */
    protected $provinceCode;
    protected $extra = [];

    /**
     * @return string
     */
    public function getFullLocation(): string
    {
        return $this->fullLocation;
    }

    /**
     * @param string $fullLocation
     * @return IpLocation
     */
    public function setFullLocation(string $fullLocation): IpLocation
    {
        $this->fullLocation = $fullLocation;

        return $this;
    }

    /**
     * @var string 全地址信息
     */
    protected $fullLocation;

    public function toArray(): array
    {
        return [
            'ip'           => $this->ip,
            'addr'         => $this->fullLocation,
            'nation'       => $this->nation,
            'province'     => $this->province,
            'city'         => $this->city,
            'distinct'     => $this->distinct,
            'provinceCode' => $this->provinceCode,
            'cityCode'     => $this->cityCode,
            'extra'        => $this->extra,
        ];
    }

    /**
     * @return string
     */
    public function getIp(): string
    {
        return $this->ip;
    }

    /**
     * @param string $ip
     * @return IpLocation
     */
    public function setIp(string $ip): IpLocation
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * @return string
     */
    public function getNation(): string
    {
        return $this->nation;
    }

    /**
     * @param string $nation
     * @return IpLocation
     */
    public function setNation(string $nation): IpLocation
    {
        $this->nation = $nation;

        return $this;
    }

    /**
     * @return string
     */
    public function getProvince(): string
    {
        return $this->province;
    }

    /**
     * @param string $province
     * @return IpLocation
     */
    public function setProvince(string $province): IpLocation
    {
        $this->province = $province;

        return $this;
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @param string $city
     * @return IpLocation
     */
    public function setCity(string $city): IpLocation
    {
        $this->city = $city;

        return $this;
    }

    /**
     * @return string
     */
    public function getDistinct(): string
    {
        return $this->distinct;
    }

    /**
     * @param string $distinct
     * @return IpLocation
     */
    public function setDistinct(string $distinct): IpLocation
    {
        $this->distinct = $distinct;

        return $this;
    }

    /**
     * @return string
     */
    public function getCityCode(): string
    {
        return $this->cityCode;
    }

    /**
     * @param string $cityCode
     * @return IpLocation
     */
    public function setCityCode(string $cityCode): IpLocation
    {
        $this->cityCode = $cityCode;

        return $this;
    }

    /**
     * @return string
     */
    public function getProvinceCode(): string
    {
        return $this->provinceCode;
    }

    /**
     * @param string $provinceCode
     * @return IpLocation
     */
    public function setProvinceCode(string $provinceCode): IpLocation
    {
        $this->provinceCode = $provinceCode;

        return $this;
    }


    /**
     * @inheritDoc
     */
    public function __toString()
    {
        return trim(
            implode(
                ',',
                [
                    "Ip: {$this->ip}",
                    "Country: {$this->nation}",
                    "Province: {$this->province}",
                    "City: {$this->city}",
                    "Distinct: {$this->distinct}",
                ]
            )
        );
    }

    public static function fromArray($array)
    {
        $location = new static();

        return $location->setFullLocation(Arr::get($array, 'addr', ''))
                        ->setIp(Arr::get($array, 'ip', ''))
                        ->setNation(Arr::get($array, 'nation', ''))
                        ->setProvince(Arr::get($array, 'province', ''))
                        ->setProvinceCode(Arr::get($array, 'provinceCode', ''))
                        ->setCity(Arr::get($array, 'city', ''))
                        ->setCityCode(Arr::get($array, 'cityCode', ''))
                        ->setDistinct(Arr::get($array, 'distinct', ''))
                        ->setExtra(Arr::get($array, 'extra', []));
    }

    /**
     * @return mixed
     * @param null $key
     * @param null $default
     * @return mixed
     */
    public function getExtra($key = null, $default = null)
    {
        return Arr::get($this->extra, $key, $default);
    }

    /**
     * @param $key
     * @param null $value
     * @return IpLocation
     */
    public function setExtra($key, $value = null): IpLocation
    {
        if (is_array($key)) {
            $this->extra = $key;
        }else {
            Arr::set($this->extra, $key, $value);
        }
        return $this;
    }

}