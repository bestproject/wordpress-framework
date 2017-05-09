<?php

namespace BestProject\Wordpress\Widget;

use BestProject\Wordpress\Widget;
use BestProject\Wordpress\Field;

// Creating the widget
class Editor extends Widget
{

	public function render($instance, $args)
	{

		if ( isset($instance['content']) ) {
			echo nl2br($instance['content']);
		}
	}

	/**
	 * Widget Backend
	 * @param type $instance
	 */
	public function getFields()
	{
		return array(
			(new Field\Textarea('content')),
		);
	}
}