<?php
/**
 * ConfigTest class.
 */

namespace Alltube\Test;

use Alltube\Config;
use PHPUnit\Framework\TestCase;

/**
 * Unit tests for the Config class.
 */
class ConfigTest extends BaseTest
{
    /**
     * Config class instance.
     *
     * @var Config
     */
    private $config;

    /**
     * Prepare tests.
     */
    protected function setUp()
    {
        $this->config = Config::getInstance();
    }

    /**
     * Test the getInstance function.
     *
     * @return void
     */
    public function testGetInstance()
    {
        $this->assertEquals($this->config->convert, false);
        $this->assertConfig($this->config);
    }

    /**
     * Assert that a Config object is correctly instantiated.
     *
     * @param Config $config Config class instance.
     *
     * @return void
     */
    private function assertConfig(Config $config)
    {
        $this->assertInternalType('array', $config->params);
        $this->assertInternalType('string', $config->youtubedl);
        $this->assertInternalType('string', $config->python);
        $this->assertInternalType('string', $config->avconv);
        $this->assertInternalType('bool', $config->convert);
        $this->assertInternalType('bool', $config->uglyUrls);
        $this->assertInternalType('bool', $config->stream);
        $this->assertInternalType('bool', $config->remux);
        $this->assertInternalType('int', $config->audioBitrate);
    }

    /**
     * Test the setFile function.
     *
     * @return void
     */
    public function testSetFile()
    {
        if (PHP_OS == 'WINNT') {
            $configFile = 'config_test_windows.yml';
        } else {
            $configFile = 'config_test.yml';
        }

        $this->assertNull(Config::setFile(__DIR__.'/../config/'.$configFile));
    }

    /**
     * Test the setFile function with a missing config file.
     *
     * @return void
     * @expectedException Exception
     */
    public function testSetFileWithMissingFile()
    {
        Config::setFile('foo');
    }

    /**
     * Test the setOptions function.
     *
     * @return void
     */
    public function testSetOptions()
    {
        Config::setOptions(['appName' => 'foo']);
        $config = Config::getInstance();
        $this->assertEquals($config->appName, 'foo');
    }

    /**
     * Test the setOptions function.
     *
     * @return void
     */
    public function testSetOptionsWithoutUpdate()
    {
        Config::setOptions(['appName' => 'foo'], false);
        $config = Config::getInstance();
        $this->assertEquals($config->appName, 'foo');
    }

    /**
     * Test the setOptions function.
     *
     * @return void
     * @expectedException Exception
     */
    public function testSetOptionsWithBadYoutubedl()
    {
        Config::setOptions(['youtubedl' => 'foo']);
    }

    /**
     * Test the setOptions function.
     *
     * @return void
     * @expectedException Exception
     */
    public function testSetOptionsWithBadPython()
    {
        Config::setOptions(['python' => 'foo']);
    }


    /**
     * Test the getInstance function with the CONVERT and PYTHON environment variables.
     *
     * @return void
     */
    public function testGetInstanceWithEnv()
    {
        Config::destroyInstance();
        putenv('CONVERT=1');
        $config = Config::getInstance();
        $this->assertEquals($config->convert, true);
        putenv('CONVERT');
    }
}
