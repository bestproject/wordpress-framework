<?php

namespace BestProject\Wordpress\Field;

use BestProject\Wordpress\Field;

class Text extends Field
{

	/**
	 * Get this fields input
	 */
	public function getInput()
	{
		$id			 = (!empty($this->id) ? ' id="'.$this->id.'"' : '');
		$value		 = (!empty($this->value) ? ' value="'.$this->value.'"' : '');
		$hint		 = (!empty($this->hint) ? ' placeholder="'.$this->hint.'"' : '');
		$required	 = ($this->required ? ' required' : '');
		?><input type="text" class="widefat" name="<?php echo $this->name ?>"<?php echo $id ?><?php echo $value ?><?php echo $hint ?><?php echo $required ?>><?php
	}
}