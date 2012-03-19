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
use Sagres\Component\FileSystem\Exception\IOException;

use Sagres\Component\FileSystem\Exception\ResourceExistsException;

use Sagres\Component\FileSystem\Node;
use Sagres\Component\FileSystem\Locator;
/**
 * Copy files
 *
 * It handles the logic needed to make copying files in php esay and powerfull
 *
 * @package Sagres
 * @subpackage FileSystem
 *
 * @author nuno costa <nuno@francodacosta.com>
 * @since 10-11-2011
 * @license MIT
 */
class Copy
{


    /**
     * Converts a path into an SplFileInfo Object if it is not already one
     * @param SplFileInfo|String|Node $file
     * @return SplFileInfo
     */
    private function createNode($file)
    {
        if (! $file instanceof \Sagres\Component\FileSystem\Node) {
            $file = new node($file);
        }

        return $file;
    }

    /**
     * calculates the destination filename
     * if destinationFile is a folder it will append $sourceFile name to destination
     * @param Node $sourceFile
     * @param Node $destinationFile
     * @return Node
     */
    private function computeFinalDestination(Node $sourceFile, Node $destinationFile)
    {
        if($destinationFile->isDir()) {
            $destinationFile = new Node($destinationFile . DIRECTORY_SEPARATOR . $sourceFile->getFileName());
        }

        return $destinationFile;
    }


    /**
     *
     * calculates the relative path of a Resource, based on the current path,
     * the initial folder path and the new folder path
     *
     * file: /etc/abc/def
     * source: /etc
     * new: /tmp
     *
     * result: /tmp/abc/def
     *
     * @param Node $sourceFile
     * @param String $sourceFolder
     * @param String $destinationFolder
     */

    private function calculateRelativePath(Node $sourceFile, $sourceFolder, $destinationFolder)
    {
        if ($sourceFile->isDir()) {
            $sourceString = $sourceFile->getRealPath();
        } else {
            $sourceString = dirname($sourceFile->getRealPath());
        }
        return str_replace($sourceFolder, $destinationFolder, $sourceString);
    }


    public function copy($sourceFile, $destinationFile, $overwrite = false)
    {
        $sourceFile = $this->createNode($sourceFile);
        $destinationFile = $this->createNode($destinationFile);
        $destinationFile = $this->computeFinalDestination($sourceFile, $destinationFile);

        if ($sourceFile->isDir()) {
            throw new \UnexpectedValueException(sprintf(
                "%s is a folder, copy() can only copy files",
                 $sourceFile
            ));
        }

        if ($destinationFile->exists() && !$overwrite) {
            throw new ResourceExistsException(sprintf("%s exists and \$overwrite is false", $destinationFile));
        }

        try {
            copy($sourceFile, $destinationFile);
        } catch (\Exception $e) {
            // @codeCoverageIgnoreStart
            throw new IOException(sprintf("%s => %s, Can not copy, reason: %s", $sourceFile, $destinationFile, $e->getMessage()));
            // @codeCoverageIgnoreEnd
        }
    }

    public function copyFolder($source, $destination, $overwrite = false)
    {
        $sourceFile = $this->createNode($sourceFile);
        $destinationFile = $this->createNode($destinationFile);

        if (!$sourceFile->isFolder()) {
            throw new \UnexpectedValueException(sprintf(
                    "%s : source needs to be a folder",
                    $sourceFile
            ));
        }

        if (!$destination->isFolder()) {
            throw new \UnexpectedValueException(sprintf(
                    "%s : destination needs to be a folder",
                    $sourceFile
            ));
        }

        $locator = new Locator();
        $locator->addRecursive($sourceFile->getRealPath());

        $folders = $locator->getFolders();

        foreach($folders as $folder) {
            mkdir($this->calculateRelativePath($folder, $source, $destination), null,  true);
        }

        $files = $locator->getFiles();
    }
}