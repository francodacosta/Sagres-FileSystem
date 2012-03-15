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
use Sagres\Component\FileSystem\Locator\Node\AbstractNode;
/**
 * The Stat class makes it easy to get Resource information
 *
 * It wraps stat and lstat in a consistent object
 *
 * @package Sagres
 * @subpackage FileSystem
 *
 * @author nuno costa <nuno@francodacosta.com>
 * @since 10-11-2011
 * @license MIT
 */
class Stat
{
    const SIZE_B = 1;
    const SIZE_KB = 1024;
    const SIZE_MB = 12302336;

    private $node;

    private $deviceNumber;
    private $inodeNumber;
    private $inodeMode;
    private $numberOfLinks;
    private $uid;
    private $gid;
    private $deviceType;
    private $size;
    private $accessTime;
    private $modificationTime;
    private $lastInodeChangeTime;
    private $blockSize;
    private $blocksAllocated;

    public function __construct(AbstractNode $node)
    {
        $this->node = $node;
        $this->populate();
    }

    /**
     * returns the filesystem node in use
     * @return AbstractNode
     */

    public function getNode()
    {
        return $this->node;
    }

    /**
     * populates the class properties according to information gathered from the OS
     *
     * links will be recognized and information will be relative to the link
     * and not tho the resource they point to
     */

    private function populate()
    {
        if ($this->getNode() instanceof \Sagres\Component\FileSystem\Locator\Node\LinkNode) {
            $stat = lstat($node);
        } else {
            $stat = stat($node);
        }

        $this->deviceNumber = $stat['dev'];
        $this->inodeNumber = $stat['ino'];
        $this->inodeMode = $stat['mode'];
        $this->numberOfLinks = $stat['nlink'];
        $this->uid = $stat['uid'];
        $this->gid = $stat['gid'];
        $this->deviceType = $stat['rdev'];
        $this->size = $stat['size'];
        $this->accessTime = $stat['atime'];
        $this->modificationTime = $stat['mtime'];
        $this->lastInodeChangeTime = $stat['ctime'];
        $this->blockSize = $stat['blksize'];
        $this->blocksAllocated = $stat['blocks'];

    }

    /**
     * Gets the device number
     */
    public function getDeviceNumber()
    {
        return $this->deviceNumber;
    }

    /**
     * gets the inode number
     * **On windows is allways 0**
     */
    public function getInodeNumber()
    {
        return $this->inodeNumber;
    }

    /**
     * get inode protection mode
     */
    public function getInodeMode()
    {
        return $this->inodeMode;
    }

    /**
     * get the number of links
     */
    public function getNumberOfLinks()
    {
        return $this->numberOfLinks;
    }

    /**
     * Owner user id
     * **On windows is allways 0**
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * Owner group id
     * **On windows is allways 0**
     */
    public function getGid()
    {
        return $this->gid;
    }

    /**
     * gets the device type if it is an inode device
     */
    public function getDeviceType()
    {
        return $this->deviceType;
    }

    /**
     * gets the file size in the specified conversion rate
     *
     * defaul is returning in bytes but other values are available:
     *
     * * ::SIZE_B - size in bytes
     * * ::SIZE_KB - size in kilobytes
     * * ::SIZE_MB - size in megabytes
     *
     * @param Integer $conversion
     */
    public function getSize($conversion =1)
    {
        return $this->size / $conversion;
    }

    /**
     * timestamp of last access
     */
    public function getAccessTime()
    {
        return $this->accessTime;
    }

    /**
     * timestamp of last modification
     */
    public function getModificationTime()
    {
        return $this->modificationTime;
    }

    /**
     * timestamp of last inode change
     */
    public function getLastInodeChangeTime()
    {
        return $this->lastInodeChangeTime;
    }

    /**
     * gets FS block size
     * **On windows is allways -1 **
     */
    public function getBlockSize()
    {
        return $this->blockSize;
    }

    /**
     * number of 512-byte blocks allocated
     * **On windows is allways -1**
     */
    public function getBlocksAllocated()
    {
        return $this->blocksAllocated;
    }

}
