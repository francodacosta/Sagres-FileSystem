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

namespace Sagres\Component\FileSystem;

use Sagres\Component\FileSystem\Exception\ResourceNotFoundException;
use Sagres\Component\FileSystem\Locator\Node\Node;

/**
 * The Locator class makes it easy to locate/specify files or folders in the
 * FileSystem
 *
 * @package Sagres
 * @subpackage FileSystem
 *
 * @author nuno costa <nuno@francodacosta.com>
 * @since 10-11-2011
 * @license MIT
 */
class Locator
{
    /**
     * Holds folder Node types
     * @var Array
     */
    private $folders = array();

    /**
     * Holds File Node and Link Node types
     * @var Array
     */
    private $files = array();

    /**
     * adds a single resource to the current list
     * @param String $path
     */
    public function addSingle($path)
    {
        $node = $this->resolveNode($path);

        $this->addNode($node);
    }

    /**
     * ads a filesystem node abstraction to the current set
     * @param Node $node
     * @throws \UnexpectedValueException
     */
    public function addNode(Node $node)
    {

        if ($node->isDir()) {
            $this->folders[] = $node;
        } else {
            $this->files[] = $node;
        }
    }

    /**
     * adds a folder resource to the list, optionally filtering out resources
     *
     * File Resources that do not match the $filter regular expression will *not*
     * be included.
     *
     * This function is **not recursive**, so **files inside other folders will not be included**
     *
     * @param String $path
     * @param string $filter - regular expression
     */
    public function add($path, $filter = '/.*/')
    {

        if(!is_dir($path)) {
            throw new ResourceNotFoundException($path . ' not found');
        }

        $path = new Node($path);
        $this->addNode($path);

        if ($dh = opendir($path)) {
            while (($filename = readdir($dh)) !== false) {
                if (in_array($filename, array('.','..'))) {
                    continue;
                }
                $file = $path . DIRECTORY_SEPARATOR . $filename;
                if(!is_dir($file)) {
                    if (preg_match( $filter, $filename) > 0) {
                        $this->addSingle($file);
                    }
                }
            }
            closedir($dh);
        }
    }


    /**
     * does the same than Locator::add() but recursive
     *
     * @see Locator::add
     *
     * @param String $path
     * @param String $filter regular expression
     */
    public function addRecursive($path, $filter = '/.*/') {
        $this->add($path, $filter);

        $found = glob($path . DIRECTORY_SEPARATOR . '*', GLOB_ONLYDIR);
        foreach($found as $subfolder) {
            $this->add($subfolder, $filter);
        }
    }

    /**
     * resolves a path into a node object
     *
     * @param String $node
     * @throws UnexpectedValueException
     * @return \Sagres\Component\FileSystem\Locator\Node\Node
     */
    public function resolveNode($path)
    {

//         try {
//             $type = filetype($path) ;
//         } catch (\Exception $e) {
//             throw new ResourceNotFoundException($path . ' was not found : ' . $e->getMessage());
//         }

        return new Node($path);

//         return $ret;
    }

    /**
     * get all files nodes
     * @return Array of Node Implementation (FileNode and LinkNode)
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * get all folder nodes
     *
     * @return Array of Node Implementation (FolderNode)
     */
    public function getFolders()
    {
        return $this->folders;
    }

    /**
     * return all nodes (file and folder)
     * @return Array of Node Implementation (FileNode, LinkNode and FolderNode)
     */
    public function get()
    {
        return array_merge($this->getFolders(), $this->getFiles());
    }
}