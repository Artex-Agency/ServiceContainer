<?php declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use Artex\DIContainer\ConfigLoader;

class ConfigLoaderTest extends TestCase
{
    public function testLoadValidConfig(): void
    {
        $loader = new ConfigLoader();
        $filePath = __DIR__ . '/fixtures/config.php';
        if (!file_exists($filePath)) {
            $this->fail("Config file does not exist at path: $filePath");
        }
        $loader->load($filePath);
        $config = $loader->all();
    
        $this->assertIsArray($config);
        $this->assertArrayHasKey('app', $config);
        $this->assertSame('TestApp', $config['app']['name']);
    }

    public function testLoadInvalidConfigThrowsException(): void
    {
        $loader = new ConfigLoader();

        $this->expectException(\InvalidArgumentException::class);
        $loader->load('invalid/path/config.php');
    }
}
