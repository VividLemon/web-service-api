<?php
/*
ERROR HANDLING
*/

set_error_handler("myErrorHandler");

function myErrorHandler($errno, $errstr, $errfile, $errline){

	$str = "THIS IS OUR CUSTOM ERROR HANDLER<br>";
	$str .= "ERROR NUMBER: " . $errno . "<br>ERROR MSG: " . $errstr . "<br>FILE: " . $errfile . "<br>LINE NUMBER: " . $errline . "<br><br>";
	
	if(DEBUG_MODE){
		echo($str);
	}else{
		// You might want to send all the super globals with the error message 
		$str .= print_r($_POST, true);
		$str .= print_r($_GET, true);
		$str .= print_r($_SERVER, true);
		$str .= print_r($_FILES, true);
		$str .= print_r($_COOKIE, true);
		$str .= print_r($_SESSION, true);
		$str .= print_r($_REQUEST, true);
		$str .= print_r($_ENV, true);
		
		//send email to web admin
		mail(SITE_ADMIN_EMAIL, SITE_DOMAIN . " - ERROR", $str);
		
		//TODO: echo a nice message to the user, or redirect to an error page
		die("We are sorry, there has been an error. But we have been notified and are working in it.");
	}
}




