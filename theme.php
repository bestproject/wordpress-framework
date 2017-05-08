<?php

namespace BestProject\Wordpress;

use BestProject\Wordpress\Sidebar;
use BestProject\Wordpress\Language;

class Theme
{
	/**
	 * Name of this Theme
	 *
	 * @var	String
	 */
	protected $name;

	/**
	 * Base URI for this theme directory.
	 *
	 * @var String
	 */
	protected $path;

	/**
	 * Example features: post-formats, post-thumbnails, custom-background, custom-header, custom-logo, automatic-feed-links, html5, title-tag, customize-selective-refresh-widgets
	 * Documentation: https://developer.wordpress.org/reference/functions/add_theme_support/#post-formats
	 * @var Array
	 */
	protected $features;

	/**
	 * This variable holds Theme instance and is used by other classes to avoid recreating object.
	 * @var Theme
	 */
	protected static $instance;

	/**
	 *
	 * @param	Array	$sidebars	Array of supported sidebars (string or Sidebar class instances).
	 * @param	Array	$features	Array of features supported by this theme (https://developer.wordpress.org/reference/functions/add_theme_support/#post-formats)
	 */
	public function __construct($sidebars = array(), Array $features = array())
	{
		$this->path		 = get_stylesheet_directory();
		$this->name		 = basename($this->path);
		$this->features	 = $features;

		$this->registerSidebars($sidebars);
		$this->registerFeatures();

		self::$instance = $this;
	}

	/**
	 * Returns an Theme object instance if there was any created.
	 *
	 * @return instanceof Theme
	 *
	 * @throws Exception
	 */
	public static function &getInstance()
	{
		if (self::$instance instanceof Theme) {
			return self::$instance;
		} else {
			throw new Exception('Currently there is no instance of `Theme` class. Please create it, then use getInstance() method.');
		}
	}

	/**
	 *
	 * @param	String	$name		Name of the property to return.
	 * @param	Mixed	$default	Default property
	 * @return	Mixed
	 */
	public function get($name, $default = null)
	{
		if (isset($this->$name)) {
			return $this->$name;
		}

		return $default;
	}

	/**
	 * Declares sidebars for the theme.
	 *
	 * @param	Array	$sidebars	An array of BestProject\Wordpress\Theme\Sidebar instances.
	 */
	protected function registerSidebars(array $sidebars)
	{
		if (!empty($sidebars)) {
			foreach ($sidebars AS $sidebar) {
				if ($sidebar instanceof Sidebar) {
					$sidebar->register();
				} elseif (is_string($sidebar)) {
					(new Sidebar($sidebar, Language::_($sidebar)))->register();
				}
			}
		}
	}

	/**
	 * Return TRUE when chosen sidebar has widgets.
	 *
	 * @param	String	$name	Name of the sidebar to check
	 * 
	 * @return	Boolean
	 */
	public static function hasSidebar($name)
	{
		return (bool) is_active_sidebar($name);
	}

	/**
	 * Registers Theme features
	 */
	protected function registerFeatures()
	{
//		var_dump($this->features);
//		die;
	}
}