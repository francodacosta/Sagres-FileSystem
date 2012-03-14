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
class LinkNode extends AbstractNode
{
    /**
     * {@inheritdoc}
     * @param String $path
     */
    public function __construct($path)
    {
        $this->setType(self::TYPE_LINK);
        parent::__construct($path);
    }
}