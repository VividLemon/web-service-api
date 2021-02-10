<?php
include_once("../includes/dataaccess/UserDataAccess.inc.php");
include_once("../includes/models/User.inc.php");

// IMPORTANT WARNING: YOU SHOULD MAKE A BACKUP OF YOUR DATABASE BEFORE DOING THIS

// YOU MAKE HAVE TO DROP SOME TABLES BEFORE RUNNING THE NEXT FEW QUERIES DEPENDING ON YOUR FK RELATIONSHIPS
// YOU ALSO MAY NOT WANT TO SET UP FOREIGN KEYS IN YOUR TEST DB
// (note how I commented the FK info out below)

// create a database called test_users
$testDB = "test_db"; // put the name of your test db here
$testServer = "localhost";
$testLogin = "root";
$testPassword = "test"; // set the password 
$link = mysqli_connect($testServer, $testLogin, $testPassword, $testDB);

if(!$link){
	die("Unable to connect to test db");
}


$dropUsersTable = "DROP TABLE IF EXISTS users;";
$dropUserRolesTable = "DROP TABLE IF EXISTS user_roles;";

// Note that I changed the length of the password from 32 to 255
$createUsersTable = "
	CREATE TABLE IF NOT EXISTS users (
	  user_id int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
	  user_first_name varchar(30) NOT NULL,
	  user_last_name varchar(30) NOT NULL,
	  user_email varchar(255) NOT NULL UNIQUE,
	  user_password char(255) NOT NULL,
	  user_salt char(32) NOT NULL,
	  user_role INT NOT NULL DEFAULT '1',
	  user_active enum('yes','no') NOT NULL DEFAULT 'yes'
	  # , FOREIGN KEY (user_role) REFERENCES user_roles(user_role_id)
	);";

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

$populateUsersTable = "
	INSERT INTO users (user_first_name,user_last_name, user_email, user_password, user_salt, user_role, user_active) VALUES 
	('John', 'Doe','john@doe.com', 'opensesame', 'xxx', '1', 'yes'),
	('Jane', 'Doe','jane@doe.com', 'letmein', 'xxx', '2', 'yes'),
	('Bob', 'Smith','bob@smith.com', 'test', 'xxx', '2', 'yes');";	


// Note: you have to drop the user_roles table before the users table
// because of the FK relationship
// You also have to create and populate the user_roles table before the users table
mysqli_query($link, $dropUserRolesTable);
mysqli_query($link, $dropUsersTable);
mysqli_query($link, $createUserRolesTable);
mysqli_query($link, $populateUserRolesTable);
mysqli_query($link, $createUsersTable);
mysqli_query($link, $populateUsersTable);

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

// These tests applies only to the UserDataAccess
//testSaltAndHashPassword(); // We'll talk about this one later
//testLogin();

	// wrap each test result message in a div
	$testResults = array_map(function($str){
		return "<div>$str</div>";
	}, $testResults);

	echo(implode($testResults));



function testConstructor(){

	global $testResults, $link;
	$testResults[] = "<h3>TESTING constructor...</h3>";

	// TEST 1 - create an instance of the ConcactDataAccess class
	$da = new UserDataAccess($link);
	
	if($da){
		$testResults[] = "PASS - Created instance of UserDataAccess";
	}else{
		$testResults[] = "FAIL - DID NOT creat instance of UserDataAccess";
	}
}

function testConvertModelToRow(){
	global $testResults, $link;

	$testResults[] = "<h3>TESTING convertModelToRow()...</h3>";

	$da = new UserDataAccess($link);

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

	$u = new User($options);

	$expectedResult = array(
		'user_id' => 1,
		'user_first_name' => "Bob",
		'user_last_name' => "Smith",
		'user_email' => "bob@smith.com",
		'user_role' => "1",
		'user_password' => "opensesame",
		'user_salt' => "xxx",
		'user_active' => "yes"
	);
	
	$actualResult = $da->convertModelToRow($u);

	if(empty(array_diff_assoc($expectedResult, $actualResult))){
		$testResults[] = "PASS - Converted User to proper assoc array";
	}else{
		$testResults[] = "FAIL - DID NOT convert User to proper assoc array";
	}
}


