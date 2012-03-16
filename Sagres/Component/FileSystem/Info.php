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
class Info
{
    const SIZE_B = 1;
    const SIZE_KB = 1024;
    const SIZE_MB = 12302336;

    const PERMISSION_NUMBER = 0;
    const PERMISSION_HUMAN = 1;

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
        $node = $this->getNode();
        if(! $node->exists()) {
            throw new ResourceNotFoundException('Can not stat an non existent resource ' . $node);
        }

        clearstatcache();
        if ($node instanceof \Sagres\Component\FileSystem\Locator\Node\LinkNode) {
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
        clearstatcache();
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

    /**
     * gets the username of the resource owner
     * @return String|NULL
     */
    public function getOwnerUsername()
    {
       if (function_exists('posix_getpwuid')) {
            $info = @posix_getpwuid($this->getUid());
            return $info['name'];
        } else {
            throw new \RuntimeException('posix extension not installed');
        }
    }

    /**
     * gets the groupname of the resource
     * @return String|NULL
     */
    public function getGroupName()
    {
        if (function_exists('posix_getgrgid')) {
             $info = @posix_getgrgid($this->getGid());
             return $info['name'];
        }else {
            throw new \RuntimeException('posix extension not installed');
        }
    }

    /**
     * get resource permissions in Ocatal format (ex: 0777)
     *
     * @return String
     */
    public function getOctalPermissions(){
       return substr(sprintf('%o', fileperms($this->getNode())), -4);
    }

    /**
     * gets the resource permissions in a human readable way, unix style -rwxrwxrwx
     * @return string
     */
    public function getHumanReadablePermissions()
    {
        $perms = fileperms($this->getNode());

        // @codeCoverageIgnoreStart
        if (($perms & 0xC000) == 0xC000) {
            // Socket
            $info = 's';
        } elseif (($perms & 0xA000) == 0xA000) {
            // Symbolic Link
            $info = 'l';
        } elseif (($perms & 0x8000) == 0x8000) {
            // Regular
            $info = '-';
        } elseif (($perms & 0x6000) == 0x6000) {
            // Block special
            $info = 'b';
        } elseif (($perms & 0x4000) == 0x4000) {
            // Directory
            $info = 'd';
        } elseif (($perms & 0x2000) == 0x2000) {
            // Character special
            $info = 'c';
        } elseif (($perms & 0x1000) == 0x1000) {
            // FIFO pipe
            $info = 'p';
        } else {
            // Unknown
            $info = 'u';
        }
        // @codeCoverageIgnoreEnd

        // Owner
        $info .= (($perms & 0x0100) ? 'r' : '-');
        $info .= (($perms & 0x0080) ? 'w' : '-');
        $info .= (($perms & 0x0040) ?
                (($perms & 0x0800) ? 's' : 'x' ) :
                (($perms & 0x0800) ? 'S' : '-'));

        // Group
        $info .= (($perms & 0x0020) ? 'r' : '-');
        $info .= (($perms & 0x0010) ? 'w' : '-');
        $info .= (($perms & 0x0008) ?
                (($perms & 0x0400) ? 's' : 'x' ) :
                (($perms & 0x0400) ? 'S' : '-'));

        // World
        $info .= (($perms & 0x0004) ? 'r' : '-');
        $info .= (($perms & 0x0002) ? 'w' : '-');
        $info .= (($perms & 0x0001) ?
                (($perms & 0x0200) ? 't' : 'x' ) :
                (($perms & 0x0200) ? 'T' : '-'));

        return $info;
    }

    /**
     * gets the resource permissions according to the desired format
     *
     * see PERMISSION_* constants
     *
     * @param Integer $format
     * @throws UnexpectedValueException
     * @return string
     */
    public function getPermissions($format = 0)
    {
        switch($format) {
            case self::PERMISSION_HUMAN:
                return $this->getHumanReadablePermissions();
                break;

            case self::PERMISSION_NUMBER:
                return $this->getOctalPermissions();
                break;

            default:
                throw new \UnexpectedValueException('Invalid permissions format :' . $format);
                break;
        }
    }

}
