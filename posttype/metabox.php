<?php

namespace BestProject\Wordpress\PostType;

defined('ABSPATH') or die;

use BestProject\Wordpress\Language;

/**
 * MetaBox class.
 */
class MetaBox
{
	/**
	 * Name of this metabox.
	 * @var String
	 */
	protected $name;

	/**
	 * Label of this metabox.
	 * @var String
	 */
	protected $label;

	/**
	 * Fields to be included in this metabox.
	 * @var Array
	 */
	protected $fields;

	/**
	 * The context within the screen where the field should display (normal|side|advanced)
	 * Default: normal
	 * @var String
	 */
	protected $context;

	/**
	 * Post type that should hold this meta box.
	 * @var String
	 */
	protected $post_type;

	/**
	 * Creates MetaBox instance.
	 *
	 * @param	String	$name		Name of this metabox.
	 * @param	String	$label		Label of this metabox.
	 * @param	Array	$fields		Fields to be included in this metabox.
	 * @param	String	$context	The context within the screen where the field should display (normal|side|advanced).
	 */
	public function __construct($name, $label, Array $fields, $context = 'normal')
	{
		$this->name		 = $name;
		$this->label	 = Language::_($label);
		$this->fields	 = $fields;
		$this->context	 = $context;
	}

	/**
	 * This is an internal function to register a metabox.
	 * @param	String	$post_type	Post type to register this field too.
	 */
	public function register($post_type)
	{
		$this->post_type = $post_type;

		add_action("admin_init", [$this, 'doRegister']);
		add_action('save_post_'.$post_type, [$this, 'save'], 10, 3);
	}

	/**
	 * Actual metabox register method used by add_action in admin_init event.
	 */
	public function doRegister()
	{

		add_meta_box($this->name.'-meta', $this->label, [$this, 'render'],
			$this->post_type, $this->context, "low");
	}

	/**
	 * Method that renders this metabox.
	 */
	public function render()
	{
		$html = '';

		/* @var $field Field */
		foreach ($this->fields AS $field) {
			// Make sure field will display in WP styl
			$field->set('class','widefat');

			// Render field
			$html .= $field->render();
		}

		ob_start();
		wp_nonce_field(basename(__FILE__), 'custom_post_type_nonce');

		$html .= ob_get_clean();

		echo $html;
	}

	/**
	 * Mathod that takes care of saving fields data into database.
	 * 
	 * @param	integer	$post_id	ID of current post
	 * @param	WP_Post	$post		Object of current post.
	 * @param	Boolean	$updated	Was this post just updated?
	 * @return	Bool
	 */
	public function save($post_id, \WP_Post $post, $updated)
	{
		$nonce = filter_input(INPUT_POST, 'custom_post_type_nonce');

		// Verify the nonce before proceeding.
		if (is_null($nonce) || !wp_verify_nonce($nonce, basename(__FILE__))) {
			return $post_id;
		}

		// Get the post type object.
		$post_type = get_post_type_object($post->post_type);

		// Check if the current user has permission to edit the post.
		if (!current_user_can($post_type->cap->edit_post, $post_id)) {
			return $post_id;
		}

		// Updating/Adding/Deleting this MetaBox fields data.
		foreach ($this->fields AS $field) {

			// Get new and old value of this field
			$meta_value		 = get_post_meta($post_id, $field->get('name'), true);
			$new_meta_value	 = $field->getSaveData();

			// Should this data be updated?
			if (!add_post_meta($post_id, $field->get('name'), $new_meta_value, true) AND ! empty($new_meta_value)) {
				update_post_meta($post_id, $field->get('name'), $new_meta_value);

			// Should this data be deleted
			} elseif (empty($new_meta_value) && $meta_value) {
				delete_post_meta($post_id, $field->get('name'), $meta_value);
			}
		}
	}
}