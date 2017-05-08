<?php

namespace BestProject\Wordpress\Widget;

use BestProject\Wordpress\Widget;
use BestProject\Wordpress\Field;

// Creating the widget
class Editor extends Widget
{

	public function render($instance, $args)
	{
//		var_dump($instance);die;
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
//			(new Field\Editor('title'))->set('required'),
			(new Field\Editor('content'))->set('teeny'),
		);
	}
//
//// Updating widget replacing old instances with new
//	/**
//	 * Updating widget replacing old instances with new
//	 * @param type $new_instance
//	 * @param type $old_instance
//	 * @return type
//	 */
//	public function update($new_instance, $old_instance)
//	{
//		$instance			 = array();
//		$instance['title']	 = (!empty($new_instance['title']) ) ? strip_tags($new_instance['title'])
//				: '';
//		return $instance;
//	}
}