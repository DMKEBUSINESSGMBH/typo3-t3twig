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
        return implode(
            '_',
            [
                strtr(get_class($this->delegate),'\\','-'),
                str_replace('.', '_', $name),
                $className
            ]
        );
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