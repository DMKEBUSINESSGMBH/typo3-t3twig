<?php

namespace DMK\T3twig\Tests\Unit\Cache;

use DMK\T3twig\Cache\TYPO3Cache;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use TYPO3\CMS\Core\Cache\Backend\SimpleFileBackend;
use TYPO3\CMS\Core\Cache\CacheManager;
use TYPO3\CMS\Core\Cache\Frontend\PhpFrontend;

class TYPO3CacheTest extends TestCase
{
    private $twigCache;
    private $cache;
    private $root;

    protected function setUp()
    {
        $this->twigCache = new TYPO3Cache();
        $this->cache = $this->prophesize(PhpFrontend::class);

        $cacheManager = $this->prophesize(CacheManager::class);
        $cacheManager->getCache(TYPO3Cache::CACHE_TYPE)->willReturn($this->cache->reveal());

        $this->twigCache->injectCacheManager($cacheManager->reveal());

        $this->root = vfsStream::setup();
    }

    public function testWrite()
    {
        $this->cache->set('foo', '#bar')
            ->shouldBeCalled();

        $this->twigCache->write('foo', 'bar');
    }

    public function testGetTimeStamp()
    {
        $this->cache->has('foo')->willReturn(true);

        $backend = $this->prophesize(SimpleFileBackend::class);
        $backend->getCacheDirectory()->willReturn($this->root->url());

        $this->cache->getBackend()->willReturn($backend);

        self::assertSame(0, $this->twigCache->getTimestamp('foo'));
    }

    public function testGetTimestampWhichDoesNotExists()
    {
        $this->cache->has('foo')->willReturn(false);

        self::assertSame(0, $this->twigCache->getTimestamp('foo'));
    }

    public function testLoad()
    {
        $this->cache->requireOnce('foo')->shouldBeCalled();

        $this->twigCache->load('foo');
    }

    /**
     * @dataProvider getKeyData
     */
    public function testGenerateKey($name, $class, $expected)
    {
        self::assertRegExp($expected, $this->twigCache->generateKey($name, $class));
    }

    public static function getKeyData()
    {
        return [
            [
                'name' => 'foo',
                'class' => '\Twig_Template',
                'expected' => '/Double_TYPO3_CMS_Core_Cache_Frontend_PhpFrontend_P(.*)_foo__Twig_Template/',
            ],
            [
                'name' => '@foo.html.twig',
                'class' => '\Twig\Template',
                'expected' => '/Double_TYPO3_CMS_Core_Cache_Frontend_PhpFrontend_P(.*)_foo_html_twig__Twig_Template/',
            ],
        ];
    }
}
