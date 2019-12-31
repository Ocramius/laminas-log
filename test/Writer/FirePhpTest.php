<?php

/**
 * @see       https://github.com/laminas/laminas-log for the canonical source repository
 * @copyright https://github.com/laminas/laminas-log/blob/master/COPYRIGHT.md
 * @license   https://github.com/laminas/laminas-log/blob/master/LICENSE.md New BSD License
 */

namespace LaminasTest\Log\Writer;

use Laminas\Log\Logger;
use Laminas\Log\Writer\FirePhp;
use Laminas\Log\Writer\FirePhp\FirePhpInterface;
use LaminasTest\Log\TestAsset\MockFirePhp;

/**
 * @category   Laminas
 * @package    Laminas_Log
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2013 Laminas (https://www.zend.com)
 * @license    https://getlaminas.org/license/new-bsd     New BSD License
 * @group      Laminas_Log
 */
class FirePhpTest extends \PHPUnit_Framework_TestCase
{
    protected $firephp;

    public function setUp()
    {
        $this->firephp = new MockFirePhp();

    }
    /**
     * Test get FirePhp
     */
    public function testGetFirePhp()
    {
        $writer = new FirePhp($this->firephp);
        $this->assertTrue($writer->getFirePhp() instanceof FirePhpInterface);
    }
    /**
     * Test set firephp
     */
    public function testSetFirePhp()
    {
        $writer   = new FirePhp($this->firephp);
        $firephp2 = new MockFirePhp();

        $writer->setFirePhp($firephp2);
        $this->assertTrue($writer->getFirePhp() instanceof FirePhpInterface);
        $this->assertEquals($firephp2, $writer->getFirePhp());
    }
    /**
     * Test write
     */
    public function testWrite()
    {
        $writer = new FirePhp($this->firephp);
        $writer->write(array(
            'message' => 'my msg',
            'priority' => Logger::DEBUG
        ));
        $this->assertEquals('my msg', $this->firephp->calls['trace'][0]);
    }
    /**
     * Test write with FirePhp disabled
     */
    public function testWriteDisabled()
    {
        $firephp = new MockFirePhp(false);
        $writer = new FirePhp($firephp);
        $writer->write(array(
            'message' => 'my msg',
            'priority' => Logger::DEBUG
        ));
        $this->assertTrue(empty($this->firephp->calls));
    }

    public function testConstructWithOptions()
    {
        $formatter = new \Laminas\Log\Formatter\Simple();
        $filter    = new \Laminas\Log\Filter\Mock();
        $writer = new FirePhp(array(
                'filters'   => $filter,
                'formatter' => $formatter,
                'instance'  => $this->firephp,
        ));
        $this->assertTrue($writer->getFirePhp() instanceof FirePhpInterface);
        $this->assertAttributeInstanceOf('Laminas\Log\Formatter\FirePhp', 'formatter', $writer);

        $filters = self::readAttribute($writer, 'filters');
        $this->assertCount(1, $filters);
        $this->assertEquals($filter, $filters[0]);
    }
}
