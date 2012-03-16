<?php
namespace Sagres\Component\FileSystem\Locator\Node;



/**
 * Test class for File.
 * Generated by PHPUnit on 2012-03-14 at 15:23:08.
 */
class NodeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var File
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
    }

    private function getFixturesPath($path)
    {
        return __DIR__ . '/../../../../../fixtures/' . $path;
    }
    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    protected function getReadWriteFile()
    {
        $file = $this->getFixturesPath('read.write.file');
        $o = new Node($file);
        return $o;
    }


    /**
     * @covers Sagres\Component\FileSystem\Locator\\Abstract::exists
     */
    public function testExists()
    {
         $o = $this->getReadWriteFile();
         $this->assertTrue($o->exists());
    }

    /**
     * @covers Sagres\Component\FileSystem\Locator\\Abstract::__toString
     */
    public function test__toString()
    {
         $o = $this->getReadWriteFile();
         $this->assertEquals( __DIR__ . '/../../../../../fixtures/read.write.file', $o);
    }

}
?>
