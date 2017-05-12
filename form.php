<?php

namespace BestProject\Wordpress;

use BestProject\Wordpress\Object;

class Form {

	use Object;

	/**
	 * Name of this form.
	 * 
	 * @var	String
	 */
	protected $name;

	/**
	 * List of form fields.
	 * 
	 * @var	Array
	 */
	protected $fields;

	/**
	 * Form method.
	 * 
	 * @var	String
	 */
	protected $method;

	/**
	 * Array of form tag attributes.
	 * 
	 * @var	Array
	 */
	protected $attributes;

	/**
	 *
	 * @param	String	$name	Name of this form.
	 * @param	Array	$fields	List of form fields.
	 */
	public function __construct($name, Array $fields) {
		$this->name = $name;
		foreach( $fields AS $field ) {
			$this->fields[$field->get('name')] = $field;
		}

		// Set basic form attributes
		$this->setAttribute('name', $name);
		$this->setAttribute('method', 'post');
		$this->setAttribute('id', 'form-'.$name);
		$this->setAttribute('action', filter_input(INPUT_SERVER, 'REQUEST_URI'));
	}

	/**
	 * Get a render list of form attributes like name, action, method.
	 */
	public function renderFormAttributes() {
		$html = '';
		foreach( $this->attributes AS $attribute=>$value ) {
			$html.= $attribute.'="'.htmlentities($value).'" ';
		}

		return trim($attribute);
	}

	/**
	 * Set a form tag attribute and return form instance for chaining.
	 * 
	 * @param	String	$name	Attribute name.
	 * @param	String	$value	Attribute value.
	 */
	public function setAttribute($name, $value) {
		$this->attributes[$name] = $value;

		return $this;
	}

	/**
	 * Render a selected form field.
	 * 
	 * @param	String	$name	Name of a field to render.
	 * @return	String
	 */
	public function renderField($name){
		if( isset($this->fields[$name]) ) {
			return $this->fields[$name]->render();
		} else {
			throw new Exception('Field '.$name.' was not found in this form.', 500);
		}
	}

	/**
	 * Does this form validate? Check each field if it validates.
	 * 
	 * @return	Boolean
	 */
	public function validate(){

		// Validate each form field.
		$validates = true;
		foreach( $this->fields AS $field ) {

			// Get validation result
			$validate_result = $field->validate();

			// If this field did not validate add its error message to form errors.
			if( $validate_result!==true ) {
				$this->errors[] = $validate_result;
				$validates = false;
			}
		}
		
		return $validates;
	}

	/**
	 * Return errors list.
	 * 
	 * @return	Array
	 */
	public function getErrors() {
		return $this->errors;
	}

	

}