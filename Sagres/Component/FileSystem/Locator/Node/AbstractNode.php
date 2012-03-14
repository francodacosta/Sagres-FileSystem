<?php
namespace Sagres\Component\FileSystem\Locator\Node;

abstract class AbstractNode implements NodeInterface
{
    const TYPE_FOLDER = 0;
    const TYPE_FILE = 1;
    const TYPE_LINK = 2;

    private $name = null;
    private $path = null;
    private $type = null;

    private $readable = false;
    private $writable = false;
    private $exists = false;

    public function __construct($path)
    {
        $this->processPath($path);
    }

    /**
     * processes the path and populates this class with all falues
     * @param unknown_type $path
     */
    protected function processPath($path)
    {
        $this->name = basename($path);
        $this->path = $path;
        $this->exists = file_exists($path);
        $this->readable = is_readable($path);
        $this->writable = is_writable($path);

    }

    public function isReadable()
    {
        return $this->readable;
    }
    public function isWritable()
    {
        return $this->writable;
    }
    public function exists()
    {
        return $this->exists;
    }

    public function getName()
    {
        return $this->name;
    }
    public function getPath()
    {
        return $this->path;
    }

    public function getType()
    {
        return $this->type;
    }

    protected function setType($type)
    {
        $this->type = $type;
    }

    public function __toString()
    {
        return $this->getPath();
    }
}