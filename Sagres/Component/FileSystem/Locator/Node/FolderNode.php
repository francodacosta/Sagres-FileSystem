<?php
namespace Sagres\Component\FileSystem\Locator\Node;

class FolderNode extends AbstractNode
{
    public function __construct($path)
    {
        $this->setType(self::TYPE_FOLDER);
        parent::__construct($path);
    }

    public function getPath()
    {
        $path = parent::getPath();

        if(DIRECTORY_SEPARATOR != substr($path, -1)) {
            $path = $path . DIRECTORY_SEPARATOR;
        }

        return $path;
    }
}