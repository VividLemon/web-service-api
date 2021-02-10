<?php

/*
THIS PAGE SHOULD ULTIMATELY BE THE DOCUMENTATION FOR YOUR WEB SERVICE API
*/
session_start();

echo(get_request_data());
die();


/**
* The get_request_data() function gets info about an incoming HTTP Request.
* Here's the info that it gathers:
*
* $_SERVER['REQUEST_METHOD'];			Tells you the method/type of request (GET, POST, etc.)
* $_SERVER['REQUEST_URI'];				Tells you the URL being requested (include the query string)
* file_get_contents('php://input');		This function returns the body of the request
* getallheaders();						This function returns an array of all headers in the request
* $_GET									This array inlcudes the URL parameters in the query string
* $_POST								This array includes all the user input entered into a form
*
*/

function get_request_data(){

	$strArray = [];

	$strArray[] = "REQUEST METHOD: {$_SERVER['REQUEST_METHOD']}";
	$strArray[] = "REQUEST URI: {$_SERVER['REQUEST_URI']}";

	$strArray[] = "REQUEST HEADERS...";
	$request_headers = getallheaders();
	foreach ($request_headers as $key => $value) {
		$strArray[] = "$key : $value";
	}

	if(!empty($_GET)){
		$strArray[] = "QUERY STRING PARAMS...";
		foreach ($_GET as $key => $value) {
			$strArray[] = "$key : $value";
		}
	}

	if(!empty($_POST)){
		$strArray[] = "POST PARAMS...";
		foreach ($_POST as $key => $value) {
			$strArray[] = "$key : $value";
		}
	}

	if(!empty($_COOKIE)){
		$strArray[] = "COOKIES...";
		foreach ($_COOKIE as $key => $value) {
			$strArray[] = "$key : $value";
		}
	}

	$request_body = file_get_contents('php://input');
	if(!empty($request_body)){
		$strArray[] = "REQUEST BODY...";
		$strArray[] = $request_body;
	}

	return implode($strArray, "\n");

}


?>