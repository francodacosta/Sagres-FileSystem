<?php
/**
 * This file is part of the Sagres package.
 *
 * (c) nuno costa <nuno@francodacosta.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package Sagres
 */
namespace Sagres\Component\FileSystem\Locator\Node;

/**
 * Base class for all nodes
 *
 * @package Sagres
 * @subpackage FileSystem
 *
 * @author nuno costa <nuno@francodacosta.com>
 * @since 10-11-2011
 * @license MIT
 */
class FolderNode extends AbstractNode
{
    /**
     * {@inheritdoc}
     * @param String $path
     */
    public function __construct($path)
    {
        $this->setType(self::TYPE_FOLDER);
        parent::__construct($path);
    }

    /**
     * {@inheritdoc}
     *
     * the DIRECTORY_SEPARATOR is appended to the path if not already there
     *
     * @see Sagres\Component\FileSystem\Locator\Node.AbstractNode::getPath()
     */
    public function getPath()
    {
        $path = parent::getPath();

        if(DIRECTORY_SEPARATOR != substr($path, -1)) {
            $path = $path . DIRECTORY_SEPARATOR;
        }

        return $path;
    }
}