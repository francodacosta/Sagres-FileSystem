<?php
namespace Sagres\Component\FileSystem;



/**
 * Test class for Locator.
 * Generated by PHPUnit on 2012-03-14 at 15:52:39.
 */

use Sagres\Component\FileSystem\Locator\Node\AbstractNode;

class LocatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Locator
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Locator;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    private function getFixturesPath($path)
    {
        return __DIR__ . '/../../../fixtures/' . $path;
    }

    /**
     * @covers Sagres\Component\FileSystem\Locator::addSingle
     * @covers Sagres\Component\FileSystem\Locator::resolveNode
     * @covers Sagres\Component\FileSystem\Locator::addNode
     * @covers Sagres\Component\FileSystem\Locator::getFiles
     */
    public function testAddSingle()
    {
        $file = $this->getFixturesPath('read.write.file');
        $this->object->addSingle($file);

        $files = $this->object->getFiles();
        $folders = $this->object->getFolders();
        $this->assertEquals(1, count($files));
        $this->assertEquals(0, count($folders));
        $this->assertTrue($files[0] instanceof \Sagres\Component\FileSystem\Locator\Node\FileNode);
    }
    /**
     * @covers Sagres\Component\FileSystem\Locator::addSingle
     * @covers Sagres\Component\FileSystem\Locator::resolveNode
     * @covers Sagres\Component\FileSystem\Locator::addNode
     * @covers Sagres\Component\FileSystem\Locator::getFolders
     * @covers Sagres\Component\FileSystem\Locator::get
     */
    public function testAddSingle_folder()
    {
        $file = $this->getFixturesPath('');
        $this->object->addSingle($file);

        $files = $this->object->getFiles();
        $folders = $this->object->getFolders();
        $all = $this->object->get();

        $this->assertEquals(0, count($files));
        $this->assertEquals(1, count($folders));
        $this->assertEquals(1, count($all));
        $this->assertTrue($folders[0] instanceof \Sagres\Component\FileSystem\Locator\Node\FolderNode);
    }
    /**
     * @covers Sagres\Component\FileSystem\Locator::addSingle
     * @covers Sagres\Component\FileSystem\Locator::resolveNode
     * @covers Sagres\Component\FileSystem\Locator::addNode
     */
    public function testAddSingle_link()
    {
        $file = $this->getFixturesPath('linked.file');
        $this->object->addSingle($file);

        $files = $this->object->getFiles();
        $folders = $this->object->getFolders();

        $this->assertEquals(1, count($files));
        $this->assertEquals(0, count($folders));
        $this->assertTrue($files[0] instanceof \Sagres\Component\FileSystem\Locator\Node\LinkNode);
    }
    /**
     * @covers Sagres\Component\FileSystem\Locator::addSingle
     * @covers Sagres\Component\FileSystem\Locator::resolveNode
     * @covers Sagres\Component\FileSystem\Locator::addNode
     *
     * @expectedException Sagres\Component\FileSystem\Exception\ResourceNotFoundException
     */
    public function testAddSingle_not_found()
    {
        $file = $this->getFixturesPath('not.found');
        $this->object->addSingle($file);

    }

    /**
     * @covers Sagres\Component\FileSystem\Locator::addNode
     * @expectedException UnexpectedValueException
     */
    public function testAddNode_invalid()
    {
       $this->object->addNode(new FakeNode(''));
    }

    /**
     * @covers Sagres\Component\FileSystem\Locator::resolveNode
     * @expectedException UnexpectedValueException
     */
    public function testAddNode_invalid_file()
    {
        $this->object->addSingle('/dev/sda');
    }
    /**
     * @covers Sagres\Component\FileSystem\Locator::add
     * @todo Implement testAdd().
     */
    public function testAdd()
    {
        $file = $this->getFixturesPath('');
        $this->object->add($file);

        $files = $this->object->getFiles();
        $folders = $this->object->getFolders();

        $this->assertEquals(2, count($files));
        $this->assertEquals(1, count($folders)); // add only adds files and the base folder
    }
    /**
     * @covers Sagres\Component\FileSystem\Locator::add
     */
    public function testAdd_filter()
    {
        $file = $this->getFixturesPath('');
        $this->object->add($file, '/.*\write\.file/');

        $files = $this->object->getFiles();
        $folders = $this->object->getFolders();

        $this->assertEquals(1, count($files));
        $this->assertEquals(1, count($folders));
    }
    /**
     * @covers Sagres\Component\FileSystem\Locator::add
     * @expectedException Sagres\Component\FileSystem\Exception\ResourceNotFoundException
     */
    public function testAdd_notFound()
    {
        $this->object->add('dfsdfsdfsdf');

    }

    /**
     * @covers Sagres\Component\FileSystem\Locator::addRecursive
     */
    public function testAddRecursive()
    {
        $file = $this->getFixturesPath('');
        $this->object->addRecursive($file);

        $files = $this->object->getFiles();
        $folders = $this->object->getFolders();

        $this->assertEquals(2, count($files));
        $this->assertEquals(2, count($folders));
    }

}

class FakeNode extends AbstractNode
{
    public function __construct($path){
        $this->setType(98);
    }
}
?>
