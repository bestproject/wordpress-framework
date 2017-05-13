<?php

namespace BestProject\Wordpress\Form\Field;

defined('ABSPATH') or die;

use BestProject\Wordpress\Form\Field,
	BestProject\Wordpress\Language;

/**
 * E-mail field type.
 */
class Email extends Field
{

	/**
	 * Get this fields input
	 */
	public function getInput()
	{
		$id			 = (!empty($this->id) ? ' id="'.$this->id.'"' : '');
		$value		 = (!empty($this->value) ? ' value="'.$this->value.'"' : '');
		$hint		 = (!empty($this->hint) ? ' placeholder="'.Language::_($this->hint).'"'
				: '');
		$class		 = (!empty($this->class) ? ' class="'.$this->class.'"' : '');
		$required	 = ($this->required ? ' required' : '');
		?><input type="email" name="<?php echo $this->name ?>"<?php echo $id ?><?php echo $value ?><?php echo $class ?><?php echo $hint ?><?php echo $required ?> /><?php
	}

	/**
	 * Validate this field. Returns TRUE on success or error message on failure.
	 * 
	 * @return	Boolean
	 */
	public function validate()
	{

		// Check input email
		$valid_email = !(filter_var($this->getSaveData(), FILTER_VALIDATE_EMAIL) === false);

		// Check parent validation value
		$validation_result = parent::validate();

		// If this field failed parent validation, return error message.
		if ($validation_result !== true) {
			return $validation_result;
		}

		// If this field has an invalid e-mail return error
		if (!$valid_email) {
			return Language::_('FORM_EMAIL_FIELD_WRONG_VALUE', Language::_($this->label));
		}

		// All Ok so return a success.
		return true;
	}
}