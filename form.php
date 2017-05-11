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
		$this->fields = $fields;

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

}