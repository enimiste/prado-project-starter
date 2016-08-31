<?php

$frameworkPath = __DIR__ . '/vendor/pradosoft/prado/framework/prado.php';

// The following directory checks may be removed if performance is required
$basePath    = dirname( __FILE__ );
$assetsPath  = $basePath . '/assets';
$runtimePath = $basePath . '/protected/runtime';
$codePath    = $basePath . '/protected/Code';

if ( ! is_file( $frameworkPath ) ) {
	die( "Unable to find prado framework path $frameworkPath." );
}
if ( ! is_writable( $assetsPath ) ) {
	die( "Please make sure that the directory $assetsPath is writable by Web server process." );
}
if ( ! is_writable( $runtimePath ) ) {
	die( "Please make sure that the directory $runtimePath is writable by Web server process." );
}


require_once( $frameworkPath );
require_once __DIR__ . '/vendor/autoload.php';
require_once $codePath . '/NApplication.php';

$application = new NApplication();

$application->run();
