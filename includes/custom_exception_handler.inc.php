<?php
/*
EXCEPTION HANDLING
*/

set_exception_handler("myExceptionHandler");

function myExceptionHandler($exception) {

	$str = "THIS IS OUR CUSTOM EXCEPTION HANDLER<br>";
	$str .= $exception->getMessage();

    if(DEBUG_MODE){
		var_dump($exception);
	}else{
		//How to handle exceptions???
		
		// You might want to send all the super globals with the error message 
		$str .= print_r($exception, true);
		$str .= print_r($_POST, true);
		$str .= print_r($_GET, true);
		$str .= print_r($_SERVER, true);
		$str .= print_r($_FILES, true);
		$str .= print_r($_COOKIE, true);
		$str .= print_r($_SESSION, true);
		$str .= print_r($_REQUEST, true);
		$str .= print_r($_ENV, true);
		
		//send email to web admin
		mail(SITE_ADMIN_EMAIL, SITE_DOMAIN . " - EXCEPTION", $str);
		die("We're sorry, there was an error and we have been notified of it (TODO: redirect to a nice looking 'sorry' page)");
	}
}

