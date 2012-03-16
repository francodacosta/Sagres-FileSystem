<?php
// Exceptions
require_once __DIR__ . '/../Sagres/Component/FileSystem/Exception/ResourceNotFoundException.php';

// Nodes
require_once __DIR__ . '/../Sagres/Component/FileSystem/Locator/Node/NodeInterface.php';
require_once __DIR__ . '/../Sagres/Component/FileSystem/Locator/Node/AbstractNode.php';
require_once __DIR__ . '/../Sagres/Component/FileSystem/Locator/Node/FileNode.php';
require_once __DIR__ . '/../Sagres/Component/FileSystem/Locator/Node/FolderNode.php';
require_once __DIR__ . '/../Sagres/Component/FileSystem/Locator/Node/LinkNode.php';

// classes
require_once __DIR__ . '/../Sagres/Component/FileSystem/Locator.php';
require_once __DIR__ . '/../Sagres/Component/FileSystem/Info.php';



/**
 * create needed fixtures
 */

@touch(__DIR__ . '/fixtures/read.write.file');
@chmod(__DIR__ . '/fixtures/read.write.file', 0777);
@unlink(__DIR__ . '/fixtures/linked.file');
@symlink(__DIR__ . '/fixtures/read.write.file',__DIR__ . '/fixtures/linked.file' );
@mkdir (__DIR__ . '/fixtures/FileSystem');