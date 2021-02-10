<?php
include_once("../includes/models/Role.inc.php");


// we'll use these options to create valid Role in our tests
$options = array(
	'id' => 1,
	'name' => "Test Role",
	'description' => "This is a role for testing"
);

// This array will store the test results
$testResults = array();

// run the test functions
testConstructor();
testIsValid();
testToJSON();
testToCSV();
testToXML();
testToArray();

// wrap each test result message in a div
$testResults = array_map(function($str){
	return "<div>$str</div>";
}, $testResults);

echo(implode($testResults));



function testConstructor(){
	global $testResults, $options;
	$testResults[] = "<h2>Testing constructor...</h2>";

	// TEST - Make sure we can create a Contact object
	$r = new Role();
	
	if($r){
		$testResults[] = "PASS - Created instance of Contact model object";
	}else{
		$testResults[] = "FAIL - DID NOT creat instance of a Contact model object";
	}

	// TEST - Make sure each property gets set correctly
	$r = new Role($options);

	$testResults[] = "<h4>Validate Name:</h4>";
	if($r->name == "Test Role"){
		$testResults[] = "PASS - Set Name properly";
	}else{
		$testResults[] = "FAIL - DID NOT set Name properly";
	}

	
	// TODO: write test to make sure that all the other properties 
	// are set properly
	$testResults[] = "<h4>Validate ID:</h4>";
	$testResults[] = "<h4>Validate Description:</h4>";

}


function testIsValid(){
	global $testResults, $options;
	$testResults[] = "<h2>Testing isValid()...</h2>";

	$testResults[] = "<h4>Validate ID:</h4>";
	$r = new Role($options);
	$r->id = "";

	if($r->isValid() === false){
		$testResults[] = "PASS - isValid() returns false when ID is empty";
	}else{
		$testResults[] = "FAIL - isValid() DOES NOT return false when ID is empty";
	}

	if(isset($r->validationErrors['id'])){
		$testResults[] = "PASS - validationErrors includes key for ID";
	}else{
		$testResults[] = "FAIL - validationErrors does NOT include key for ID";
	}


	$testResults[] = "<h4>Validate Name:</h4>";
	$r = new Role($options);
	$r->name = "";
	
	if($r->isValid() === false){
		$testResults[] = "PASS - isValid() returns false when Name is empty";
	}else{
		$testResults[] = "FAIL - isValid() DOES NOT return false when Name is empty";
	}

	if(isset($r->validationErrors['name'])){
		$testResults[] = "PASS - validationErrors includes key for Name";
	}else{
		$testResults[] = "FAIL - validationErrors does NOT include key for Name";
	}

	$r->name = "xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx";
	if($r->isValid() === false){
		$testResults[] = "PASS - isValid() returns false when Name is too long";
	}else{
		$testResults[] = "FAIL - isValid() DOES NOT return false when Name is too long";
	}

	if(isset($r->validationErrors['name'])){
		$testResults[] = "PASS - validationErrors includes key for Name when Name is too long";
	}else{
		$testResults[] = "FAIL - validationErrors does NOT include key for Name when Name is too long";
	}

	
	$testResults[] = "<h4>Validate Description:</h4>";
	$r = new Role($options);
	$r->description = "";
	
	if($r->isValid() === false){
		$testResults[] = "PASS - isValid() returns false when Description is empty";
	}else{
		$testResults[] = "FAIL - isValid() DOES NOT return false when Description is empty";
	}

	if(isset($r->validationErrors['description'])){
		$testResults[] = "PASS - validationErrors includes key for Description";
	}else{
		$testResults[] = "FAIL - validationErrors does NOT include key for Description";
	}

	// the description must be less than 200 characters
	$r->description = "xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
					  xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
					  xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
					  xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
					  xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx";
	
	if($r->isValid() === false){
		$testResults[] = "PASS - isValid() returns false when Description is too long";
	}else{
		$testResults[] = "FAIL - isValid() DOES NOT return false when Description is too long";
	}

	if(isset($r->validationErrors['name'])){
		$testResults[] = "PASS - validationErrors includes key for Description when Description is too long";
	}else{
		$testResults[] = "FAIL - validationErrors does NOT include key for Description when Description is too long";
	}
}


function testToJSON(){

	global $testResults, $options;
	$testResults[] = "<h2>Testing toJSON()...</h2>";

	$r = new Role($options);

	$expectedResult = '{"id":1,"name":"Test Role","description":"This is a role for testing"}';
	$actualResult = $r->toJSON();

	if($expectedResult == $actualResult){
		$testResults[] = "PASS - converted to JSON properly";
	}else{
		$testResults[] = "FAIL - DID NOT convert to JSON properly";
	}
}


function testToCSV(){

	global $testResults, $options;
	$testResults[] = "<h2>Testing toCSV()...</h2>";

	$r = new Role($options);

	$expectedResult = "1,Test Role,This is a role for testing";
	$actualResult = $r->toCSV();

	if($expectedResult == $actualResult){
		$testResults[] = "PASS - converted to CSV properly";
	}else{
		$testResults[] = "FAIL - DID NOT convert to CSV properly";
	}
	

}


function testToXML(){

	global $testResults, $options;
	$testResults[] = "<h2>Testing toXML()...</h2>";

	$r = new Role($options);
	
	$expectedResult = "<role><id>1</id><name>Test Role</name><description>This is a role for testing</description></role>";
	$actualResult = $r->toXML();

	if($expectedResult == $actualResult){
		$testResults[] = "PASS - converted to XML properly";
	}else{
		$testResults[] = "FAIL - DID NOT convert to XML properly";
	}
	

}


function testToArray(){
	global $testResults, $options;
	$testResults[] = "<h2>Testing toArray()...</h2>";

	$r = new Role($options);
	$diff = array_diff_assoc($r->toArray(), $options);
	// array_diff_assoc() returns the differences between two arrays
	// it it's empty, then the two arrays have the same keys and values
	if(empty($diff)){
		$testResults[] = "PASS - converted to array properly";
	}else{
		$testResults[] = "FAIL - DID NOT convert to array properly";
	}
}
