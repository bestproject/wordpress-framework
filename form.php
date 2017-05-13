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
	 * Holds list of form errors (mostly validation).
	 *
	 * @var	Array
	 */
	protected $errors = array();

	/**
	 * Subject of an e-mail if there are e-mail recipipients for this form.
	 *
	 * @var	String
	 */
	protected $mail_subject = '';

	/**
	 * Sender e-mail if there are e-mail recipients for form.
	 *
	 * @var	String
	 */
	protected $mail_sender = '';

	/**
	 * Name of the sender if there are e-mail recipients for form.
	 *
	 * @var	String
	 */
	protected $mail_sender_name = '';

	/**
	 * Create new Form instance.
	 *
	 * @param	String	$name				Name of this form.
	 * @param	Array	$fields				List of form fields.
	 * @param	Mixed	$recipient			Recipient for this form data. It accepts a valid email, name of a form field that holds the email or a PostType instance that should hold the data. It also accepts array of those values.
	 * @param	String	$mail_subject		Subject for a mail if there are e-mail recipients in this form.
	 * @param	String	$mail_sender_name	Name of a sender if there are e-mail recipients in this form.
	 */
	public function __construct($name, Array $fields, $recipient = array(),
							 $mail_subject = '', $mail_sender = '', $mail_sender_name = '')
	{

		// Add fields using its name as array key so we can access them easly later
		foreach ($fields AS $field) {
			$this->fields[$field->get('name')] = $field;
		}

		// Set default params
		$this->set('name', $name);
		$this->set('recipient', $recipient);
		$this->set('mail_subject', $mail_subject);
		$this->set('mail_sender', $mail_sender);
		$this->set('mail_sender_name', $mail_sender_name);

		// Set basic form attributes
		$this->setAttribute('method', 'post');
		$this->setAttribute('id', 'form-'.$name);
		$this->setAttribute('action', filter_input(INPUT_SERVER, 'REQUEST_URI').'#form-'.$name);
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
	 * Process form input. Returns:
	 * TRUE: If every recipient was processed correctly.
	 * FALSE: If there was a single error while processing the recipients.
	 * NULL: If there is no data to process.
	 */
	public function processInput()
	{

		// Is there anything to process
		if (strtoupper($this->attributes['method']) == 'POST' AND empty($_POST)) {
			return;
		} elseif (strtoupper($this->attributes['method']) == 'GET' AND empty($_GET)) {
			return;
		}

		// Holding recipient processing results
		$results = array();

		// If there is a list providing recipients
		if (is_array($this->recipient)) {

			// Process each recipient
			foreach ($this->recipient AS $recipient) {
				$results[] = $this->processRecipient($recipient);
			}

			// its a single recipient
		} else {
			$results[] = $this->processRecipient($this->recipient);
		}

		// Was there any error while processing the recipients list?
		if ( in_array(false, $results)) {
			return false;

		// All recipients processed without an error
		} elseif( !empty($results) ) {
			return true;
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
			//
			
			// Is an object but wrong type
		} elseif (is_object($recipient)) {
			throw new \Exception('Wrong object provided as a form recipient.');

			// String provided
		} elseif (is_string($recipient)) {

			$recipient = (string) $recipient;

			// Is this a valid e-mail
			$is_valid_email = !(filter_var($recipient, FILTER_VALIDATE_EMAIL) === false);

			// Is this not a valid email but a valid form field name that holds an e-mail
			if (!$is_valid_email AND isset($this->fields[$recipient]) AND ! (filter_var($this->fields[$recipient]->getSaveData(),
					FILTER_VALIDATE_EMAIL) === false)) {
				$recipient		 = $this->fields[$recipient]->getSaveData();
				$is_valid_email	 = true;
			} elseif (!$is_valid_email) {
				$is_valid_email = false;
			}

			// Send e-mail
			if ($is_valid_email AND $this->sendEmail($recipient)) {
				return true;
			} else {

				$this->errors[] = Language::_('FORM_COULD_NOT_SEND_EMAIL');

				return false;
			}
		}
	}

	/**
	 * Send e-mail with form input to the selected e-mail address.
	 * 
	 * @param	String	$recipient_email	Recipient e-mail address.
	 * @return	Boolean
	 */
	private function sendEmail($recipient_email)
	{

		// Generate an e-mail template file
		$path = get_template_directory().'/template-parts/emails/'.$this->name.'.php';

		// Default e-mail options
		$mail_subject		 = $this->mail_subject;
		$mail_sender		 = $this->mail_sender;
		$mail_sender_name	 = $this->mail_sender_name;
		$mail_recipient		 = $recipient_email;

		// If e-mail template file exists
		if (file_exists($path)) {

			// Generate e-mail body
			ob_start();
			require $path;
			$mail_body = ob_get_clean();

			// There is no e-mail template. Use default view
		} else {

			// List of field types that should not be processed
			$hide_fields = array(
				'\\BestProject\Wordpress\\Form\\Field\\Submit',
				'\\BestProject\Wordpress\\Form\\Field\\Button',
			);

			$mail_body = '';
			/* @var $field Field */
			foreach ($this->fields AS $field) {

				if (!in_array(get_class($field), $hide_fields)) {
					$mail_body	 .= '<div>';
					$mail_body	 .= '<h4>'.$field->get('label').'</h4>';
					$mail_body	 .= $field->getSaveData();
					$mail_body	 .= '</div>'."\n";
				}
			}
		}

		// If any of required parameters is missing, return failure
		if (empty($mail_subject) OR empty($mail_sender) OR empty($mail_sender_name) OR empty($mail_body)) {
			return false;

			// We have all the data, so send the message
		} else {
			$headers[]	 = 'MIME-Version: 1.0';
			$headers[]	 = 'Content-type: text/html; charset=utf-8';

			// Add sender name if provided
			if( !empty($mail_sender_name) ) {
				$headers[]	 = 'From: '.$mail_sender_name.' <'.$mail_sender.'>';

			// No sender name, just set an e-mail address
			} else {
				$headers[]	 = 'From: '.$mail_sender;
			}

			// Send email and return its
			return mail($mail_recipient, $mail_subject, $mail_body,
				implode("\r\n", $headers));
		}

		return true;
	}

	/**
	 * Returns an instance of a selected form field.
	 * 
	 * @param	String			$name	Name of a field to return.
	 * @return	Field|Boolean
	 */
	public function &getField($name)
	{

		// If field with this name was set in this form, return its reference.
		if (isset($this->fields[$name])) {
			return $this->fields[$name];
		}

		return false;
	}
}