<?php
// Exceptions
require_once __DIR__ . '/../Sagres/Component/FileSystem/Exception/ResourceNotFoundException.php';
require_once __DIR__ . '/../Sagres/Component/FileSystem/Exception/ResourceExistsException.php';
require_once __DIR__ . '/../Sagres/Component/FileSystem/Exception/IOException.php';

// Nodes
require_once __DIR__ . '/../Sagres/Component/FileSystem/Node.php';

// classes
require_once __DIR__ . '/../Sagres/Component/FileSystem/Locator.php';
require_once __DIR__ . '/../Sagres/Component/FileSystem/Copy.php';



/**
 * create needed fixtures
 */

@touch(__DIR__ . '/fixtures/read.write.file');
@chmod(__DIR__ . '/fixtures/read.write.file', 0777);
@unlink(__DIR__ . '/fixtures/linked.file');
@symlink(__DIR__ . '/fixtures/read.write.file',__DIR__ . '/fixtures/linked.file' );
@mkdir (__DIR__ . '/fixtures/FileSystem');