function testConvertRowToModel(){
	global $testResults, $link;

	$testResults[] = "<h3>TESTING convertRowToModel()...</h3>";

	$da = new UserDataAccess($link);

	$row = array(
		'user_id' => 1,
		'user_first_name' => "Bob",
		'user_last_name' => "Smith",
		'user_email' => "bob@smith.com",
		'user_role' => "1",
		'user_password' => "opensesame",
		'user_salt' => "xxx",
		'user_active' => "yes"
	);
	
	$actualUser = $da->convertRowToModel($row);
	
	$expectedUser = new User([
		'id' => 1,
		'firstName' => "Bob",
		'lastName' => "Smith",
		'email' => "bob@smith.com",
		'roleId' => "1",
		'password' => "opensesame",
		'salt' => "xxx",
		'active' => "yes"
	]);

	if($actualUser->equals($expectedUser)){
		$testResults[] = "PASS - Converted row (assoc array) to User";
	}else{
		$testResults[] = "FAIL - DID NOT Convert row (assoc array) to User";
	}
}


function testGetAll(){
	global $testResults, $link;
	$testResults[] = "<h3>TESTING getAll()...</h3>";

	$da = new UserDataAccess($link);
	$users = $da->getAll();
	$testResults[] = print_r($users, true);
}


function testGetById(){
	global $testResults, $link;
	$testResults[] = "<h3>TESTING getById()...</h3>";

	// We need an instance of a UserDataAccess object so that we can call the method we want to test
	$da = new UserDataAccess($link);
	$user = $da->getById(1);
	$testResults[] = print_r($user, true);
}

function testInsert(){
	global $testResults, $link;
	$testResults[] = "<h3>TESTING insert()...</h3>";

	$da = new UserDataAccess($link);

	$options = array(
		'id' => 1,
		'firstName' => "Bob",
		'lastName' => "Smith",
		'email' => "bobxxxx@smith.com", //email must be unique
		'roleId' => "1",
		'password' => "opensesame",
		'salt' => "xxx",
		'active' => "yes"
	);

	$u = new User($options);

	// The insert method should throw an error if you try to 
	// insert a user_email that already exists in the database
	try{
		$newUser = $da->insert($u);
		$testResults[] = print_r($newUser, true);
	}catch(Exception $e){
		$testResults[] = $e->getMessage() . " EMAIL IS NOT UNIQUE???";
	}
	
}

function testUpdate(){

	global $testResults, $link;
	$testResults[] = "<h3>TESTING update()...</h3>";

	$da = new UserDataAccess($link);

	$options = array(
		'id' => 1,
		'firstName' => "Bobssssss",
		'lastName' => "Smith",
		'email' => "bobxxxxssssss@smith.com", //email must be unique
		'roleId' => "1",
		'password' => "opensesame",
		'salt' => "xxx",
		'active' => "yes"
	);

	$u = new User($options);

	// NOTE that you can't update a user's email if another user already has that email address
	if($da->update($u)){
		$testResults[] = "PASS - updated user";
		// You shouldn't call other methods in the class when testing a method
		$testResults[] = print_r($da->getById($u->id), true);
	}else{
		$testResults[] = "Failed - Did not update user when it should have";
	}
}

function testDelete(){
	// Note sure how we want to handle this
	// If you allow deletes then it can get messy with FK relationships
	// It might be better to set active = no
}


function testGetAllRoles(){

	global $testResults, $link;
	$testResults[] = "<h3>TESTING getAllRoles()...</h3>";

	$da = new UserDataAccess($link);
	$roles = $da->getAllRoles();
	$testResults[] = print_r($roles, true);
}


function testSaltAndHashPassword(){
	global $testResults;
	$testResults[] = "<h3>TESTING saltAndHashPassword()...</h3>";
}


?>