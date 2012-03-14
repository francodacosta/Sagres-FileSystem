<?php
namespace Sagres\Component\FileSystem;

use Sagres\Component\FileSystem\Exception\ResourceNotFoundException;
use Sagres\Component\FileSystem\Locator\Node\FolderNode;
use Sagres\Component\FileSystem\Locator\Node\FileNode;
use Sagres\Component\FileSystem\Locator\Node\LinkNode;
use Sagres\Component\FileSystem\Locator\Node\AbstractNode;

class Locator
{
    private $folders = array();
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

    public function addNode(AbstractNode $node)
    {
        switch ($node->getType()) {
            case $node::TYPE_LINK:
            case $node::TYPE_FILE:
                $this->files[] = $node;
                break;

            case $node::TYPE_FOLDER:
                $this->folders[] = $node;
                break;

            default:
                throw new \UnexpectedValueException('Can not process nodes of type ' . $node->getType());
                break;
        }
    }

    /**
     * adds a folder resource to the list, optionally filtering out the resources
     * that do not match the expression, this is not recursive, so files inside
     * other folders will not be included
     *
     * @param String $path
     * @param string $filter - regular expression
     */
    public function add($path, $filter = '/.*/')
    {

        if(!is_dir($path)) {
            throw new ResourceNotFoundException($path . ' not found');
        }

        $path = new FolderNode($path);
        $this->addNode($path);


        if ($dh = opendir($path)) {
            while (($filename = readdir($dh)) !== false) {
                $file = $path . $filename;
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
     * @see add
     * does the same but recursive
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
     * @param unknown_type $node
     * @throws UnexpectedValueException
     * @return \Sagres\Component\FileSystem\Locator\Node\AbstractNode
     */
    private function resolveNode($node)
    {
        $ret = null;
        if (is_dir($node)) {
            $ret = new FolderNode($node);
        }
        if (is_file($node)) {
            $ret = new FileNode($node);
        }

        if (is_link($node)) {
            $ret = new LinkNode($node);
        }

        if (is_null($ret)) {
            throw new \UnexpectedValueException('Can not resolve node for path ' . $node);
        }

        return $ret;
    }

    /**
     * get all files nodes
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * get all folder nodes
     */
    public function getFolders()
    {
        return $this->folders;
    }

    /**
     * return all nodes (file and folder)
     */
    public function get()
    {
        return array_merge($this->getFolders(), $this->getFiles());
    }
}