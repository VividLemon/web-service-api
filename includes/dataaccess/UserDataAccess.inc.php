<?php
/*
NOTE - The database column names do not map directly to the property names of the User model object
		
		User Model 		users table columns
		-----------------------------------
		id				user_id
		firstName		user_first_name
		lastName		user_last_name
		email			user_email
		roleId			user_role
		password		user_password
		salt			user_salt
		active			user_active

This is the purpose of the methods convertModelToRow() 
and convertRowToModel(). Note that both of these functions
also serve another purpose, they scrub the data to prevent hacks
(sometimes it makes sense to break the single responsibility rule!)

*/


require_once("DataAccess.inc.php");
include_once(__DIR__ . "/../models/User.inc.php"); // I had problems including this file, not sure why!


class UserDataAccess extends DataAccess{

	
	/**
	* Constructor
	* @param $link 		The connection to the database
	*/
	function __construct($link){
		parent::__construct($link);
	}

	/**
	* Converts a model object into an assoc array and sets the keys
	* to the proper names. For example a $user->id is converted to $row['user_id']
	* The data should also be scrubbed to prevent SQL injection attacks
	*
	* @param {User} $user 
	* @return {array}
	*/
	function convertModelToRow($user){

		$row = [];
		$row['user_id']	= mysqli_real_escape_string($this->link, $user->id);
		$row['user_first_name'] = mysqli_real_escape_string($this->link, $user->firstName);
		$row['user_last_name'] = mysqli_real_escape_string($this->link, $user->lastName);
		$row['user_email'] = mysqli_real_escape_string($this->link, $user->email);
		$row['user_role'] = mysqli_real_escape_string($this->link, $user->roleId);
		$row['user_password'] = mysqli_real_escape_string($this->link, $user->password);
		$row['user_salt'] = mysqli_real_escape_string($this->link, $user->salt);
		$row['user_active'] = mysqli_real_escape_string($this->link, $user->active);

		return $row;
	}

	/**
	* Converts a row from the database to a model object
	* And scrubs the data to prevent XSS attacks
	*
	* @param {array} $row
	* @return {User}		Returns a subclass of a Model 
	*/
	function convertRowToModel($row){
		// Note that if you have a column that allows some HMTL content
		// then use $this->sanitizeHTML() instead of htmlentities()

		$u = new User();
		$u->id = htmlentities($row['user_id']);
		$u->firstName = htmlentities($row['user_first_name']);
		$u->lastName = htmlentities($row['user_last_name']);
		$u->email = htmlentities($row['user_email']);
		$u->roleId = htmlentities($row['user_role']);
		$u->password = htmlentities($row['user_password']);
		$u->salt = htmlentities($row['user_salt']);
		$u->active = htmlentities($row['user_active']);

		return $u;
	}

	/**
	* Gets all rows from a table in the database
	* @param {assoc array} 	This optional param would allow you to filter the result set
	* 						For example, you could use it to somehow add a WHERE claus to the query
	* 
	* @return {array}		Returns an array of model objects
	*/
	function getAll($args = null){
		
		// Write a SQL query to select the id, first name, last name, and email address of every user in the users table
		$qStr = "SELECT
		            user_id, user_first_name, user_last_name, user_email, user_password, user_salt, user_role, user_active
		        FROM users";
		    
		//die($qStr);

		$result = mysqli_query($this->link, $qStr) or $this->handleError(mysqli_error($this->link));
		
		$allUsers = array();

		while($row = mysqli_fetch_assoc($result)){
		    $user = $this->convertRowToModel($row);
		    $allUsers[] = $user;
		}

		return $allUsers;
	}


