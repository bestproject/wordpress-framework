<?php

namespace BestProject\Wordpress;

use BestProject\Wordpress\PostType\Labels;
use BestProject\Wordpress\PostType\Arguments;
use BestProject\Wordpress\PostType\MetaBox;

/**
 * This class takes care of creating new custom post type.
 */
class PostType
{
	/**
	 * Name of this post type.
	 *
	 * @var String
	 */
	protected $name;

	/**
	 * The labels instance used for holding basic labels and descriptions of post type.
	 *
	 * @var	Labels
	 */
	protected $labels;

	/**
	 * The Arguments instance that holds all the arguments for this custom post type.
	 * 
	 * @var	Arguments
	 */
	protected $arguments;

	/**
	 * Array of Field or MetaBox instances.
	 *
	 * @var	Array
	 */
	protected $fields;

	/**
	 * Creates and registers new Post Type (on object construct).
	 *
	 * @param	String		$name		Name of this post type.
	 * @param	Labels		$labels		The labels instance used for holding basic labels and descriptions of post type.
	 * @param	Arguments	$arguments	The Arguments instance that holds all the arguments for this custom post type.
	 * @param	Array		$fields		Array of Field or MetaBox instances.
	 */
	public function __construct($name, Labels $labels, Arguments $arguments,
							 Array $fields)
	{
		$this->name			 = $name;
		$this->labels		 = $labels->toArray();
		$arguments->labels	 = $this->labels;
		$this->arguments	 = $arguments->toArray();
		$this->fields		 = $fields;

		$this->register();
		$this->registerFields();
	}

	/**
	 * Method used to register post type.
	 */
	protected function register()
	{
		add_action('init', [$this, 'doRegister']);
	}

	/**
	 * This method does the actual registering of post type.
	 */
	public function doRegister()
	{
		register_post_type($this->name, $this->arguments);

		// There is a thumbnail field, make sure that theme will mark this as supported.
		if (isset($this->arguments['supports']) AND in_array('thumbnail',
				$this->arguments['supports'])) {
			add_theme_support('post-thumbnails');
			add_image_size('featured_preview', 55, 55, true);

		}
	}

	/**
	 * Register all the fields/metaboxes.
	 */
	protected function registerFields()
	{

		// Developer provided MetaBoxes array.
		if ($this->fields[0] instanceof MetaBox) {

			/* @var $metabox MetaBox */
			foreach ($this->fields AS $metabox) {
				$metabox->register($this->name);
			}

		// Developer provided Field array so create new metabox and register it.
		} else {

			$metabox = new MetaBox('advanced', 'METABOX_ADVANCED', $this->fields);
			$metabox->register($this->name);
		}
	}
}