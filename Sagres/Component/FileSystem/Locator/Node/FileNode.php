<?php
namespace Sagres\Component\FileSystem\Locator\Node;

class FileNode extends AbstractNode
{
    public function __construct($path)
    {
        $this->setType(self::TYPE_FILE);
        parent::__construct($path);
    }
}