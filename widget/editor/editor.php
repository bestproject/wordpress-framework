<?php

namespace BestProject\Wordpress\Widget;

defined('ABSPATH') or die;

use BestProject\Wordpress\Widget,
	BestProject\Wordpress\Form\Field;

/**
 * Editor widget definition.
 */
class Editor extends Widget
{

	/**
	 * Method that renders the widget.
	 * 
	 * @param	Array	$instance
	 * @param	Array	$args
	 */
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