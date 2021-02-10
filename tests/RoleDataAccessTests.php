<?php
include_once("../includes/dataaccess/RoleDataAccess.inc.php");
include_once("../includes/models/Role.inc.php");

// IMPORTANT WARNING: YOU SHOULD MAKE A BACKUP OF YOUR DATABASE BEFORE DOING THIS

// YOU MAKE HAVE TO DROP SOME TABLES BEFORE RUNNING THE NEXT FEW QUERIES DEPENDING ON YOUR FK RELATIONSHIPS
// YOU ALSO MAY NOT WANT TO SET UP FOREIGN KEYS IN YOUR TEST DB
// (note how I commented the FK info out below)

// You'll need to create a database called test_roles
$testDB = "test_roles"; // put the name of your test db here
$testServer = "localhost";
$testLogin = "root";
$testPassword = "test"; // set the password 
$link = mysqli_connect($testServer, $testLogin, $testPassword, $testDB);

if(!$link){
	die("Unable to connect to test db");
}

$dropUserRolesTable = "DROP TABLE IF EXISTS user_roles;";

$createUserRolesTable = "
	CREATE TABLE `user_roles` (
	  `user_role_id` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
	  `user_role_name` varchar(30) NOT NULL,
	  `user_role_desc` varchar(200) NOT NULL
	) ENGINE=InnoDB DEFAULT CHARSET=latin1;";

$populateUserRolesTable = "
	INSERT INTO `user_roles` (`user_role_id`, `user_role_name`, `user_role_desc`) VALUES
	(1, 'Standard User', 'Normal user with no special permissions'),
	(2, 'Admin', 'Extra permissions');";

mysqli_query($link, $dropUserRolesTable);
mysqli_query($link, $createUserRolesTable);
mysqli_query($link, $populateUserRolesTable);
//die();

$testResults = array();

// You'll have to run all these tests for each of your data access classes
testConstructor();
testConvertModelToRow();
testConvertRowToModel();
testGetAll();
testGetById();
testInsert(); 
testUpdate(); 
testDelete(); 

// wrap each test result message in a div
$testResults = array_map(function($str){
	return "<div>$str</div>";
}, $testResults);

echo(implode($testResults));



function testConstructor(){

	global $testResults, $link;
	$testResults[] = "<h3>TESTING constructor...</h3>";

	// TEST 1 - create an instance of the ConcactDataAccess class
	$da = new RoleDataAccess($link);
	
	if($da){
		$testResults[] = "PASS - Created instance of RoleDataAccess";
	}else{
		$testResults[] = "FAIL - DID NOT creat instance of RoleDataAccess";
	}
}

function testConvertModelToRow(){

	global $testResults, $link;

	$testResults[] = "<h3>TESTING convertModelToRow()...</h3>";

	$da = new RoleDataAccess($link);

	$options = array(
		'id' => 1,
		'name' => "Test Role",
		'description' => "This is a test role"
	);

	$r = new Role($options);

	$expectedResult = array(
		'user_role_id' => 1,
		'user_role_name' => "Test Role",
		'user_role_desc' => "This is a test role"
	);
	
	$actualResult = $da->convertModelToRow($r);

	if(empty(array_diff_assoc($expectedResult, $actualResult))){
		$testResults[] = "PASS - Converted Role to proper assoc array";
	}else{
		$testResults[] = "FAIL - DID NOT convert Role to proper assoc array";
	}
}


function testConvertRowToModel(){
	global $testResults, $link;

	$testResults[] = "<h3>TESTING convertRowToModel()...</h3>";

	$da = new RoleDataAccess($link);

	$row = array(
		'user_role_id' => 1,
		'user_role_name' => "Test Role",
		'user_role_desc' => "This is a test role"
	);
	
	$actualRole = $da->convertRowToModel($row);
	
	$expectedRole = new Role([
		'id' => 1,
		'name' => "Test Role",
		'description' => "This is a test role"
	]);

	if($actualRole->equals($expectedRole)){
		$testResults[] = "PASS - Converted row (assoc array) to Role";
	}else{
		$testResults[] = "FAIL - DID NOT Convert row (assoc array) to Role";
	}
}


function testGetAll(){
	global $testResults, $link;
	$testResults[] = "<h3>TESTING getAll()...</h3>";

	$da = new RoleDataAccess($link);
	$roles = $da->getAll();
	$testResults[] = print_r($roles, true);
}


function testGetById(){
	global $testResults, $link;
	$testResults[] = "<h3>TESTING getById()...</h3>";

	// We need an instance of a UserDataAccess object so that we can call the method we want to test
	$da = new RoleDataAccess($link);
	$role = $da->getById(1);
	$testResults[] = print_r($role, true);
}

function testInsert(){
	global $testResults, $link;
	$testResults[] = "<h3>TESTING insert()...</h3>";

	$da = new RoleDataAccess($link);

	$options = array(
		'id' => 1,
		'name' => "Test Role",
		'description' => "This is a test role"
	);

	$r = new Role($options);

	// The insert method should throw an error if you try to 
	// insert a user_email that already exists in the database
	try{
		$newRole = $da->insert($r);
		$testResults[] = print_r($newRole, true);
	}catch(Exception $e){
		$testResults[] = $e->getMessage() . " EMAIL IS NOT UNIQUE???";
	}
	
}

function testUpdate(){

	global $testResults, $link;
	$testResults[] = "<h3>TESTING update()...</h3>";

	$da = new RoleDataAccess($link);

	$options = array(
		'id' => 1,
		'name' => "Test Role",
		'description' => "This is a test role"
	);

	$r = new Role($options);

	// NOTE that you can't update a user's email if another user already has that email address
	if($da->update($r)){
		$testResults[] = "PASS - updated Role";
		// You shouldn't call other methods in the class when testing a method
		$testResults[] = print_r($da->getById($r->id), true);
	}else{
		$testResults[] = "Failed - Did not update Role when it should have";
	}
}

function testDelete(){
	// Note sure how we want to handle this
	// If you allow deletes then it can get messy with FK relationships
	// It might be better to set active = no
}

?>