<?php

namespace BestProject\Wordpress\PostType;

use BestProject\Wordpress\Theme;

/**
 * This class takes care of passing custom post type arguments.
 */
class Arguments
{
	/**
	 * Controls how the type is visible to authors and readers.
	 * 'true' - Implies exclude_from_search: false, publicly_queryable: true, show_in_nav_menus: true, and show_ui:true. The built-in types attachment, page, and post are similar to this.
	 * 'false' - Implies exclude_from_search: true, publicly_queryable: false, show_in_nav_menus: false, and show_ui: false. The built-in types nav_menu_item and revision are similar to this. Best used if you'll provide your own editing and viewing interfaces (or none at all).
	 * @var Boolean
	 */
	public $public;

	/**
	 * Whether to exclude posts with this post type from front end search results.
	 * @var Boolean
	 */
	public $exclude_from_search;

	/**
	 * Whether queries can be performed on the front end as part of parse_request().
	 * @var Boolean
	 */
	public $publicly_queryable;

	/**
	 * Whether to generate a default UI for managing this post type in the admin.
	 * @var Boolean
	 */
	public $show_ui;

	/**
	 * Whether post_type is available for selection in navigation menus. 
	 * @var Boolean
	 */
	public $show_in_nav_menus;

	/**
	 * Where to show the post type in the admin menu. show_ui must be true. 
	 * @var Boolean
	 */
	public $show_in_menu;

	/**
	 * Whether to make this post type available in the WordPress admin bar. 
	 * @var Boolean|String
	 */
	public $show_in_admin_bar;

	/**
	 * The position in the menu order the post type should appear. show_in_menu must be true. 
	 * @var Integer
	 */
	public $menu_position;

	/**
	 * The url to the icon to be used for this menu or the name of the icon from the iconfont (https://developer.wordpress.org/resource/dashicons/#carrot).
	 * @var String
	 */
	public $menu_icon;

	/**
	 * The string to use to build the read, edit, and delete capabilities. May be passed as an array to allow for alternative plurals when using this argument as a base to construct the capabilities, e.g. array('story', 'stories') the first array element will be used for the singular capabilities and the second array element for the plural capabilities, this is instead of the auto generated version if no array is given which would be "storys". The 'capability_type' parameter is used as a base to construct capabilities unless they are explicitly set with the 'capabilities' parameter. It seems that `map_meta_cap` needs to be set to false or null, to make this work.
	 * @var Array|String
	 */
	public $capability_type;

	/**
	 * An array of the capabilities for this post type. 
	 * @var Array
	 */
	public $capabilities;

	/**
	 * Whether to use the internal default meta capability handling. 
	 * @var Boolean
	 */
	public $map_meta_cap;

	/**
	 * Whether the post type is hierarchical (e.g. page). Allows Parent to be specified. The 'supports' parameter should contain 'page-attributes' to show the parent select box on the editor page.
	 * Note: this parameter was intended for Pages. Be careful when choosing it for your custom post type - if you are planning to have very many entries (say - over 2-3 thousand), you will run into load time issues. With this parameter set to true WordPress will fetch all IDs of that particular post type on each administration page load for your post type. Servers with limited memory resources may also be challenged by this parameter being set to true.
	 * @var Boolean
	 */
	public $hierarchical;

	/**
	 * An alias for calling add_post_type_support() directly. As of 3.5, boolean false can be passed as value instead of an array to prevent default (title and editor) behavior.
	 * Default: title and editor
	 * Options: title, editor, author,thumbnail, excerpt, trackbacks, custom-fields, comments, revisions, page-attributes, post-formats
	 * @var Array
	 */
	public $supports;

	/**
	 * Provide a callback function that will be called when setting up the meta boxes for the edit form. The callback function takes one argument $post, which contains the WP_Post object for the currently edited post. Do remove_meta_box() and add_meta_box() calls in the callback.
	 * @var Callback
	 */
	public $register_meta_box_cb;

	/**
	 * An array of registered taxonomies like category or post_tag that will be used with this post type. This can be used in lieu of calling register_taxonomy_for_object_type() directly. Custom taxonomies still need to be registered with register_taxonomy().
	 * @var Array
	 */
	public $taxonomies;

	/**
	 * Enables post type archives. Will use $post_type as archive slug by default.
	 * Default: false
	 * @var Boolean|String
	 */
	public $has_archive;

