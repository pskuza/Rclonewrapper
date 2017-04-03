<?php

/*
 * Copyright (c) Philip Skuza <philip.skuza@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rclonewrapper\tests;

use PHPUnit\Framework\TestCase;
use Rclonewrapper\Rclonewrapper;

/**
 * Rclonewrappertests.
 *
 * Tests all functionality in the Rclonewrapper package.
 */
class RclonewrapperTest extends TestCase
{
    private $semi_random_folder_name;

    /**
     * Init some semi random folders/files names.
     */
    public function __construct()
    {
        $this->semi_random_test_name = 'test_'.mt_rand();
    }

    /**
     * Tests if the version function works.
     */
    public function testVersion()
    {
        $rclone = new Rclonewrapper('./rclone');

        $this->assertRegexp('/rclone/', $rclone->version());
    }

    /**
     * Tests if the listremotes function works.
     */
    public function testListremotes()
    {
        $rclone = new Rclonewrapper('./rclone');
        $this->assertContains('DropboxTest:', $rclone->listremotes());
    }

    /**
     * Tests if setremote works.
     */
    public function testSetremote()
    {
        $rclone = new Rclonewrapper('./rclone');
        $this->assertTrue($rclone->setremote('DropboxTest:'));
        $this->assertFalse($rclone->setremote('Nosuchremote:'));
    }

    /**
     * Tests if mkdir and rmdir works.
     */
    public function testCreatedirandDeletedir()
    {
        $rclone = new Rclonewrapper('./rclone');
        $rclone->setremote('DropboxTest:');
        $this->assertTrue($rclone->mkdir('/'.$this->semi_random_test_name));
        $this->assertTrue($rclone->rmdir('/'.$this->semi_random_test_name));
    }

    /**
     * Tests if copy and purge works.
     */
    public function testCreatedirandCopyandPurge()
    {
        $rclone = new Rclonewrapper('./rclone');
        $rclone->setremote('DropboxTest:');
        $this->assertTrue($rclone->mkdir('/'.$this->semi_random_test_name));
        $this->assertTrue($rclone->copy('testfile.dat', '/'.$this->semi_random_test_name));
        $this->assertTrue($rclone->purge('/'.$this->semi_random_test_name));
    }

    /**
     * Tests if cleanup.
     */
    public function testCleanup()
    {
        $rclone = new Rclonewrapper('./rclone');
        // cleanup does not work with dropbox
        $rclone->setremote('DropboxTest:');
        $this->assertFalse($rclone->cleanup());
    }
}
