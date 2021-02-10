<?php
// we need the config file to get a connection to the database
//include_once("../includes/config.inc.php");
include_once("../includes/dataaccess/DataAccess.inc.php");

// create a database called test_users
$testDB = "test_db"; // put the name of your test db here
$testServer = "localhost";
$testLogin = "root";
$testPassword = ""; // set the password 
$link = mysqli_connect($testServer, $testLogin, $testPassword, $testDB);

if(!$link){
	die("Unable to connect to test db");
}

// Since we can't instantiate the DataAccess class (because it's abstract)
// We'll create a sub class that we CAN instantiate,
// then test the methods of the DataAccess class
class TestDataAccess extends DataAccess{

	function getById($id){ 
		// DO NOTHING
	}

	function getAll($args = []){ 
		// DO NOTHING
	}

	function insert($obj){ 
		// DO NOTHING
	}

	function update($obj){ 
		// DO NOTHING
	}

	function delete($id){ 
		// DO NOTHING
	}

	function convertModelToRow($model){
		// DO NOTHING
	}

	function convertRowToModel($row){
		// DO NOTHING
	}

	// ADD THIS FUNCTION SO WE CAN TEST THAT THE LINK IS WORKING
	function getLink(){
		return $this->link;
	}

}

// RUN THE TEST FUNCTIONS
$testResults = array();

testConstructor();
testSanitizeHtml();
testConvertDateForMySQL();


// wrap each test result message in a div
$testResults = array_map(function($str){
	return "<div>$str</div>";
}, $testResults);

echo(implode($testResults));



function testConstructor(){

	global $testResults, $link;
	$testResults[] = "<h3>TESTING constructor...</h3>";

	// TEST 1 - create an instance of the ConcactDataAccess class
	$da = new TestDataAccess($link);
	
	if($da){
		$testResults[] = "PASS - Created instance of TestDataAccess";
	}else{
		$testResults[] = "FAIL - DID NOT creat instance of TestDataAccess";
	}

	// TEST 2 - make sure the link param got set
	// Note that we created the getLink() method in our TestDataAccess
	// class for the sole purpose of testing the link
	$expectedLink = $da->getLink();
	if(get_class($expectedLink) === "mysqli"){
		$testResults[] = "PASS - Properly set link property";
	}else{
		$testResults[] = "FAIL - DID NOT properly set link property";
	}

	// TEST 3 - make sure it throws an error if the link is not valid
	try{
		$da = new TestDataAccess("blah");
		$testResults[] = "FAIL - DID NOT throw an error when invalid link param was used";
	}catch(Exception $e){
		//die($e->getMessage());
		$testResults[] = "PASS - Threw an error when invalid link param was used";
	}

	// NOTE: we could/should write another test to make sure that when an error is thrown
	// it has the proper error message in it:
	//		$e->getMessage() == "The link param is not a valid connection"
}

function testSanitizeHtml(){
	
	global $testResults, $link;
	$testResults[] = "<h3>TESTING sanitizeHtml()...</h3>";
	
	// We need an instance of a TestDataAccess object so that we can call the method we want to test
	$da = new TestDataAccess($link);

	// TEST 1 - Make sure it removes 'script' tags from the HTML string
	$dirtyHtml = "<h3><script>some script</sript></h3>";
	$expectedResult = "<h3>some script</h3>";
	$actuallResult = $da->sanitizeHtml($dirtyHtml);
	
	if($expectedResult == $actuallResult){
		$testResults[] = "PASS - Removed script tag from HTML string";
	}else{
		$testResults[] = "FAIL - DID NOT remove script tag from HTML string";
	}

	// TEST X - MORE TESTS TO DO...make sure it removes other tags and malicious attributes
}


function testConvertDateForMySQL(){
	global $testResults, $link;
	$testResults[] = "<h3>TESTING convertDateForMySQL()...</h3>";

	// We need an instance of a TestDataAccess object so that we can call the method we want to test
	$da = new TestDataAccess($link);

	// TEST 1 - Make sure it removes 'script' tags from the HTML string
	$stringToFormat = "2/1/2020";
	$expectedResult = "2020-02-01";
	$actuallResult = $da->convertDateForMySQL($stringToFormat);

	if($expectedResult == $actuallResult){
		$testResults[] = "PASS - Formatted 2/1/202 into 2020-02-01";
	}else{
		$testResults[] = "FAIL - DID NOT format 2/1/202 into 2020-02-01";
	}
}


?>