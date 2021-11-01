<?php


namespace Moonpie\IpBlock\Locator;


use GuzzleHttp\Client;
use Moonpie\IpBlock\IpLocation;
use Moonpie\IpBlock\IpLocatorInterface;
use think\helper\Arr;

class PcOnlineLocator extends AbstractCacheLocator
{
    public function getFallback(): ?IpLocatorInterface
    {
        return null;
    }

    protected function fetchResponse($ip)
    {
//
        $level        = 4;
        $client       = new Client(
            ['base_uri' => 'https://whois.pconline.com.cn/']
        );
        $json         = 'true';
        $response     = $client->get(
            'ipJson.jsp',
            ['query' => compact('ip', 'level', 'json')]
        );
        $content_type = $response->getHeader('Content-Type');
        if (stripos($content_type[0], 'charset=utf') === false) {
            $content = iconv(
                'GBK',
                'UTF-8',
                $response->getBody()->getContents()
            );
        } else {
            $content = $response->getBody()->getContents();
        }
        $ip_data = json_decode($content, true);

        if (JSON_ERROR_NONE === json_last_error()) {
            return $ip_data;
        } else {
            return false;
        }
    }

    protected function formatLocation($data, $ip)
    {
        $location = new IpLocation();

        return $location
            ->setIp(Arr::get($data, 'ip', $ip))
            ->setProvince(Arr::get($data, 'pro', ''))
            ->setDistinct(Arr::get($data, 'region', ''))
            ->setCity(Arr::get($data, 'city', ''))
            ->setNation(Arr::get($data, 'country', ''))
            ->setCityCode(Arr::get($data, 'cityCode', ''))
            ->setProvinceCode(Arr::get($data, 'proCode', ''))
            ->setFullLocation(Arr::get($data, 'addr', ''));
    }
}