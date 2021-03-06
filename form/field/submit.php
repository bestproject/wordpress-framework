<?php

namespace BestProject\Wordpress\Form\Field;

defined('ABSPATH') or die;

use BestProject\Wordpress\Form\Field,
	BestProject\Wordpress\Language;

/**
 * Submit button field type.
 */
class Submit extends Field
{

	protected $hide_label = true;

	/**
	 * Get this fields input
	 */
	public function getInput()
	{
		$id			 = (!empty($this->id) ? ' id="'.$this->id.'"' : '');
		$value		 = (!empty($this->value) ? ' value="'.Language::_($this->value).'"' : '');
		$class		 = (!empty($this->class) ? ' class="'.$this->class.'"' : '');
		?><input type="submit" name="<?php echo $this->name ?>"<?php echo $id ?><?php echo $value ?><?php echo $class ?> /><?php
	}
}