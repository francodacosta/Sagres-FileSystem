<?php
namespace Sagres\Component\FileSystem\Locator\Node;

Interface NodeInterface
{
    public function __construct($path);
    public function isReadable();
    public function isWritable();
    public function exists();
    public function getName();
    public function getPath();
    public function getType();
}