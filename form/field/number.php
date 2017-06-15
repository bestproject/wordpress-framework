<?php

namespace BestProject\Wordpress\Form\Field;

defined('ABSPATH') or die;

use BestProject\Wordpress\Language,
    BestProject\Wordpress\Form\Field;

/**
 * Number field type.
 */
class Number extends Field
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
		?><input type="number" name="<?php echo $this->name ?>"<?php echo $id ?><?php echo $value ?><?php echo $class ?><?php echo $hint ?><?php echo $required ?> /><?php
	}
}