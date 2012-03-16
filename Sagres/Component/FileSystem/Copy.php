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

use Sagres\Component\FileSystem\Locator\Node\AbstractNode;
use Sagres\Component\FileSystem\Locator\Node\FileNode;
use Sagres\Component\FileSystem\Locator\Locator;
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


    public function copy($sourceFile, $destinationFile, $overwrite = false, $mode = null)
    {
        if (! $sourcefile instanceof Sagres\Component\FileSystem\Locator\Node\AbstractNode) {
            $sourceFile = Locator::resolveNode($sourceFile);
        }

        if ($sourceFile->isFolder()) {
            throw new \UnexpectedValueException(sprintf(
                "%s is a folder, copyFile() can only copy file, use copySet() function instead",
                 $sourceFile
            ));
        }

        if (! $destinationFile instanceof Sagres\Component\FileSystem\Locator\Node\AbstractNode) {
            $destinationFile = Locator::resolveNode($destinationFile);
        }

        if($destinationFile->isFolder()) {
            $destinationFile = new FileNode($destinationFile . $sourceFile->getName());
        }

        if ($destinationFile->isFile() && !$overwrite) {
            throw new ResourceExistsException(sprintf("$s exists and $overwrite is false", $destinationFile));
        }

        try {
            copy($sourceFile, $destinationFile);
        } catch (\Exception $e) {
            throw new IOException(sprintf("%s => %s, Can not copy, reason: %s", $sourceFile, $destinationFile, $e->getMessage()));
        }
    }
}