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
abstract class AbstractNode implements NodeInterface
{
    const TYPE_FOLDER = 0;
    const TYPE_FILE = 1;
    const TYPE_LINK = 2;

    /**
     * resource name
     * @var String
     */
    private $name = null;
    /**
     * resource full path
     * @var String
     */
    private $path = null;

    /**
     * resource type
     * @var Integer
     */
    private $type = null;

    /**
     * is resource readable
     * @var boolean
     */
    private $readable = false;

    /**
     * is resource writable
     * @var boolean
     */
    private $writable = false;

    /**
     * is resource available to use
     * @var boolean
     */
    private $exists = false;

    /**
     * populates the class with relevante values form the path provided
     * @param unknown_type $path
     */
    public function __construct($path)
    {
        $this->processPath($path);
    }

    /**
     * processes the path and populates this class with all values
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

    /**
     * {@inheritdoc}
     * @see Sagres\Component\FileSystem\Locator\Node.NodeInterface::isReadable()
     */
    public function isReadable()
    {
        return $this->readable;
    }

    /**
     * {@inheritdoc}
     * @see Sagres\Component\FileSystem\Locator\Node.NodeInterface::isWritable()
     */
    public function isWritable()
    {
        return $this->writable;
    }

    /**
     * {@inheritdoc}
     * @see Sagres\Component\FileSystem\Locator\Node.NodeInterface::exists()
     */
    public function exists()
    {
        return $this->exists;
    }

    /**
     * {@inheritdoc}
     * @see Sagres\Component\FileSystem\Locator\Node.NodeInterface::getName()
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * {@inheritdoc}
     * @see Sagres\Component\FileSystem\Locator\Node.NodeInterface::getPath()
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * {@inheritdoc}
     * @see Sagres\Component\FileSystem\Locator\Node.NodeInterface::getType()
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Sets the resource type
     *
     * @see Sagres\Component\FileSystem\Locator\Node.NodeInterface::getType()
     *
     * @param Integer $type
     */
    protected function setType($type)
    {
        $this->type = $type;
    }


    /**
     * String representation of the resource
     *
     * in this case it is the full path
     *
     * @return String
     */
    public function __toString()
    {
        return $this->getPath();
    }
    /**
     * (non-PHPdoc)
     * @see Sagres\Component\FileSystem\Locator\Node.NodeInterface::getRealPath()
     */
    public function getRealPath()
    {
        return realpath($this->getPath());
    }

}