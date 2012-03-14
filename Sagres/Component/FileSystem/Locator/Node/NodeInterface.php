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
 * Interface for all nodes
 *
 * @package Sagres
 * @subpackage FileSystem
 *
 * @author nuno costa <nuno@francodacosta.com>
 * @since 10-11-2011
 * @license MIT
 */
Interface NodeInterface
{
    /**
     * (NON PHP DOC)
     * @param String $path
     */
    public function __construct($path);

    /**
     * Can we read the file ?
     * @return boolean true if we can read it
     */
    public function isReadable();

    /**
     * Can we write to the file ?
     * @return boolean true if we can
     */
    public function isWritable();

    /**
     * resource is found
     * @return boolean true if it is available for us
     */
    public function exists();

    /**
     * gets the resource name
     * @return String
     */
    public function getName();

    /**
     * The resource full path
     * @return String
     */
    public function getPath();

    /**
     * the resource type
     *
     * See the TYPE_ constants for a list of supported types
     * @return Integer
     */
    public function getType();
}