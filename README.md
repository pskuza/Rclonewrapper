# Rclonewrapper [![Build Status](https://travis-ci.org/pskuza/Rclonewrapper.svg?branch=master)](https://travis-ci.org/Cr0nixx/Rclonewrapper)
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

use Rclonewrapper\Rclonewrapper;

// Binary and config location
$rclone = new Rclonewrapper('./rclone', 'rclone.conf');

# print rclone version
var_dump($rclone->version());
// string(12) "rclone v1.36"
// https://rclone.org/commands/rclone_version/

# list all available remotes
var_dump($rclone->listremotes());
// array(1) {[0]=>string(8) "Dropbox:"}
// or however many are defined in the rclone.conf
// https://rclone.org/commands/rclone_listremotes/

# set which remote you want to use
var_dump($rclone->setremote('Dropbox:'));
// bool (true) on success, false on failure

# create directory
var_dump($rclone->mkdir('/test'));
// bool (true) on success, false on failure
// https://rclone.org/commands/rclone_mkdir/

# delete empty directory
var_dump($rclone->rmdir('/test'));
// bool (true) on success, false on failure
// https://rclone.org/commands/rclone_rmdir/

# copy a file to a remote directory
var_dump($rclone->copy('afile.dat', '/test'));
// bool (true) on success, false on failure
// https://rclone.org/commands/rclone_copy/

# copy a whole directory to remote directory
var_dump($rclone->copy('some_directory_with_files', '/test'));
// bool (true) on success, false on failure
// https://rclone.org/commands/rclone_copy/

# get object count and size of path
var_dump($rclone->size('/'));
// array(2) {["count"]=>int(4)["size"]=>string(9) "134217724"}
// https://rclone.org/commands/rclone_size/

# get directory and files in path
var_dump($rclone->ls('/test'));
// array you will see how it looks
// https://rclone.org/commands/rclone_ls/

# get directory and files in path with timestamp
var_dump($rclone->lsl('/test'));
// array you will see how it looks
// https://rclone.org/commands/rclone_lsl/

# get directories in path
var_dump($rclone->lsd('/test'));
// array you will see how it looks
// https://rclone.org/commands/rclone_lsd/

# md5sum of file or path
var_dump($rclone->md5sum('/test.file'));
// array you will see how it looks, false on failure
// https://rclone.org/commands/rclone_md5sum/

# sha1sum of file or path
var_dump($rclone->sha1sum('/testdir'));
// array you will see how it looks, false on failure
// https://rclone.org/commands/rclone_sha1sum/

# delete a directory with files
var_dump($rclone->purge('/test'));
// bool (true) on success, false on failure
// https://rclone.org/commands/rclone_purge/
```

### What does not work

Everything else
