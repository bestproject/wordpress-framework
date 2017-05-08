<?php

namespace BestProject\Wordpress;

use BestProject\Wordpress\Language;
use BestProject\Wordpress\Widget\WidgetInterface;

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
		$this->instance	 = $instance;
		$title			 = apply_filters('widget_title', $instance['title']);

		ob_start();

		// before and after widget arguments are defined by themes
		echo $args['before_widget'];
		if (!empty($title)) {
			echo $args['before_title'].$title.$args['after_title'];
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
		if (isset($instance['title'])) {
			$title = $instance['title'];
		} else {
			$title = Language::_('NEW_WIDGET_TITLE');
		}
		// Widget admin form
		?>
		<p>
			<label for="<?php echo $this->get_field_id('title') ?>"><?php Language::_('Title:') ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title') ?>" name="<?php echo $this->get_field_name('title') ?>" type="text" value="<?php echo esc_attr($title) ?>" />
		</p>
		<?php
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
		ob_start();
		print_r($old_instance);
		print_r($new_instance);
		$buff = ob_get_clean();

		file_put_contents(__DIR__.'/text.log', $buff);

		return $new_instance;
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