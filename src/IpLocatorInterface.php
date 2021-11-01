<?php


namespace Moonpie\IpBlock;


interface IpLocatorInterface
{
    public function getLocation($ip): IpLocation;
    public function getFallback(): ?IpLocatorInterface;
}