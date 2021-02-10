<?php
include_once("../includes/models/Model.inc.php");

// Since we can't instantiate the Model class (because it's abstract)
// We'll create a sub class that we CAN instantiate,
// then test the methods of the Model class
class TestModel extends Model{

	public $testProperty;

	function isValid(){
		// DO NOTHING
	}

}


$testResults = array();

// RUN THE TEST FUNCTIONS HERE

$testResults[] = "No tests yet, I tested most of the concrete methods in the sub classes";
echo(implode($testResults,"<br>"));



