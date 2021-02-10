<?php

require_once("DataAccess.inc.php");
include_once(__DIR__ . "/../models/Role.inc.php"); // I had problems including this file, not sure why!


class RoleDataAccess extends DataAccess{

	    /**
	    * Constructor function for this class
	    * @param {mysqli} $link      A preconfigured connection to the database
	    */
	    function __construct($link){
			parent::__construct($link); // call the super constructor
	    }

	    /**
	    * Converts a model object into an assoc array and sets the keys
	    * to the proper names. For example a $role->id is converted to $row['user_role_id']
	    * The data should also be scrubbed to prevent SQL injection attacks
	    *
	    * @param {Role} $role 
	    * @return {array}
	    */
	    function convertModelToRow($role){
	    	return []; // replace this with the real code
	    }

	    /**
	    * Converts a row from the database to a model object
	    * And scrubs the data to prevent XSS attacks
	    *
	    * @param {array} $row
	    * @return {Role}		Returns a subclass of a Model 
	    */
	    function convertRowToModel($row){

	    	// Note that if you have a column that allows some HMTL content
			// then use $this->sanitizeHTML() instead of htmlentities()
	    	
	    	return new Role(); // replace this with the real code
	    }


	    /**
	    * Gets a row from the database by it's id
	    * @param {number} $id 	The id of the item to get from a row in the database
	    * @return {Role}		Returns an instance of a model object 
	    */
	    function getById($id){

	    }

	    /**
	    * Gets all rows from a table in the database
	    * @param {assoc array} 	This optional param would allow you to filter the result set
	    * 						For example, you could use it to somehow add a WHERE claus to the query
	    * 
	    * @return {array}		Returns an array of model objects
	    */
	    function getAll($args = []){

	    }


	    /**
	    * Inserts a row into a table in the database
	    * @param {Role}	$role	The model object to be inserted
	    * @return {Role}		Returns the same model object, but with the id property set 
	    *						(the id is assigned by the database)
	    */
	    function insert($role){

	    }

	    /**
	    * Updates a row in a table of the database
	    * @param {Role}	$role	The model object to be updated
	    * @return {object}		Returns the same model object that was passed in as the param
	    */
	    function update($role){

	    }


	    /**
	    * Deletes a row from a table in the database
	    * @param {number} 	The id of the row to delete
	    * @return {boolean}	Returns true if the row was sucessfully deleted, false otherwise
	    */
	    function delete($id){
	    	// should we really delete a row?
	    	// it can get super tricky when there are foreign keys!
	    }
}