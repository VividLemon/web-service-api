<?php
include_once(__DIR__ . "/../models/Model.inc.php");

abstract class DataAccess{

	// INSTANCE VARIABLES

	/**
	* A connection to the database (a msqli object)
	*/
	protected $link;


	/**
	* Constructor function for this class
	* @param $link 		A preconfigured connection to the database
	*/
	function __construct($link){

		if($link && $link instanceof mysqli){
			$this->link = $link;
		}else{
			$this->handleError("The link param is not a valid connection");
		}
	
	}


	// METHODS

	/**
	* Subclasses should invoke this method if an error occurs while executing a query.
	* The method can be in for other errors that occur within a subclass as well.
	* @example	 mysqli_query($this->link, $query) or $this->handleError(mysqli_error($this->link));
	* 
	*/
	protected function handleError($msg){
		throw new Exception($msg);
	}
		

	/**
	* Removes 'dangerous' html tags and attributes from a string of html.
	* @param {string} $inputHTML 		A string that includes HTML mark up in it.
	* @return {string}					The sanitized HTML string
	*/
	function sanitizeHtml($inputHTML){
	       
	    // we'll allow these tags, but no others (we are white-listing)
	    $allowed_tags = array('<sub>','<sup>','<div>','<span>','<h1>','<h2>','<br>','<h3>','<h4>','<h5>','<h6>','<h7>','<i>','<b>','<a>','<ul>','<ol>','<em>','<li>','<pre>','<hr>','<blockquote>','<p>','<img>','<strong>','<code>');

	    //replace dangerous attributes...
	    $bad_attributes = array('onerror','onmousemove','onmouseout','onmouseover','onkeypress','onkeydown','onkeyup','onclick','onchange','onload','javascript:');
	    $inputHTML = str_replace($bad_attributes,"x",$inputHTML);
	   
	    $allowed_tags = implode('',$allowed_tags);
	    $inputHTML = strip_tags($inputHTML, $allowed_tags);

	    return $inputHTML;

	}

	/**
	* Converts a date string into the format required by MySQL
	* @param $dateStr {string}		A string that can be parsed into a Date object and then
	* 								formatted for inserting into a MySQL database (Y-m-d)
	*/
	function convertDateForMySQL($dateStr){
		$dt = new DateTime($dateStr);
	    return $dt->format('Y-m-d');
	}

	
	/**
	* Converts a model object into an assoc array and sets the keys
	* to the proper names. For example a $user->id is converted to $row['user_id']
	* The data should also be scrubbed to prevent SQL injection attacks
	*
	* @param {Model}
	* @return {array}
	*/
	abstract function convertModelToRow($model);

	/**
	* Converts a row from the database to a model object
	* And scrubs the data to prevent XSS attacks
	*
	* @param {array}
	* @return {Model}	Returns a subclass of a Model 
	*/
	abstract function convertRowToModel($row);


	/**
	* Gets a row from the database by it's id
	* @param {number} 	The id of the item to get from a row in the database
	* @return {Model}	Returns an instance of a subclass of Model object
	*/
	abstract function getById($id);

	/**
	* Gets all rows from a table in the database
	* @param {assoc array} 	This optional param would allow you to filter the result set
	* 						For example, you could use it to somehow add a WHERE claus to the query
	* 
	* @return {array}		Returns an array of model objects
	*/
	abstract function getAll($args = []);


	/**
	* Inserts a row into a table in the database
	* @param {object}	The model object to be inserted
	* @return {object}	Returns the same model object, but with the id property set (the id is assigned by the database)
	*/
	abstract function insert($obj);

	/**
	* Updates a row in a table of the database
	* @param {object}	The model object to be updated
	* @return {object}	Returns the same model object that was passed in as the param
	*/
	abstract function update($obj);


	/**
	* Deletes a row from a table in the database
	* @param {number} 	The id of the row to delete
	* @return {boolean}	Returns true if the row was sucessfully deleted, false otherwise
	*/
	abstract function delete($id);

}