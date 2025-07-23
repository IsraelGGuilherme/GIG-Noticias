<?php

use Config\Paths;

    $paths = new Paths;

    $opts = array(
	// 'debug' => true,
	'roots' => array(
		// Items volume
		array(
			'driver'        => $paths->elFinderDirectory . '\php\LocalFileSystem',           // driver for accessing file system (REQUIRED)
			'path'          => '../files/',                 // path to files (REQUIRED)
			'URL'           => dirname($_SERVER['PHP_SELF']) . '/../files/', // URL to files (REQUIRED)
			'trashHash'     => 't1_Lw',                     // elFinder's hash of trash folder
			'winHashFix'    => DIRECTORY_SEPARATOR !== '/', // to make hash same to Linux one on windows too
			'uploadDeny'    => array('all'),                // All Mimetypes not allowed to upload
			'uploadAllow'   => array('image/x-ms-bmp', 'image/gif', 'image/jpeg', 'image/png', 'image/x-icon', 'text/plain'), // Mimetype `image` and `text/plain` allowed to upload
			'uploadOrder'   => array('deny', 'allow'),      // allowed Mimetype `image` and `text/plain` only
			'accessControl' => 'access'                     // disable and hide dot starting files (OPTIONAL)
		),
		// Trash volume
		array(
			'id'            => '1',
			'driver'        => 'Trash',
			'path'          => '../files/.trash/',
			'tmbURL'        => dirname($_SERVER['PHP_SELF']) . '/../files/.trash/.tmb/',
			'winHashFix'    => DIRECTORY_SEPARATOR !== '/', // to make hash same to Linux one on windows too
			'uploadDeny'    => array('all'),                // Recomend the same settings as the original volume that uses the trash
			'uploadAllow'   => array('image/x-ms-bmp', 'image/gif', 'image/jpeg', 'image/png', 'image/x-icon', 'text/plain'), // Same as above
			'uploadOrder'   => array('deny', 'allow'),      // Same as above
			'accessControl' => 'access',                    // Same as above
		),
	)
);

// run elFinder
$connector = new elFinderConnector(new elFinder($opts));
$connector->run();

?>
