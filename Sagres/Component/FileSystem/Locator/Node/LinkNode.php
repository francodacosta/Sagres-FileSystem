<?php
namespace Sagres\Component\FileSystem\Locator\Node;

class LinkNode extends AbstractNode
{
    public function __construct($path)
    {
        $this->setType(self::TYPE_LINK);
        parent::__construct($path);
    }
}