<?php
// this is the main configuration file for the website

// set up custom error and exception handling
require_once('custom_error_handler.inc.php');
require_once('custom_exception_handler.inc.php');

// detect which environment the code is running in
if($_SERVER['SERVER_NAME'] == "localhost"){
	// DEV ENVIRONMENT SETTINGS
	define("DEBUG_MODE", true);
	define("DB_HOST", "localhost");
	define("DB_USER", "root");
	define("DB_PASSWORD", "");
	define("DB_NAME", "user_manager");
	define("SITE_ADMIN_EMAIL", "PUT EMAIL ADDRESS HERE");
	define("SITE_DOMAIN", $_SERVER['SERVER_NAME']);
	define("PROJECT_DIR", "/api/");
	define("UPLOAD_DIR", $_SERVER['DOCUMENT_ROOT'] . PROJECT_DIR . "uploads/");
}else{
	// PRODUCTION SETTINGS
	define("DEBUG_MODE", false); 
	// you may want to set DEBUG_MODE to true when you 
	// are first setting up your live site, but once you get
	// everything working you'd want it off.
	define("DB_HOST", "?????");
	define("DB_USER", "?????");
	define("DB_PASSWORD", "?????");
	define("DB_NAME", "?????");
	define("SITE_ADMIN_EMAIL", "?????");
	define("SITE_DOMAIN", $_SERVER['SERVER_NAME']);
	define("PROJECT_DIR", "?????");
	define("UPLOAD_DIR", $_SERVER['DOCUMENT_ROOT'] . PROJECT_DIR . "uploads/");
}

// if we are in debug mode then display all errors and set error reporting to all 
if(DEBUG_MODE){
	// turn on error messages
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
}

// the $link variable will be our connection to the database
$link = null;

function get_link(){

	global $link;
		
	if($link == null){
		
		$link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

		if(!$link){
			throw new Exception(mysqli_connect_error()); 
		}
	}

	return $link;
}
