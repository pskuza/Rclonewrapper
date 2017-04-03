# Rclonewrapper [![Build Status](https://travis-ci.org/Cr0nixx/Rclonewrapper.svg?branch=master)](https://travis-ci.org/Cr0nixx/Rclonewrapper)
Simple wrapper to use rclone in your PHP projects.

## Install

``` sh
php composer.phar require "cr0nixx/rclonewrapper"
```

You need a valid rclone.conf

### Basic usage and what works
``` php
<?php

require('vendor/autoload.php');

use Rclonewrapper\Rclonewrapper;

// Binary and config location
$rclone = new Rclonewrapper('./rclone', 'rclone.conf');

var_dump($rclone->version());
// string(12) "rclone v1.36"
// https://rclone.org/commands/rclone_version/

var_dump($rclone->listremotes());
// array(1) {[0]=>string(12) "Dropbox:"}
// or however many are defined in the rclone.conf
// https://rclone.org/commands/rclone_listremotes/

var_dump($rclone->setremote('Dropbox:'));
// bool (true) on success, false on failure

var_dump($rclone->mkdir('/test'));
// bool (true) on success, false on failure
// https://rclone.org/commands/rclone_mkdir/

var_dump($rclone->rmdir('/test'));
// bool (true) on success, false on failure
// https://rclone.org/commands/rclone_rmdir/

var_dump($rclone->copy('afile.dat', '/test'));
// bool (true) on success, false on failure
// https://rclone.org/commands/rclone_copy/

var_dump($rclone->purge('/test'));
// bool (true) on success, false on failure
// https://rclone.org/commands/rclone_purge/
```

### What does not work

Everything else