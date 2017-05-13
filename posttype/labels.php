<?php

namespace BestProject\Wordpress\PostType;

defined('ABSPATH') or die;

use BestProject\Wordpress\Language;

/**
 * This class takes care of passing custom post type labels.
 */
class Labels
{
	/**
	 * General name for the post type, usually plural. The same and overridden by $post_type_object->label. Default is Posts/Pages
	 * @var String
	 */
	public $name;

	/**
	 * Name for one object of this post type. Default is Post/Page
	 * @var String
	 */
	public $singular_name;

	/**
	 * The add new text. The default is "Add New" for both hierarchical and non-hierarchical post types. When internationalizing this string, please use a gettext context matching your post type. Example: _x('Add New', 'product');
	 * @var String
	 */
	public $add_new;

	/**
	 * Default is Add New Post/Add New Page.
	 * @var String
	 */
	public $add_new_item;

	/**
	 * Default is Edit Post/Edit Page.
	 * @var String
	 */
	public $edit_item;

	/**
	 * Default is New Post/New Page.
	 * @var String
	 */
	public $new_item;

	/**
	 * Default is View Post/View Page.
	 * @var String
	 */
	public $view_item;

	/**
	 * Label for viewing post type archives. Default is 'View Posts' / 'View Pages'.
	 * @var String
	 */
	public $view_items;

	/**
	 * Default is Search Posts/Search Pages.
	 * @var String
	 */
	public $search_items;

	/**
	 * Default is No posts found/No pages found.
	 * @var String
	 */
	public $not_found;

	/**
	 * Default is No posts found in Trash/No pages found in Trash.
	 * @var String
	 */
	public $not_found_in_trash;

	/**
	 * This string isn't used on non-hierarchical types. In hierarchical ones the default is 'Parent Page:'.
	 * @var String
	 */
	public $parent_item_colon;

	/**
	 * String for the submenu. Default is All Posts/All Pages.
	 * @var String
	 */
	public $all_items;

	/**
	 * String for use with archives in nav menus. Default is Post Archives/Page Archives.
	 * @var String
	 */
	public $archives;

	/**
	 * Label for the attributes meta box. Default is 'Post Attributes' / 'Page Attributes'.
	 * @var String
	 */
	public $attributes;

	/**
	 * String for the media frame button. Default is Insert into post/Insert into page.
	 * @var String
	 */
	public $insert_into_item;

	/**
	 * String for the media frame filter. Default is Uploaded to this post/Uploaded to this page.
	 * @var String
	 */
	public $uploaded_to_this_item;

	/**
	 * Default is Featured Image.
	 * @var String
	 */
	public $featured_image;

	/**
	 * Default is Set featured image.
	 * @var String 
	 */
	public $set_featured_image;

	/**
	 * Default is Remove featured image.
	 * @var String
	 */
	public $remove_featured_image;

	/**
	 * Default is Use as featured image.
	 * @var String
	 */
	public $use_featured_image;

	/**
	 * Default is the same as `name`.
	 * @var String
	 */
	public $menu_name;

	/**
	 * String for the table views hidden heading.
	 * @var String
	 */
	public $filter_items_list;

	/**
	 * String for the table pagination hidden heading.
	 * @var String
	 */
	public $items_list_navigation;

	/**
	 * String for the table hidden heading.
	 * @var String
	 */
	public $items_list;

	/**
	 * String for use in New in Admin menu bar. Default is the same as `singular_name`.
	 * @var String
	 */
	public $name_admin_bar;

	public function __construct(
		$name, $singular_name, $add_new = null, $add_new_item = null,
		$edit_item = null, $new_item = null, $view_item = null, $view_items = null,
		$search_items = null, $not_found = null, $not_found_in_trash = null,
		$parent_item_colon = null, $all_items = null, $archives = null,
		$attributes = null, $insert_into_item = null, $uploaded_to_this_item = null,
		$featured_image = null, $set_featured_image = null,
		$remove_featured_image = null, $use_featured_image = null, $menu_name = null,
		$filter_items_list = null, $items_list_navigation = null, $items_list = null,
		$name_admin_bar = null
	)
	{
		$this->name = Language::_($name);
		$this->singular_name = Language::_($singular_name);
		$this->add_new = Language::_($add_new);
		$this->add_new_item = Language::_($add_new_item);
		$this->edit_item = Language::_($edit_item);
		$this->new_item = Language::_($new_item);
		$this->view_item = Language::_($view_item);
		$this->view_items = Language::_($view_items);
		$this->search_items = Language::_($search_items);
		$this->not_found = Language::_($not_found);
		$this->not_found_in_trash = Language::_($not_found_in_trash);
		$this->parent_item_colon = Language::_($parent_item_colon);
		$this->all_items = Language::_($all_items);
		$this->archives = Language::_($archives);
		$this->attributes = Language::_($attributes);
		$this->insert_into_item = Language::_($insert_into_item);
		$this->uploaded_to_this_item = Language::_($uploaded_to_this_item);
		$this->featured_image = Language::_($featured_image);
		$this->set_featured_image = Language::_($set_featured_image);
		$this->remove_featured_image = Language::_($remove_featured_image);
		$this->use_featured_image = Language::_($use_featured_image);
		$this->menu_name = Language::_($menu_name);
		$this->filter_items_list = Language::_($filter_items_list);
		$this->items_list_navigation = Language::_($items_list_navigation);
		$this->items_list = Language::_($items_list);
		$this->name_admin_bar = Language::_($name_admin_bar);
	}

	/**
	 * Converts this object to array. Note: All NULL entries will be removed.
	 *
	 * @return Array
	 */
	public function toArray() {
		$properties = (array)$this;
		return array_filter($properties);
	}

	/**
	 * Sets a given property and returns current object for chaining.
	 *
	 * @param	String		$property
	 * @param	Mixed		$value
	 * @return	Arguments
	 */
	public function &set($property, $value = true) {
		$this->$property = Language::_($value);
		return $this;
	}

}