<?php
include_once("Model.inc.php");

class User extends Model
{

	// INSTANCE VARIABLES
	public $id;
	public $firstName;
	public $lastName;
	public $email;
	public $roleId;
	public $password;
	public $salt;
	public $active;

	
	/**
	 * Constructor for creating Contact model objects
	 * @param {asoociative array} $args 	key value pairs that map to the instance variables
	 *										NOTE: the $args param is OPTIONAL, if it is not passed in
	 * 										The default will be an empty array: []									
	 */
	public function __construct($args = []){

		// NOTE that in PHP we use bracket notation for associative arrays
		$this->id = $args['id'] ?? 0;
		$this->firstName = $args['firstName'] ?? "";
		$this->lastName = $args['lastName'] ?? "";
		$this->email = $args['email'] ?? "";
		$this->roleId = $args['roleId'] ?? "";
		$this->password = $args['password'] ?? "";
		$this->salt = $args['salt'] ?? "";
		$this->active = $args['active'] ?? "";
	}

	/**
	 * Validates the state of this object. 
	 * Returns true if it is valid, false otherwise.
	 * For any properties that are not valid, a key will be added
	 * to the validationErrors array and it's value will be a description of the error.
	 * 
	 * @return {boolean}
	 */
	public function isValid()
	{

		$valid = true;
		$this->validationErrors = [];
		
		// validate id
		if (($this->id >= 0) == false) {
			$valid = false;
			$this->validationErrors['id'] = "ID is not valid";
		}

		// validate first name
		if (empty($this->firstName)) {
			$valid = false;
			$this->validationErrors['firstName'] = "First Name is required";
		}else if(strlen($this->firstName) > 30){
			$valid = false;
			$this->validationErrors['firstName'] = "First Name must be 30 characters or less";
		}

		// validate lastName
		if (empty($this->lastName)) {
			$valid = false;
			$this->validationErrors['lastName'] = "Last Name is required";
		}else if(strlen($this->lastName) > 30){
			$valid = false;
			$this->validationErrors['lastName'] = "Last Name must be 30 characters or less";
		}

		// validate email
		if (empty($this->email)){
			$valid = false;
			$this->validationErrors['email'] = "Email is required";
		}else if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)){
			$valid = false;
			$this->validationErrors['email'] = "The email address is not valid";
		}else if(strlen($this->email) > 255){
			$valid = false;
			$this->validationErrors['email'] = "The email address must not be more than 255 characters";
		}

		// role id should be a number greater than zero
		if (!$this->roleId > 0) {
			$valid = false;
			$this->validationErrors['roleId'] = "Role id is not valid";
		}

		// password should not be empty
		// do we need to enforce password strength????
		// the length should not be validated because we'll be using encryption, which should force it to be a certain length
		if (empty($this->password)) {
			$valid = false;
			$this->validationErrors['password'] = "Password is required";
		}

		// salt does not need validation

		// active must be either 'yes' or 'no'
		if ($this->active != "yes" && $this->active != "no") {
			$valid = false;
			$this->validationErrors['active'] = "Active setting is not valid";
		}

		return $valid;
	}

}
