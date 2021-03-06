<?php

namespace BestProject\Wordpress\Widget;

defined('ABSPATH') or die;

/**
 * Interface for Widgets definition.
 */
interface WidgetInterface {

	public function widget($args, $instance);

	public function form($instance);

	public function getFields();

	public function update($new_instance, $old_instance);

	public static function register($name);

	public function render($instance, $args);

}