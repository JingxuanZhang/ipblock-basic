<?php


namespace Moonpie\IpBlock\Locator;


use Moonpie\IpBlock\IpLocation;
use Moonpie\IpBlock\IpLocatorInterface;
use Psr\SimpleCache\CacheInterface;

abstract class AbstractCacheLocator implements IpLocatorInterface
{

    protected $cache;
    protected $cacheTmpl;
    protected $lifetime = 86400;
    protected $fallback;

    public function __construct(
        CacheInterface $cache = null,
        $lifetime = 86400,
        $cacheTmpl = 'app:cache-item:ip-{ip}:information'
    ) {
        $this->cacheTmpl = $cacheTmpl;
        $this->cache     = $cache;
        $this->lifetime  = $lifetime;
    }

    public function getLocation($ip): IpLocation
    {
        $cache_key = strtr(
            $this->cacheTmpl,
            [
                '{ip}' => $ip,
            ]
        );
        if (is_null($this->cache)) {
            $use_fresh = true;
        } else {
            $use_fresh = !$this->cache->has($cache_key);
        }
        $need_cache = false;
        if ($use_fresh) {
            //转成统一格式
            $ip_data = $this->fetchResponse($ip);
            if (false === $ip_data) {
                //使用后续方案
                $fallback = $this->getFallback();
                if (!is_null($fallback)) {
                    $ip_data = $fallback->getLocation($ip);
                    return $ip_data;
                }
            }
            if (false !== $ip_data) {
                $location = $this->formatLocation($ip_data, $ip);

                $need_cache = true;
            } else {
                $location = IpLocation::fromArray(['ip' => $ip]);
            }


        } else {
            $location
                = IpLocation::fromArray(
                $this->cache->get($cache_key, ['ip' => $ip])
            );
        }
        if (!is_null($this->cache) && $need_cache) {
            $this->cache->set(
                $cache_key,
                $location->toArray(),
                $this->lifetime
            );
        }

        return $location;
    }

    protected abstract function fetchResponse($ip);
    protected abstract function formatLocation($data, $ip);
}