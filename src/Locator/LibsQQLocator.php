<?php


namespace Moonpie\IpBlock\Locator;


use GuzzleHttp\Client;
use Moonpie\IpBlock\IpLocation;
use Moonpie\IpBlock\IpLocatorInterface;
use Psr\SimpleCache\CacheInterface;
use think\helper\Arr;

class LibsQQLocator extends AbstractCacheLocator
{
    private $key;

    public function __construct(
        $key,
        CacheInterface $cache = null,
        $lifetime = 86400,
        $cacheTmpl = 'app:cache-item:ip-{ip}:information'
    ) {
        parent::__construct($cache, $lifetime, $cacheTmpl);
        $this->key = $key;
    }

    protected function fetchResponse($ip)
    {
        $client   = new Client(
            [
                'base_uri' => 'https://apis.map.qq.com',
            ]
        );
        $key      = $this->key;
        $output   = 'JSON';
        $uri      = '/ws/location/v1/ip';
        $response = $client->get(
            $uri,
            ['query' => compact('ip', 'key', 'output')]
        );
        $content  = $response->getBody()->getContents();
        $ip_data  = json_decode($content, true);

        if (JSON_ERROR_NONE === json_last_error()) {
            if (Arr::get($ip_data, 'status', 100) === 0) {
                return $ip_data['result'];
            }
        }
        return false;
    }

    protected function formatLocation($data, $ip)
    {
        $location = new IpLocation();

        $location
            ->setIp(Arr::get($data, 'ip', $ip))
            ->setNation(Arr::get($data, 'ad_info.nation', ''))
            ->setProvince(Arr::get($data, 'ad_info.province', ''))
            ->setCity(Arr::get($data, 'ad_info.city', ''))
            ->setDistinct(Arr::get($data, 'ad_info.distinct', ''))
            ->setCityCode(Arr::get($data, 'ad_info.adcode', ''))
            ->setProvinceCode('')
            ->setExtra(Arr::get($data, 'location', []));
        $location->setFullLocation(
            trim(implode(' ', [
                $location->getNation(),
                $location->getProvince(),
                $location->getCity(),
            ]))
        );
        return $location;
    }

    public function getFallback(): ?IpLocatorInterface
    {
        if (is_null($this->fallback)) {
            $this->fallback = new PcOnlineLocator(
                $this->cache,
                $this->lifetime,
                $this->cacheTmpl
            );
        }
        return $this->fallback;
    }

}