	/**
	* Gets a row from the database by it's id
	* @param {number} $id 	The id of the item to get from a row in the database
	* @return {User}		Returns an instance of a model object 
	*/
	function getById($id){
		
		$qStr = "SELECT
		            user_id, 
		            user_first_name, 
		            user_last_name, 
		            user_email, 
		            user_salt, 
		            user_role, 
		            user_password, 
		            user_active
		        FROM users
		        WHERE user_id = " . mysqli_real_escape_string($this->link, $id);
		
		//die($qStr);

		$result = mysqli_query($this->link, $qStr) or $this->handleError(mysqli_error($this->link));
		
		if($result->num_rows == 1){

		    $row = mysqli_fetch_assoc($result);
		    $user = $this->convertRowToModel($row);
		    return $user;
		}

		return false;
	}

	
	/**
	 * Inserts a user into the database
	 * @param {User} $user
	 * @return {User}		Returns a User including the newly assignd user id			
	 */
	function insert($user){
		        
        $row = $this->convertModelToRow($user);
        
        // We'll talk about salting and hashing passwords later
        //$row['user_salt'] = $this->getRandomSalt();
        //$row['user_password'] = $this->saltAndHashPassword($row['user_salt'], $row['user_password']);

        $qStr = "INSERT INTO users (
                    user_first_name,
                    user_last_name, 
                    user_email, 
                    user_password, 
                    user_salt, 
                    user_role, 
                    user_active
                ) VALUES (
                    '{$row['user_first_name']}',
                    '{$row['user_last_name']}',
                    '{$row['user_email']}', 
                    '{$row['user_password']}', 
                    '{$row['user_salt']}', 
                    '{$row['user_role']}', 
                    '{$row['user_active']}'
                )";

        //die($qStr);

        $result = mysqli_query($this->link, $qStr) or $this->handleError(mysqli_error($this->link));

        if($result){
            // add the user id that was assigned by the data base
            $user->id = mysqli_insert_id($this->link);
            return $user;
        }else{
            $this->handleError("unable to insert user");
        }

        return false;
	}

	/**
	 * Updates a user in the database
	 * @param {User} $user
	 * @param {boolean} $hashPassword 	If true, the Users password should be salted and 
	 *									hashed before running the upate.
	 * @return {boolean}				Returns true if the update succeeds			
	 */
	function update($user, $hashPassword = false){
        
        $row = $this->convertModelToRow($user);
       
        if($hashPassword){
	        // this means the user's password has been changed, so we need to generate a new salt
	        // and then use it to has the new password
	        $row['user_salt'] = $this->getRandomSalt();
	        $row['user_password'] = $this->saltAndHashPassword($row['user_salt'], $row['user_password']);
        }

        $qStr = "UPDATE users SET
				user_first_name = '{$row['user_first_name']}',
				user_last_name = '{$row['user_last_name']}',
				user_email = '{$row['user_email']}', 
				user_password = '{$row['user_password']}',
				user_salt = '{$row['user_salt']}', 
				user_role = '{$row['user_role']}',  
				user_active = '{$row['user_active']}'
			WHERE user_id = " . $row['user_id'];
       
		//die($qStr);

		$result = mysqli_query($this->link, $qStr) or $this->handleError(mysqli_error($this->link));

		if($result){
			return true;
		}else{
			$this->handleError("unable to update user");
		}

		return false;
	}


	/**
    * Deletes a row from a table in the database
    * @param {number} 	The id of the row to delete
    * @return {boolean}	Returns true if the row was sucessfully deleted, false otherwise
    */
	function delete($args = null){
		// Note sure how we want to handle this
		// If you allow deletes then it can get messy with FK relationships
		// It might be better to set active = no
	}

	
	/**
	* Authenticates a user
	* @param $email
	* @param $password
	* @return 			Returns an assoc array with the user details if they authenticate
	* 					Returns false otherwise
	*/
	function login($email, $password){
	
		// We'll work on this later
		// BUT REMINDER: the user should be 'active' in order to login
	}

    // generates a random 'salt' string
	function getRandomSalt(){

		//FOR PHP7...
		$bytes = random_bytes(5);
		return bin2hex($bytes);

        // FOR EARLIER VERSIONS OF PHP
		//return mcrypt_create_iv(5);
	}

	// Hash the password and mix in the salt
	function saltAndHashPassword($salt, $password){

		// This is the old way to do it (less secure)
		//return md5($salt . $password . $salt);

		// there are better ways to encrypt passwords...
		// https://www.php.net/manual/en/function.password-hash.php

		// NOTE THAT FOR THIS TO WORK WE NEED TO ALTER THE user_password column
		// in the database to accomdate 255 characters!!!
		
		$encrypted_password = password_hash($salt . $password . $salt, PASSWORD_DEFAULT);
		return $encrypted_password;

		/*
		ALSO NOTE: you can use password_verify() to check it
		$password = "test123";
		$encrypted = password_hash($salt . $password . $salt);
		if(password_verify($salt . $password . $salt, $encrypted)){
			
		}
		*/
	}

}