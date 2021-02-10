<?php
include_once("../includes/models/User.inc.php");


// we'll use these options to create valid User in our tests
$options = array(
	'id' => 1,
	'firstName' => "Bob",
	'lastName' => "Smith",
	'email' => "bob@smith.com",
	'roleId' => "1",
	'password' => "opensesame",
	'salt' => "xxx",
	'active' => "yes"
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
	$u = new User();
	
	if($u){
		$testResults[] = "PASS - Created instance of Contact model object";
	}else{
		$testResults[] = "FAIL - DID NOT creat instance of a Contact model object";
	}

	// TEST - Make sure the firstName property gets set correctly
	$u = new User($options);

	if($u->firstName == "Bob"){
		$testResults[] = "PASS - Set firstName properly";
	}else{
		$testResults[] = "FAIL - DID NOT set firstName properly";
	}

	
	// TODO: write test to make sure that all the other properties 
	// are set properly
	$testResults[] = "<h4>TODO: Test all the other properties to make sure they are getting set properly</h4>";

}


function testIsValid(){
	global $testResults, $options;
	$testResults[] = "<h2>Testing isValid()...</h2>";

	$u = new User($options);
	$u->firstName = "";

	$testResults[] = "<h4>Validate firstName:</h4>";
	if($u->isValid() === false){
		$testResults[] = "PASS - isValid() returns false when firstName is empty";
	}else{
		$testResults[] = "FAIL - isValid() DOES NOT return false when firstName is empty";
	}

	if(isset($u->validationErrors['firstName'])){
		$testResults[] = "PASS - validationErrors includes key for firstName";
	}else{
		$testResults[] = "FAIL - validationErrors does NOT include key for firstName";
	}

	$u->firstName = "xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx";
	if($u->isValid() === false){
		$testResults[] = "PASS - isValid() returns false when firstName is too long";
	}else{
		$testResults[] = "FAIL - isValid() DOES NOT return false when firstName is too long";
	}

	if(isset($u->validationErrors['firstName'])){
		$testResults[] = "PASS - validationErrors includes key for firstName";
	}else{
		$testResults[] = "FAIL - validationErrors does NOT include key for firstName";
	}

	// TODO: test all the other properties that get validated
	$testResults[] = "<h4>TODO: Test all the other properties to make sure they are getting validated properly</h4>";
}


function testToJSON(){

	global $testResults, $options;
	$testResults[] = "<h2>Testing toJSON()...</h2>";

	$u = new User($options);

	$expectedResult = '{"id":1,"firstName":"Bob","lastName":"Smith","email":"bob@smith.com","roleId":"1","password":"opensesame","salt":"xxx","active":"yes"}';
	$actualResult = $u->toJSON();

	if($expectedResult == $actualResult){
		$testResults[] = "PASS - converted to JSON properly";
	}else{
		$testResults[] = "FAIL - DID NOT convert to JSON properly";
	}
}


function testToCSV(){

	global $testResults, $options;
	$testResults[] = "<h2>Testing toCSV()...</h2>";

	$u = new User($options);

	$expectedResult = "1,Bob,Smith,bob@smith.com,1,opensesame,xxx,yes";
	$actualResult = $u->toCSV();

	if($expectedResult == $actualResult){
		$testResults[] = "PASS - converted to CSV properly";
	}else{
		$testResults[] = "FAIL - DID NOT convert to CSV properly";
	}
	

}


function testToXML(){

	global $testResults, $options;
	$testResults[] = "<h2>Testing toXML()...</h2>";

	$u = new User($options);
	
	$expectedResult = "<user><id>1</id><firstName>Bob</firstName><lastName>Smith</lastName><email>bob@smith.com</email><roleId>1</roleId><password>opensesame</password><salt>xxx</salt><active>yes</active></user>";
	$actualResult = $u->toXML();

	if($expectedResult == $actualResult){
		$testResults[] = "PASS - converted to XML properly";
	}else{
		$testResults[] = "FAIL - DID NOT convert to XML properly";
	}
	

}


function testToArray(){
	global $testResults, $options;
	$testResults[] = "<h2>Testing toArray()...</h2>";

	$u = new User($options);
	$diff = array_diff_assoc($u->toArray(), $options);
	// array_diff_assoc() returns the differences between two arrays
	// it it's empty, then the two arrays have the same keys and values
	if(empty($diff)){
		$testResults[] = "PASS - converted to array properly";
	}else{
		$testResults[] = "FAIL - DID NOT convert to array properly";
	}
}
