<?php


namespace BestProject\Wordpress {
	
	defined('ABSPATH') or die;

	use BestProject\Wordpress\Theme;

	/**
	 * Localization class. Takes care of translating strings constants into a proper language.
	 */
	class Language
	{
		/**
		 * Holds all translations and its keys.
		 *
		 * @var	Array
		 */
		protected static $strings;

		/**
		 * Holds current language code (eg. eb_GB)
		 *
		 * @var String
		 */
		protected static $code;

		/**
		 * Name of current theme.
		 * 
		 * @var	String
		 */
		protected static $themeName;

		/**
		 * Load all translations into memmory.
		 */
		protected static function load()
		{
			if (is_null(self::$code)) {
				self::$code = get_locale();
			}

			/* @var $theme Theme */
			$path = get_stylesheet_directory();

			$filename = $path.'/languages/'.self::$code.'.ini';
			if (!file_exists($filename)) {
				$filename = $path.'/languages/en_GB.ini';
			}

			if (file_exists($filename)) {
				self::$strings = parse_ini_file($filename);
			}
		}

		/**
		 * Returns a translated version of provided string. If more then 1 attribute is provided `sprintf` will be used.
		 * 
		 * @param	String	$string	Key that represents the translation.
		 * @return	String
		 */
		public static function _($string)
		{
			if (is_null(self::$strings)) {
				self::load();
			}

			if (is_null(self::$themeName)) {
				self::$themeName = Theme::getName();
			}

			if (func_num_args() === 1) {
				if (isset(self::$strings[$string])) {
					return self::$strings[$string];
				} else {
					return __($string, self::$themeName);
				}
			} else {
				if (isset(self::$strings[$string])) {
					$args = func_get_args();
					array_shift($args);
					array_unshift($args, self::$strings[$string]);

					return call_user_func_array('sprintf', $args);
				} else {
					$args	 = func_get_args();
					$args	 = array_push($args, self::$themeName);

					return call_user_func_array('__', $args);
				}
			}
		}
	}
}

namespace {

	/**
	 * Translating text through \BestProject\Wordpress\Language with fallback to original
	 * "_" function. Just something to be used as a shortcut in templates.
	 * If more then 1 attribute is provided `sprintf` will be used.
	 *
	 * @return String
	 */
	function t($string)
	{
		if (!is_null($string)) {
			return call_user_func_array('BestProject\Wordpress\Language::_',
				func_get_args());
		}
	}
}

