# Wordpress Framework
A set of classes to build Wordpress websites in more humane way.

## Installation

Put all contents from this release into `/vendor/bestproject/wordpress-framework` directory inside your template.
Then load the ``_autoload.php`` in your ``functions.php`` like this.

    require_once __DIR__.'/vendor/bestproject/wordpress-framework/_autoload.php';
    
## Theme
This class is used to register basic info about theme like features and sidebars.

## Sidebar
This class takes care of registering each of the custom sidebars and is passed to the `Theme` construct method.

## PostType
This class is used to create custom post types it accepts `Labels`, `Arguments` and `Field`/`MetaBox` instances.

## MetaBox
This class is passed to the PostType `constructor` and declares a single MetaBox. It accepts a set of `Field` instances.

## Widget
This class takes care of creating custom widgets with separated view.