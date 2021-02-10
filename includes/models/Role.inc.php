<?php
include_once("Model.inc.php");

class Role extends Model
{
	public $id;
	public $name;
	public $description;

	/**
	 * Constructor for creating Contact model objects
	 * @param {asoociative array} $args 	key value pairs that map to the instance variables
	 *										NOTE: the $args param is OPTIONAL, if it is not passed in
	 * 										The default will be an empty array: []									
	 */
	public function __construct($args = []){

		$this->id = $args['id'] ?? 0;
		$this->name = $args['name'] ?? "";
		$this->description = $args['description'] ?? "";
		
	}

	/**
	 * Validates the state this object. 
	 * Returns true if it is valid, false otherwise.
	 * For any properties that are not valid, a key will be added
	 * to the validationErrors array and it's value will be a description of the error.
	 * 
	 * @return {boolean}
	 */
	function isValid(){
		
		$valid = true;
		$this->validationErrors = [];


		if(!$this->id >= 0){
			$valid = false;
			$this->validationErrors['id'] = "ID is not valid";
		}

		// name should be 30 characters or less
		// description should be 200 characters or less
		if(empty($this->name)){
			$valid = false;
			$this->validationErrors['name'] = "Name is required";
		}else if(strlen($this->name) > 30){
			$valid = false;
			$this->validationErrors['name'] = "Name must be less than 30 characters";
		}

		if(empty($this->description)){
			$valid = false;
			$this->validationErrors['description'] = "Description is required";
		}else if(strlen($this->description) > 200){
			$valid = false;
			$this->validationErrors['description'] = "Description must be less than 200 characters";
		}


		return $valid;
	}
}