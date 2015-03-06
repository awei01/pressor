# Pressor Must Use Plugin #

This package is part of the Pressor framework which loads Wordpress as a composer dependency in the context of a Laravel appliction.

This module serves as the composer-installable Wordpress must-use plugin which will load and boot the Pressor framework.

## Installation ##

This module is designed to be use with `pressor/framework`.

* Edit your `composer.json` to require the module and determine where to install it:

```
	...
    "require": {
        "pressor/framework": "dev-master",
        "pressor/mu-plugin": "dev-master"
    },
    "extra":{
        "installer-paths": {
           "app/wp-content/mu-plugins/{$name}": ["type:wordpress-muplugin"]
        }
    },

```

* In your `wordpress/mu-plugins` create or edit a `load.php` to look like this:

```
<?php

require __DIR__ . '/pressor/start.php';

// require other plugins here
?>
```
