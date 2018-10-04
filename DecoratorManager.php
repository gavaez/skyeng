<?php

namespace src\Decorator;

use DateTime;
use Exception;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Log\LoggerInterface;
use src\Integration\DataProvider;

class DecoratorManager extends DataProvider
{
    /** @var LoggerInterface */
    private $logger;

    /** @var CacheItemPoolInterface */
    protected $cache;

    /**
     * @param string $host
     * @param string $user
     * @param string $password
     * @param CacheItemPoolInterface $cache
     */
    public function __construct(
        string $host, string $user, string $password, CacheItemPoolInterface $cache
    ) {
        parent::__construct($host, $user, $password);

        $this->cache = $cache;
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function getResponse(array $input): array
    {
        try {
            $cacheKey  = $this->getCacheKey($input);
            $cacheItem = $this->cache->getItem($cacheKey);

            if ($cacheItem->isHit()) {
                return $cacheItem->get();
            }

            $result = parent::getResponse($input);

            $cacheItem
                ->set($result)
                ->expiresAt((new DateTime())->modify('+1 day'))
            ;

            return $result;
        } catch (Exception $e) {
            ($logger = $this->getLogger()) and $logger->critical('Error');

            return [];
        }
    }

    /**
     * @param array $input
     *
     * @return string
     */
    public function getCacheKey(array $input): string
    {
        return json_encode($input);
    }
}
