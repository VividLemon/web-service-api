<?php

abstract class Model {

	/**
	* validationErrors will be populated when isValid() is called.
	* It should include a key for any property of the model that is not valid
	* The key name will be the property name, and it's value will be a description of the error.
	*/
	public $validationErrors = [];
	
	/**
	* Validates the state of a this Model object. 
	* Returns true if it is valid, false otherwise
	* Note that when you implement this method, you should populate the validationErrs array
	* with a key for each property that is not valid, the value should be a description of the error.
	* For example: If the 'id' property of this object is not valid:
	* 		$this->validationErrors['id'] = "The id must be a valid number"
	* 
	* @return {boolean}
	*/
	abstract public function isValid();
	

	/**
	* Converts an instance of a model object into JSON
	* @return {string}		The state of the model object in JSON encoded formatting
	*/
	public function toJSON(){
		
		return json_encode($this->toArray());
	}

	/**
	* Converts the model to an assoc array and removes the validationErrors property
	* @return {array}
	*/
	public function toArray(){
		$array = (array)$this;
		unset($array['validationErrors']);
		return $array;
	}


	/**
	* Converts an instance of a model object into CSV
	* @return {string}		The state of the model in CSV format
	*/
	public function toCSV(){

		// Not sure if this approach will work
		// How is the order of values determined?
		// What happens to methods, are they included in the csv? 
		$cells = [];
		foreach ($this->toArray() as $value) {
			$cells[] = $value;
		}
		return implode($cells,",");
	}


	/**
	* Converts an instance of a model object into XML
	* @return {string}		The state of the model in XML format
	*/
	public function toXML(){

		$rootElement = strtolower(get_class($this));
		
		$xml = new SimpleXMLElement(strtolower('<' . $rootElement . '/>'));

		//$xml = simplexml_load_string("<$rootElement />");
		foreach ($this->toArray() as $key => $value) {
		  $xml->addChild($key, $value);
		}
		$XMLstring = $xml->asXML();
		// to remove the xml doc type declaration
		//https://stackoverflow.com/questions/5947695/remove-xml-version-tag-when-a-xml-is-created-in-php
		$XMLstring = substr($XMLstring, strpos($XMLstring, '?'.'>') + 2);

		return trim($XMLstring);
	}

	/**
	* Compares two Model objects to see if they hold the same data
	* @param {mixed}
	* @return {boolean}
	*/
	public function equals($obj){
		
		if($obj instanceof Model){
			if(empty(array_diff_assoc($this->toArray(), $obj->toArray()))){
				return true;
			}
		}
		
		return false;
	}


}