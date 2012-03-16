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
class Node extends \SplFileInfo
{
//     /**
//      * populates the class with relevante values form the path provided
//      * @param unknown_type $path
//      */
    public function __construct($path)
    {
        $path = rtrim( $path, DIRECTORY_SEPARATOR );
        parent::__construct($path);
    }

    /**
     * returns true if resource exists
     * @return boolean
     */
    public function exists()
    {
        return file_exists($this->getPath());
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
        return $this->getRealPath();
    }

}