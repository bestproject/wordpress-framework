<?php


namespace BestProject\Wordpress;

use BestProject\Wordpress\Object;

defined('ABSPATH') or die;

/**
 * Form class.
 */
class Form
{

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
	 * Recipient for this form data. It accepts a valid email, name of a form field
	 * that holds the email or a PostType instance that should hold the data.
	 * It also accepts array of those values.
	 *
	 * Form is processing the recipients and its input on construct if $recipient is not empty.
	 *
	 * @var	Mixed
	 */
	protected $recipient;

	/**
	 * Create new Form instance.
	 *
	 * @param	String	$name		Name of this form.
	 * @param	Array	$fields		List of form fields.
	 * @param	Mixed	$recipient	Recipient for this form data. It accepts a valid email, name of a form field that holds the email or a PostType instance that should hold the data. It also accepts array of those values.
	 */
	public function __construct($name, Array $fields, $recipient = array())
	{

		// Add fields using its name as array key so we can access them easly later
		foreach ($fields AS $field) {
			$this->fields[$field->get('name')] = $field;
		}

		// Set default params
		$this->set('name', $name);
		$this->set('recipient', $recipient);

		// Set basic form attributes
		$this->setAttribute('method', 'post');
		$this->setAttribute('id', 'form-'.$name);
		$this->setAttribute('action', filter_input(INPUT_SERVER, 'REQUEST_URI'));

		// Process form input if recipients list is not empty.
		if (!empty($recipient)) {
			$this->processData();
		}
	}

	/**
	 * Get a render list of form attributes like name, action, method.
	 */
	public function renderFormAttributes()
	{
		$html = '';
		foreach ($this->attributes AS $attribute => $value) {
			$html .= $attribute.'="'.htmlentities($value).'" ';
		}

		return trim($html);
	}

	/**
	 * Set a form tag attribute and return form instance for chaining.
	 * 
	 * @param	String	$name	Attribute name.
	 * @param	String	$value	Attribute value.
	 */
	public function setAttribute($name, $value)
	{
		$this->attributes[$name] = $value;

		return $this;
	}

	/**
	 * Return selected form attribute.
	 * 
	 * @param	String	$name		Attribute name to return.
	 * @param	Mixed	$default	Default value.
	 * @return String
	 */
	public function getAttribute($name, $default = '')
	{
		if (isset($this->attributes[$name])) {
			return $this->attributes[$name];
		} else {
			return $default;
		}
	}

	/**
	 * Render a selected form field.
	 * 
	 * @param	String	$name	Name of a field to render.
	 * @return	String
	 */
	public function renderField($name)
	{
		if (isset($this->fields[$name])) {
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
	public function validate()
	{

		// Validate each form field.
		$validates = true;
		foreach ($this->fields AS $field) {

			// Get validation result
			$validate_result = $field->validate();

			// If this field did not validate add its error message to form errors.
			if ($validate_result !== true) {
				$this->errors[]	 = $validate_result;
				$validates		 = false;
			}
		}

		return $validates;
	}

	/**
	 * Return errors list.
	 * 
	 * @return	Array
	 */
	public function getErrors()
	{
		return $this->errors;
	}

	/**
	 * Process form input.
	 */
	public function processData()
	{

		// Is there anything to process
		if (strtoupper($this->attributes['method']) == 'POST' AND empty($_POST)) {
			return;
		} elseif( strtoupper($this->attributes['method']) == 'GET' AND empty($_GET) ) {
			return;
		}

		// If there is a list providing recipients
		if (is_array($this->recipient)) {

			// Process each recipient
			foreach ($this->recipient AS $recipient) {
				$this->processRecipient($recipient);
			}
		} else {
			$this->processRecipient($this->recipient);
		}
	}

	/**
	 * Process selected recipient.
	 * 
	 * @param	$recipient
	 */
	private function processRecipient($recipient)
	{


		// Its a PostType
		$className = '\\BestProject\\Wordpress\\PostType';
		if (is_object($recipient) AND ( $recipient instanceof $className)) {
			// TODO: Process PostType as a recipient

		// Is an object but wrong type
		} elseif( is_object($recipient) ) {
			throw new \ErrorException('Wrong object provided as a form recipient.');

		// String provided
		} elseif( is_string($recipient) ) {

			$recipient = (string)$recipient;

			// Is this a valid e-mail
			$is_valid_email = filter_var($recipient, FILTER_VALIDATE_EMAIL);

			// Is this not a valid email but a valid form field name that holds an e-mail
			if( !$is_valid_email AND isset($this->fields[$recipient]) AND filter_var($this->fields[$recipient]->getSaveData(), FILTER_VALIDATE_EMAIL) ) {
				$recipient = $this->fields[$recipient]->getSaveData();
			}

			// Send e-mail
		}


	}

	private function sendEmail($recipient_email) {
		
	}
}