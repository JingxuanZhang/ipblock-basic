<?php


namespace Moonpie\IpBlock\Whitelist;


use Moonpie\IpBlock\IpLocation;
use Moonpie\IpBlock\IpWhitelistInterface;
use Psr\Log\LoggerInterface;

class BasicIpWhitelist implements IpWhitelistInterface
{
    protected $whitelist
        = [
            '局域网',
            '本机地址',
        ];
    protected $logger;

    public function __construct(
        $keywords = [],
        $override = false,
        LoggerInterface $logger = null
    ) {
        $this->logger = $logger;
        if ($override) {
            $this->whitelist = $keywords ?? $this->whitelist;
        } else {
            $this->whitelist = array_merge($keywords, $this->whitelist);
        }
    }

    public function isPassed(IpLocation $ip)
    {
        $continue = false;
        $content  = $ip->getFullLocation();
        foreach ($this->whitelist as $w) {
            if (mb_strpos($content, $w) !== false) {
                $continue = true;
                break;
            }
        }

        return $continue;
    }

    public function logUnSafeIp(IpLocation $ip, $remark = '')
    {
        if ($this->logger) {
            $this->logger->alert(
                '记录的IP异常信息是{remark}',
                [
                    'remark' => $remark,
                ]
            );
        }
    }
    public function getWhitelist()
    {
        return $this->whitelist;
    }
}