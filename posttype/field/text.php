<?php

namespace BestProject\Wordpress\PostType\Field;

use BestProject\Wordpress\PostType\Field;

class Text extends Field
{

	public function getInput()
	{
		$id			 = (!empty($this->id) ? ' id="'.$this->id.'"' : '');
		$value		 = (!empty($this->value) ? ' value="'.$this->value.'"' : '');
		$hint		 = (!empty($this->hint) ? ' placeholder="'.$this->hint.'"' : '');
		$required	 = ($this->required ? ' required' : '');
		?><input type="text" name="<?php echo $this->name ?>"<?php echo $id ?><?php echo $value ?><?php echo $hint ?><?php echo $required ?>><?php
	}
}