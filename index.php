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

// Our home page (cached for 60 seconds)
$main->route('GET /','Flickr->home', 60);

// Minify CSS; and cache page for 10 minutes
$main->route('GET /min','Flickr->minified',600);

// Our handle ajax requests (cached for 60 seconds)
$main->route('GET /ajax','Flickr->ajax', 60);

// Execute application
$main->run();
?>