<?php

namespace BestProject\Wordpress\Field;

use BestProject\Wordpress\Field;

/**
 * Textarea field.
 */
class Textarea extends Field
{

	/**
	 * Get this fields input
	 */
	public function getInput()
	{
		$id			 = (!empty($this->id) ? ' id="'.$this->id.'"' : '');
		$hint		 = (!empty($this->hint) ? ' placeholder="'.$this->hint.'"' : '');
		$required	 = ($this->required ? ' required' : '');
		?><textarea class="widefat" name="<?php echo $this->name ?>"<?php echo $id ?><?php echo $hint ?><?php echo $required ?>><?php echo $this->value ?></textarea><?php
	}
}