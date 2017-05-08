<?php

namespace BestProject\Wordpress\Theme;

use BestProject\Wordpress\Language;

/**
 * This class takes care of sidebars.
 */
class Sidebar
{
	/**
	 * ID of this sidebar.
	 * @var	String
	 */
	protected $id;

	/**
	 * Title of this sidebar.
	 * @var	String
	 */
	protected $name;

	/**
	 * Description of this sidebar.
	 * @var String
	 */
	protected $description;

	/**
	 * Header tag for each sidebar item.
	 * @var String
	 */
	protected $header_tag;

	/**
	 * Container tag for each sidebar item.
	 * @var String
	 */
	protected $item_tag;

	/**
	 * Class for each item container.
	 * @var String
	 */
	protected $item_class;

	/**
	 * Returns the instance of Sidebar object.
	 *
	 * @param	String	$id				ID of this sidebar.
	 * @param	String	$name			Title of this sidebar.
	 * @param	String	$description	Description of this sidebar.
	 * @param	String	$header_tag		Header tag for each sidebar item.
	 * @param	String	$item_tag		Container tag for each sidebar item.
	 * @param	String	$item_class		Class for each item container.
	 */
	public function __construct($id, $name, $description = '', $header_tag = 'h2',
							 $item_tag = 'div', $item_class = 'sidebar')
	{
		$this->id			 = $id;
		$this->name			 = $name;
		$this->description	 = $description;
		$this->header_tag	 = $header_tag;
		$this->item_tag		 = $item_tag;
		$this->item_class	 = $item_class;
	}

	/**
	 * Renders a single custom sidebar.
	 *
	 * @param	String	$name	Name of a sidebar to render.
	 */
	public static function render($name)
	{
		dynamic_sidebar($name);
	}

	/**
	 * Registers a single sidebar.
	 */
	public function register()
	{
		add_action('widgets_init', [$this, 'doRegister']);
	}

	/**
	 * Register sidebar action used for add_action function.
	 */
	public function doRegister()
	{
		register_sidebar(array(
			'name' => Language::_($this->name),
			'id' => $this->id,
			'description' => Language::_($this->description),
			'before_widget' => '<'.$this->item_tag.' class="widget '.$this->item_class.'">',
			'after_widget' => '</'.$this->item_tag.'>',
			'before_title' => '<'.$this->header_tag.' class="widget-heading">',
			'after_title' => '</'.$this->header_tag.'>',
		));
	}

	/**
	 * Sets a given property and returns current object for chaining.
	 *
	 * @param	String		$property
	 * @param	Mixed		$value
	 * @return	Arguments
	 */
	public function &set($property, $value = true)
	{
		$this->$property = $value;
		return $this;
	}
}