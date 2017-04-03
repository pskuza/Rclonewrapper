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
    }

    /**
     * Tests if createdir works.
     */
    public function testCreatedir()
    {
        $rclone = new Rclonewrapper('./rclone');
        $rclone->setremote('DropboxTest:');
        $this->assertTrue($rclone->createdir('/test'));
    }

    /**
     * Tests if deletedir works.
     */
    public function testDeletedir()
    {
        $rclone = new Rclonewrapper('./rclone');
        $rclone->setremote('DropboxTest:');
        $this->assertTrue($rclone->deletedir('/test'));
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
