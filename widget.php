<?php

namespace BestProject\Wordpress;

defined('ABSPATH') or die;

use BestProject\Wordpress\Language,
	BestProject\Wordpress\Widget\WidgetInterface;

/**
 * Class that takes care of the all the functions required by Wordpress to create a widget.
 */
abstract class Widget extends \WP_Widget implements WidgetInterface
{
	/**
	 * Name of this widget(id).
	 * @var	String
	 */
	public $name;

	/**
	 * Title of this widget.
	 *
	 * @var type
	 */
	public $title;

	/**
	 * Short description of this widget.
	 *
	 * @var	String
	 */
	public $description;

	/**
	 * Widget constructor.
	 */
	public function __construct()
	{

		// Create widget name if not set
		if (empty($this->name)) {
			$className	 = explode('\\', get_class($this));
			$className	 = strtolower(end($className));
			$this->name	 = 'bp_'.$className;
		}

		// Create widget UI title if not set
		if (empty($this->title)) {
			$this->title = 'WIDGET_'.strtoupper($this->name).'_TITLE';
		}

		// Create widget UI description if not set
		if (empty($this->description)) {
			$this->description = 'WIDGET_'.strtoupper($this->name).'_DESC';
		}

		// Create new widget instance
		parent::__construct(
			$this->name, Language::_($this->title),
			array('description' => Language::_($this->description))
		);
	}

	/**
	 * Displays a widget instance on front-end.
	 *
	 * @param	Array		$args
	 * @param	WP_Widget	$instance
	 */
	public function widget($args, $instance)
	{
		$this->instance = $instance;

		ob_start();

		// before and after widget arguments are defined by themes
		echo $args['before_widget'];

		// If there is title field in this widget
		if (isset($instance['title']) AND ! empty($instance['title'])) {

			$title = apply_filters('widget_title', $instance['title']);
			if (!empty($title)) {
				echo $args['before_title'].$title.$args['after_title'];
			}
		}
		$this->render($instance, $args);
		echo $args['after_widget'];

		echo ob_get_clean();
	}

	/**
	 * Render widget form.
	 * 
	 * @param	Array	$instance
	 */
	public function form($instance)
	{
		$fields = $this->getFields();

		$html = '';
		/* @var $field Field */
		foreach ($fields AS $field) {

			$name = $field->get('name');

			// Prepare the value
			if (isset($instance[$name])) {
				$field->set('value', $instance[$field->get('name')]);
			}

			// Set `name` attribute as provided by the system
			$field->set('name', $this->get_field_name($name));
			// Set `id` attribute as provided by the system
			$field->set('id', $this->get_field_id($name));

			$html .= $field->render();
		}

		echo $html;
	}

	/**
	 * Hook on updating this widget.
	 * 
	 * @param	Array	$new_instance
	 * @param	Array	$old_instance
	 * @return	Array
	 */
	public function update($new_instance, $old_instance)
	{

		return $new_instance;
	}

	/**
	 * Renders the widget.
	 *
	 * @param	Array	$instance	An array of widget instance data.
	 * @param	Array	$args		An array of widget/sidebar data.
	 */
	public function render($instance, $args)
	{

		// Get name of the widget
		$reflection		 = new \ReflectionClass($this);
		$filename		 = strtolower($reflection->getName());
		$override_path	 = get_template_directory().'/template-parts/widgets/'.$filename.'.php';

		// If there is html override for this widget in a template, render it
		if (file_exists($override_path)) {
			extract($instance);

			require $override_path;

			// If there is no override. Just print the data
		} else {
			ob_start();
			foreach ($instance AS $key => $variable) {
				if ($key !== 'title') {
					?><div><h4><?php echo Language::_(strtoupper($key)) ?></h4><div><?php echo $variable ?></div></div><?php
				}
			}
		}
	}

	/**
	 * Register this widget.
	 * 
	 * @param	String	$className
	 */
	public static function register($className)
	{
		register_widget($className);
	}
}