	/**
	 * Triggers the handling of rewrites for this post type. To prevent rewrites, set to false.
	 * Default: true and use $post_type as slug
	 * $args array
	 * 'slug' => string Customize the permalink structure slug. Defaults to the $post_type value. Should be translatable.
	 * 'with_front' => bool Should the permalink structure be prepended with the front base. (example: if your permalink structure is /blog/, then your links will be: false->/news/, true->/blog/news/). Defaults to true
	 * 'feeds' => bool Should a feed permalink structure be built for this post type. Defaults to has_archive value.
	 * 'pages' => bool Should the permalink structure provide for pagination. Defaults to true
	 * 'ep_mask' => const As of 3.4 Assign an endpoint mask for this post type. For more info see Rewrite API/add_rewrite_endpoint, and Make WordPress Plugins summary of endpoints.
	 * @var Boolean|Array
	 */
	public $rewrite;

	/**
	 * The default rewrite endpoint bitmasks. For more info see Trac Ticket 12605 and this - Make WordPress Plugins summary of endpoints. 
	 * @var String
	 */
	public $permalink_epmask;

	/**
	 * Sets the query_var key for this post type.
	 * Default: true - set to $post_type
	 * @var Boolean|String
	 */
	public $query_var;

	/**
	 * Can this post_type be exported.
	 * @var Boolean
	 */
	public $can_export;

	/**
	 * Whether to delete posts of this type when deleting a user. If true, posts of this type belonging to the user will be moved to trash when then user is deleted. If false, posts of this type belonging to the user will not be trashed or deleted. If not set (the default), posts are trashed if post_type_supports('author'). Otherwise posts are not trashed or deleted.
	 * @var Boolean
	 */
	public $delete_with_user;

	/**
	 * Whether to expose this post type in the REST API. 
	 * @var Boolean
	 */
	public $show_in_rest;

	/**
	 * The base slug that this post type will use when accessed using the REST API.
	 * Default: $post_type
	 * @var String
	 */
	public $rest_base;

	/**
	 *
	 * @var String
	 */
	public $rest_controller_class;

	public function __construct(
		$public = null, $exclude_from_search = null, $publicly_queryable = null,
		$show_ui = null, $show_in_nav_menus = null, $show_in_menu = null,
		$show_in_admin_bar = null, $menu_position = null, $menu_icon = null,
		$capability_type = null, $capabilities = null, $map_meta_cap = null,
		$hierarchical = null, $supports = null, $register_meta_box_cb = null,
		$taxonomies = null, $has_archive = null, $rewrite = null,
		$permalink_epmask = null, $query_var = null, $can_export = null,
		$delete_with_user = null, $show_in_rest = null, $rest_base = null,
		$rest_controller_class = null
	)
	{
		$this->public				 = $public;
		$this->exclude_from_search	 = $exclude_from_search;
		$this->publicly_queryable	 = $publicly_queryable;
		$this->show_ui				 = $show_ui;
		$this->show_in_nav_menus	 = $show_in_nav_menus;
		$this->show_in_menu			 = $show_in_menu;
		$this->show_in_admin_bar	 = $show_in_admin_bar;
		$this->menu_position		 = $menu_position;
		$this->menu_icon			 = $menu_icon;
		$this->capability_type		 = $capability_type;
		$this->capabilities			 = $capabilities;
		$this->map_meta_cap			 = $map_meta_cap;
		$this->hierarchical			 = $hierarchical;
		$this->supports				 = $supports;
		$this->register_meta_box_cb	 = $register_meta_box_cb;
		$this->taxonomies			 = $taxonomies;
		$this->has_archive			 = $has_archive;
		$this->rewrite				 = $rewrite;
		$this->permalink_epmask		 = $permalink_epmask;
		$this->query_var			 = $query_var;
		$this->can_export			 = $can_export;
		$this->delete_with_user		 = $delete_with_user;
		$this->show_in_rest			 = $show_in_rest;
		$this->rest_base			 = $rest_base;
		$this->rest_controller_class = $rest_controller_class;
	}

	/**
	 * Converts this object to array. Note: All NULL entries will be removed.
	 *
	 * @return Array
	 */
	public function toArray()
	{
		$properties = (array) $this;
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
		$this->$property = $value;
		return $this;
	}
}