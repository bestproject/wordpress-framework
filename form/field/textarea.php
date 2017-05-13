<?php

namespace BestProject\Wordpress\Form\Field;

use BestProject\Wordpress\Form\Field,
	BestProject\Wordpress\Language;

/**
 * Textarea field.
 */
class Textarea extends Field
{
	protected $rows = 4;

	/**
	 * Get this fields input
	 */
	public function getInput()
	{
		$id			 = (!empty($this->id) ? ' id="'.$this->id.'"' : '');
		$rows		 = (!empty($this->rows) ? ' rows="'.$this->rows.'"' : '');
		$hint		 = (!empty($this->hint) ? ' placeholder="'.Language::_($this->hint).'"' : '');
		$class		 = (!empty($this->class) ? ' class="'.$this->class.'"' : '');
		$required	 = ($this->required ? ' required' : '');
		?><textarea name="<?php echo $this->name ?>"<?php echo $id ?><?php echo $rows ?><?php echo $hint ?><?php echo $class ?><?php echo $required ?>><?php echo $this->value ?></textarea><?php
	}
}