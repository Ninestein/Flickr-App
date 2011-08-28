<?php
/**
Load the base file
**/
$main		= require_once __DIR__.'/lib/base.php';

//Path to the app files
$main->set('AUTOLOAD','app/');

//Turn on the cache
F3::set('CACHE',TRUE);

/**
	DEBUG LEVEL
	0 = no errors shown
	3 = all errors shown
**/
$main->set('DEBUG',3);

// Path to our templates
$main->set('GUI','gui/');

// Define application globals
$main->set('site','Pollenizer Flickr Application Test');

/**
	Define our routes (HTTP method and URI) and route handlers.
**/

// Our home page (No cache)
$main->route('GET /','Flickr->show');

//Run the test
$suite  = new PHPUnit_TestSuite("Flickr_unittest");
$result = PHPUnit::run($suite);

echo $result -> toString();
?>