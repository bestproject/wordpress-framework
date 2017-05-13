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
		$hint		 = (!empty($this->hint) ? ' placeholder="'.Language::_($this->hint).'"' : '');
		$class		 = (!empty($this->class) ? ' class="'.$this->class.'"' : '');
		$required	 = ($this->required ? ' required' : '');
		?><input type="email" name="<?php echo $this->name ?>"<?php echo $id ?><?php echo $value ?><?php echo $class ?><?php echo $hint ?><?php echo $required ?> /><?php
	}

	/**
	 * Validate this field.
	 * 
	 * @return	Boolean
	 */
	public function validate() {

		if( !parent::validate() OR !filter_var($this->getSaveData(), FILTER_VALIDATE_EMAIL) ) {
			return false;
		}

		return true;
	}
}