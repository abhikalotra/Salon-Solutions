<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Debug\Debug;

// If you don't want to setup permissions the proper way, just uncomment the following PHP line
// read http://symfony.com/doc/current/book/installation.html#configuration-and-setup for more information
//umask(0000);

// This check prevents access to debug front controllers that are deployed by accident to production servers.
// Feel free to remove this, extend it, or make something more sophisticated.
/*if (isset($_SERVER['HTTP_CLIENT_IP'])
    || isset($_SERVER['HTTP_X_FORWARDED_FOR'])
    || !in_array(@$_SERVER['REMOTE_ADDR'], array('127.0.0.1', 'fe80::1', '::1'))
) {
    header('HTTP/1.0 403 Forbidden');
    exit('You are not allowed to access this file. Check '.basename(__FILE__).' for more information.');
}*/

//echo "<PRE>";print_r($_SERVER);die;

$arrServerName = explode('.salonsolutions.ca', $_SERVER['SERVER_NAME']);
//echo $_SERVER['REQUEST_URI'];
if( $arrServerName && array_key_exists(0, $arrServerName) && $arrServerName[0] != 'tanonline' && ($_SERVER['REQUEST_URI'] == '' || $_SERVER['REQUEST_URI'] == '/') )
{
	$newUrl = 'Location: http://'.$arrServerName[0].'.salonsolutions.ca/salon';
	//echo $newUrl;
	header($newUrl);
}
else if( $arrServerName && array_key_exists(0, $arrServerName) && $arrServerName[0] == 'tanonline' && ($_SERVER['REQUEST_URI'] == '' || $_SERVER['REQUEST_URI'] == '/') )
{
	$newUrl = 'Location: http://salonsolutions.ca';
	//echo $newUrl;
	header($newUrl);
}
//die;
$loader = require_once __DIR__.'/app/bootstrap.php.cache';
Debug::enable();

require_once __DIR__.'/app/AppKernel.php';

$kernel = new AppKernel('dev', true);
$kernel->loadClassCache();
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
