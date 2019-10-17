<?php

namespace DMK\T3twig\Cache;

use Twig\Cache\CacheInterface;
use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Cache\Frontend\PhpFrontend;

class TYPO3Cache implements CacheInterface
{
    const CACHE_TYPE = 't3twig';

    /**
     * @var PhpFrontend
     */
    private $delegate;

    public function injectCacheManager(CacheManager $cacheManager)
    {
        $this->delegate = $cacheManager->getCache(self::CACHE_TYPE);
    }

    public function generateKey($name, $className)
    {
        $cacheKey = implode('_', [get_class($this->delegate), $name, $className]);
        // strip all unallowed characters
        $cacheKey = preg_replace('/[^A-Za-z0-9-_\\-]/', '_', $cacheKey);

        return $cacheKey;
    }

    public function write($key, $content)
    {
        $this->delegate->set($key, '#'.$content);
    }

    public function load($key)
    {
        $this->delegate->requireOnce($key);
    }

    public function getTimestamp($key)
    {
        if ($this->delegate->has($key)) {
            return 0;
        }

        $path = sprintf(
            '%s.%s.php',
            $this->delegate->getBackend()->getCacheDirectory(),
            $key
        );

        // Ignore errors, because they may not be relevant at this point.
        set_error_handler(function() {});
        $time = filemtime($path);
        restore_error_handler();

        return (int) $time;
    }
}
