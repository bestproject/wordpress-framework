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
It should be created before any other classes.

### Creating sample theme instance

```php
/**
 * Register new Theme
 */
$theme = new Theme(

    // Declare two sidebars with names menu and about both using H3 as widgets header
    array(
        (new Sidebar('menu'))->set('header_tag','h3'),
        (new Sidebar('about'))->set('header_tag','h3'),
    ),

    // Theme features declaration
    array(
        'post-formats'
    ),

    // Remove selected pages from admin pages
    array (
        'edit.php?post_type=page',// Removing Pages
        'edit.php', // Removing Posts
        'edit-comments.php', // Removing comments
    )
);
```

## Sidebar
This class takes care of registering each of the custom sidebars and is passed to the `Theme` construct method.

### Sample usage
This code declares sidebar called menu that will use H3 as each widgets title tag.
```php
(new Sidebar('menu'))->set('header_tag','h3')
```

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
This class is passed to the PostType `constructor` and declares a single MetaBox. 
It accepts an array of `Field` instances. This post type should be declared in 
your theme `functions.php` file and be placed after createing `Theme` instance.

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
Your custom class widget should be placed in `/wp-content/themes/YOURTHEME/widgets/YOURWIDGETNAME.php`
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

### Sample usage
This sample created a form that accepts NAME and EMAIL as input and sents the input data to selected e-mail.
```php
<?php
use BestProject\Wordpress\Form;
use BestProject\Wordpress\Form\Field;

$form = (new Form(

	// Form name
	'contactform',

	// Form fields
	array(
		(new Field\Text('name'))->
			set('required'),
		(new Field\Email('email'))->
			set('required'),
		(new Field\Submit('submit'))->
			set('value',t('FIELD_SUBMIT_VALUE'))->
			set('class','btn btn-primary')
	)
));

// We want the form to send data to an e-mail
$form->set('recipient', 'yoursenderemail@example.com');// Who should recieve this data
$form->set('mail_sender', 'yoursenderemail@example.com');// Who is the sender of this input
$form->set('mail_sender_name', $form->getField('name')->getSaveData()); // It sents e-mail sender same as input from NAME field.
$form->set('mail_subject', t('WIDGET_CONTACTFORM_MAIL_SUBJECT')); // Set e-mail subject

// Process the data if there is anything to process.
$processing_result = $form->processInput();

// Get a list of form errors (usualy validation errors)
$errors = $form->getErrors();

// Show all the errors
if( !empty($errors) ): ?>
	<?php foreach($errors AS $error): ?>
	<p class="danger"><?php echo $error ?></p>
	<?php endforeach ?>
<?php endif;

// Show success message
if( $processing_result===true ): ?>
	<p class="success"><?php echo t('WIDGET_CONTACT_FORM_SENT_SUCCESS') ?></p>
<?php endif ?>

<!-- Display form -->
<form <?php echo $form->renderFormAttributes() ?>>
	<div class="col-xs-12">
		<?php echo $form->renderField('name') ?>
	</div>
	<div>
		<?php echo $form->renderField('email') ?>
	</div>
</form>
```