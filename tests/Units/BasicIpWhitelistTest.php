<?php

namespace Moonpie\Test\IpBlock\Units;

use Moonpie\IpBlock\IpLocation;
use Moonpie\IpBlock\Locator\PcOnlineLocator;
use Moonpie\IpBlock\Whitelist\BasicIpWhitelist;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class BasicIpWhitelistTest extends TestCase
{
    public function testOverride()
    {
        $logger = $this->getMockBuilder(LoggerInterface::class)
            ->getMock();
        ;
        $keywords = ['代替换数据'];
        $override = true;
        $object = new BasicIpWhitelist($keywords, $override, $logger);
        $this->assertEquals($keywords, $object->getWhitelist());
        $object2 = new BasicIpWhitelist($keywords, false, $logger);
        $this->assertGreaterThan(1, count($object2->getWhitelist()));
    }
    public function providerIps()
    {
        return [
            'localhost' => ['127.0.0.1', '本机地址'],
        ];
    }

    /**
     * @param $ip
     * 2@param $addr
     * @dataProvider providerIps
     */
    public function testPcOnline($ip, $addr)
    {
        $api = new PcOnlineLocator();
        $location = $api->getLocation($ip);
        $this->assertInstanceOf(IpLocation::class, $location, '解析的数据不是封装的结构');
        $this->assertStringContainsString($addr, $location->getFullLocation());
    }
}
