<?php

namespace BestProject\Wordpress;

use BestProject\Wordpress\Sidebar,
	BestProject\Wordpress\Language;

defined('ABSPATH') or die;

/**
 * Wordpress Theme class that takes care of all the required functions to build a theme and use custom widgets.
 */
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
	 * Remove selected pages from interface.
	 *
	 * @var	Array
	 */
	protected $remove_pages;

	/**
	 *
	 * @param	Array	$sidebars	Array of supported sidebars (string or Sidebar class instances).
	 * @param	Array	$features	Array of features supported by this theme (https://developer.wordpress.org/reference/functions/add_theme_support/#post-formats)
	 * @param	Array	$remove_pages	Array of pages to remvoe from admin interface.
	 */
	public function __construct($sidebars = array(), Array $features = array(),
							 Array $remove_pages = array())
	{
		$this->path			 = get_stylesheet_directory();
		$this->name			 = basename($this->path);
		$this->features		 = $features;
		$this->remove_pages	 = $remove_pages;

		$this->registerSidebars($sidebars);
		$this->registerFeatures();
		$this->registerWidgets();

		// Default theme behavior for back-end pages
		if (is_admin()) {

			// Remove selected admin pages
			$this->removePages();

			// Add admin stylesheets
			add_action( 'admin_enqueue_scripts', [$this, 'registerFrameworkAdminAssets'] );

		// Theme behavior for front-end
		} else {

			// Remove generator tag
			add_filter('the_generator',function(){
				return '';
			});
		}

		self::$instance = $this;
	}

	/**
	 * Register framework assets. it is called automaticly. You don't have to do it manualy.
	 */
	public function registerFrameworkAdminAssets() {
		wp_register_style( 'bp_wordpress_framework', get_template_directory_uri().'/vendor/bestproject/wordpress-framework/assets/admin.min.css', false, bloginfo('version') );
		wp_enqueue_style( 'bp_wordpress_framework' );
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
		$className = __CLASS__;
		if (self::$instance instanceof $className) {
			return self::$instance;
		} else {
			throw new \Exception('Currently there is no instance of `Theme` class. Please create it, then use getInstance() method.');
		}
	}

	/**
	 *
	 * @param	String	$name		Name of the property to return.
	 * @param	Mixed	$default	Default property
	 * @return	Mixed
	 */
	public static function get($name, $default = null)
	{
		if (isset($this->$name)) {
			return $this->$name;
		}

		return $default;
	}

	/**
	 * Return this theme name.
	 * @return	String
	 */
	public static function getName()
	{
		return basename(get_stylesheet_directory());
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
				$className = 'BestProject\\Wordpress\\Theme\\Sidebar'; //$className
				if ($sidebar instanceof \BestProject\Wordpress\Theme\Sidebar) {
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
		// TODO: Register features.
	}

	/**
	 * Register all the widgets in this framework.
	 */
	protected function registerWidgets()
	{

		// Scan framework directory for widgets
		$widgets = glob(__DIR__.'/widget/*', GLOB_ONLYDIR);

		foreach ($widgets AS $widget) {
			$name	 = basename($widget);
			$path	 = $widget.'/'.$name.'.php';

			// If there is a widget declaration file
			if (file_exists($path)) {

				// Include the widget class
				require_once $path;

				// Register widget
				$className = '\\BestProject\\Wordpress\\Widget\\'.ucfirst($name);
				call_user_func(array($className, 'register'), $className);
			}
		}

		// Scan template directory for widgets
		$template_widgets_path = dirname(dirname(dirname(__DIR__))).'/widgets';
		if (is_dir($template_widgets_path)) {
			$widgets = glob($template_widgets_path.'/*');

			foreach ($widgets AS $widget_path) {

				$name = pathinfo($widget_path, PATHINFO_FILENAME);

				// Include the widget class
				require_once $widget_path;

				// Register widget
				$className = '\\'.ucfirst($name);
				call_user_func(array($className, 'register'), $className);
			}
		}
	}

	/**
	 * Add hook to remove selected pages from admin interface.
	 */
	public function removePages()
	{
		add_action('admin_menu', [$this, 'removePagesAction']);
	}

	/**
	 * Method to remove selected pages from admin interface.
	 */
	public function removePagesAction()
	{
		foreach ($this->remove_pages AS $page) {
			remove_menu_page($page);
		}
	}
}