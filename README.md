# Wordpress Framework
A set of classes to build Wordpress websites in more humane way. This framework 
allows you to create Wordpress themes, widgets, sidebars and forms without knowing 
all those funcy functions.

## Installation

Put all contents from this release into `/vendor/bestproject/wordpress-framework` directory inside your template.
Then load the ``_autoload.php`` in your ``functions.php`` like this.

    require_once __DIR__.'/vendor/bestproject/wordpress-framework/_autoload.php';
    
## Theme
This class is used to register basic info about theme like features and sidebars.

## Sidebar
This class takes care of registering each of the custom sidebars and is passed to the `Theme` construct method.

## PostType
This class is used to create custom post types it accepts `Labels`, `Arguments` and array of `Field`/`MetaBox` instances.

### Sample usage
```php
/**
* Register Slide post type
*/
new PostType(

    // Post type
    'slides',

    // UI labels
    (new Labels('SLIDES', 'SLIDE'))->set('add_new_item','HEADER_ADD_SLIDE')->set('add_new','HEADER_ADD_SLIDE'),

    // Post type arguments
    (new Arguments())->set('public')->
        set('capability_type', 'post')->
        set('supports', array('title', 'excerpt', 'thumbnail'))->
        set('menu_icon', 'dashicons-images-alt2')->
        set('menu_position', 3),

    // Array of fields cause we dont need multiple MetaBoxes
    array(
        new Field\Text('button_title'),
        new Field\Url('url'),
    )
);
```
## MetaBox
This class is passed to the PostType `constructor` and declares a single MetaBox. It accepts an array of `Field` instances.

### Sample usage with new Post Type
```php
/**
* Register Slide post type
*/
new PostType(

    // Post type
    'slides',

    // UI labels
    (new Labels('SLIDES', 'SLIDE'))->set('add_new_item','HEADER_ADD_SLIDE')->set('add_new','HEADER_ADD_SLIDE'),

    // Post type arguments
    (new Arguments())->set('public')->
        set('capability_type', 'post')->
        set('supports', array('title', 'excerpt', 'thumbnail'))->
        set('menu_icon', 'dashicons-images-alt2')->
        set('menu_position', 3),

    // Array of MetaBoxes
    array(
        new MetaBox('animations',array(
            new Field\Text('animation_name'),
            new Field\Text('animation_time'),
        )),
        new MetaBox('button',array(
            new Field\Text('button_title'),
            new Field\Url('button_url'),
        ))
    ) 
);
```

## Widget
This class takes care of creating custom widgets with separated view. 
The front-end HTML for your widget should be placedin `/wp-content/themes/YOURTHEME/template-parts/widgets/YOURWIDGETNAME.php`.

### Sample usage
```php
<?php
defined('ABSPATH') or die;

use BestProject\Wordpress\Widget,
    BestProject\Wordpress\Form\Field;

/**
 * Contact field widget
 */
class Contact extends Widget
{

    /**
     * Widget Backend
     */
    public function getFields()
    {
        return array(
            (new Field\Text('title')),
            (new Field\Text('company')),
            (new Field\Textarea('address')),
            (new Field\Textarea('phones')),
            (new Field\Email('email')),
        );
    }
}
```

## Form
Creating and sending from forms.