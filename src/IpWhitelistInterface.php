<?php


namespace Moonpie\IpBlock;


interface IpWhitelistInterface
{
    /**
     * 是否在白名单内
     * @param $ip IpLocation 包含ip: ip地址,province省份,city城市country国家proCode省份代码,cityCode市代码addr解析的ip地理信息
     * @return bool
     */
    public function isPassed(IpLocation $location);

    /**
     * @param $ip IpLocation 同上使用的ip信息
     * @param $remark string 备注不安全的原因
     */
    public function logUnSafeIp(IpLocation $location, $remark = '');
}