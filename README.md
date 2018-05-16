# Rclonewrapper [![Build Status](https://travis-ci.org/pskuza/Rclonewrapper.svg?branch=dev)](https://travis-ci.org/pskuza/Rclonewrapper) [![StyleCI](https://styleci.io/repos/86995737/shield?branch=dev)](https://styleci.io/repos/86995737)

Simple wrapper to use [rclone](https://rclone.org) in your PHP projects.

## Install

``` sh
php composer.phar require "pskuza/rclonewrapper"
```

You need a valid rclone.conf

### Basic usage and what works
``` php
<?php

require('vendor/autoload.php');

use pskuza\Rclonewrapper;

// Binary and config location
$rclone = new Rclonewrapper('./rclone', 'rclone.conf');

```

### What does not work

Everything else ...
