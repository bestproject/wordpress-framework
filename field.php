<?php

namespace BestProject\Wordpress;

use BestProject\Wordpress\FieldInterface;
use BestProject\Wordpress\Language;

abstract class Field implements FieldInterface
{
	/**
	 * Name of of this field.
	 * @var String
	 */
	protected $name;

	/**
	 * Label of this field.
	 * @var String
	 */
	protected $label;

	/**
	 * Description of this field.
	 * @var String
	 */
	protected $description;

	/**
	 * Hint for this field.
	 * @var String
	 */
	protected $hint;

	/**
	 * ID of this field.
	 * @var String
	 */
	protected $id;

	/**
	 * Is this field is required?
	 * @var String
	 */
	protected $required;

	/**
	 * Default Value for this field.
	 * @var String
	 */
	protected $value;

	/**
	 * Should this field be displayed as column of this post type list?
	 * @var Boolean
	 */
	protected $display_in_list;

	/**
	 * Class of a field label.
	 *
	 * @var String
	 */
	protected $label_class;

	/**
	 * Creates new Field instance.
	 *
	 * @param	String	$name				Name of of this field.
	 * @param	String	$label				Label of this field.
	 * @param	String	$description		Description of this field.
	 * @param	String	$hint				Hint for this field.
	 * @param	String	$id					ID of this field.
	 * @param	Boolean	$required			Is this field is required?
	 * @param	String	$value				Default Value for this field.
	 * @param	Boolean	$display_in_list	Should this field be displayed as column of this post type list?
	 * @param	String	$label_class	Should this field be displayed as column of this post type list?
	 */
	public function __construct($name, $label = '', $description = '', $hint = '',
							 $id = '', $required = false, $value = '', $display_in_list = false,
							 $label_class = '')
	{
		$this->name = $name;
		if (empty($label)) {
			$this->label = Language::_('FIELD_'.strtoupper($name).'_LABEL');
		} else {
			$this->label = Language::_($label);
		}
		$this->description	 = Language::_($description);
		$this->hint			 = Language::_($hint);
		if (empty($id)) {
			$this->id = preg_replace("/[^0-9_a-zA-Z]/", "",
				str_ireplace(array('[', ']'), '_', $name));
		} else {
			$this->id = $id;
		}
		$this->required			 = (bool) $required;
		$this->value			 = $value;
		$this->label_class		 = $label_class;
		$this->display_in_list	 = $display_in_list;
	}

	/**
	 * Get a given property value.
	 *
	 * @param	String	$property	Property name to return
	 * @param	Mixed	$default
	 * @return	Mixed
	 */
	public function get($property, $default = null)
	{
		if (isset($this->$property)) {
			return $this->$property;
		}

		return $default;
	}

	/**
	 * Get this field value from database. If nothing is stored in the database, default will be used.
	 * 
	 * @param	Mixed	$default	Default value to be returned as field value.
	 * @return	String
	 */
	protected function getValue($default)
	{
		$value = get_post_meta(get_the_ID(), $this->name, true);

		return (empty($value) ? $default : $value);
	}

	/**
	 * Sets value for selected property and returns current object instance for chaining.
	 *
	 * @param	String	$property	
	 * @param	Mixed	$value
	 * @return	$this
	 */
	public function set($property, $value = true)
	{
		if (property_exists($this, $property)) {
			$this->$property = $value;
		}

		return $this;
	}

	/**
	 * Display this field.
	 */
	public function render()
	{
		$this->value = $this->getValue($this->value);
		$description = (!empty($this->description) ? ' title="'.$this->description.'"': '');
		$label_class = (!empty($this->label_class) ? ' title="'.$this->label_class.'"': '');
		ob_start();
		?><p class="field"><label for="<?php echo $this->name ?>"<?php echo $description ?><?php echo $label_class ?>><?php echo $this->label ?></label><?php
		?><span class="field-input"><?php echo $this->getInput(); ?></span><?php
		?></p><?php
		return ob_get_clean();
	}

	/**
	 * Get data prior saving. Separate method to allow overriding.
	 * 
	 * @return	String
	 */
	public function getSaveData()
	{
		return filter_input(INPUT_POST, $this->name, FILTER_DEFAULT);
	}
}