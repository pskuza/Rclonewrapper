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
     * Init some random folder/file names.
     */
    public function __construct()
    {
        $this->semi_random_test_name = 'test_'.bin2hex(random_bytes(8));
		$this->semi_random_test_name_second = 'test_'.bin2hex(random_bytes(8));
    }

    /**
     * Tests if the version function works.
     */
    public function testEverythingDropbox()
    {
        $rclone = new Rclonewrapper('./rclone');
		
		// test version
		echo "Version ";
        $this->assertRegexp('/rclone/', $rclone->version());
		echo "OK\n";
		
		// test listremotes
		echo "Listremote ";
		$this->assertContains('DropboxTest:', $rclone->listremotes());
		echo "OK\n";
		
		// test setremote
		echo "Setremote ";
		$this->assertTrue($rclone->setremote('DropboxTest:'));
        $this->assertFalse($rclone->setremote('Nosuchremote:'));
		echo "OK\n";
		
		// test mkdir and rmdir
		echo "Mkdir ";
		$this->assertTrue($rclone->mkdir('/'.$this->semi_random_test_name));
		echo "OK\n";
		echo "Rmdir ";
        $this->assertTrue($rclone->rmdir('/'.$this->semi_random_test_name));
		echo "OK\n";
		
		// test copy and purge
		$this->assertTrue($rclone->mkdir('/'.$this->semi_random_test_name));
		echo "Copy one file ";
        $this->assertTrue($rclone->copy('testfile.dat', '/'.$this->semi_random_test_name));
		echo "OK\n";
		echo "Purge ";
        $this->assertTrue($rclone->purge('/'.$this->semi_random_test_name));
		echo "OK\n";
		
		echo "Copy a dir ";
		// test copy of folder and purge
		$this->assertTrue($rclone->mkdir('/'.$this->semi_random_test_name_second));
        $this->assertTrue($rclone->copy('testdir', '/'.$this->semi_random_test_name_second));
		echo "OK\n";
		
		// test size
		echo "Size ";
		$size_temp = $rclone->size($this->semi_random_test_name_second);
		$this->assertEquals(5, $size_temp['count']);
		$this->assertEquals("15728640", $size_temp['size']);
		echo "OK\n";
		
		// test ls, lsd
		echo "Ls ";
		$ls_expect = json_decode('{"\/":{"test4\/":[{"name":"testfile.dat","size":"4194304"}],"test3\/":[{"name":"testfile.dat","size":"3145728"}],"test5\/":[{"name":"testfile.dat","size":"5242880"}],"test1\/":[{"name":"testfile.dat","size":"1048576"}],"test2\/":[{"name":"testfile.dat","size":"2097152"}]}}', true);
		$ls_output = $rclone->ls($this->semi_random_test_name_second);
		$this->assertEquals($ls_expect, $ls_output);
		echo "OK\n";
		
		echo "Lsd ";
		$lsd_expect = json_decode('{"\/":["test1\/","test2\/","test3\/","test4\/","test5\/"]}', true);
		$lsd_output = $rclone->lsd($this->semi_random_test_name_second);
		$this->assertEquals($lsd_expect, $lsd_output);
		echo "OK\n";
		
		// not testing lsl since the timestamp changes
		
		// test sha1sum, md5sum
		echo "MD5sum ";
		$this->assertFalse($rclone->md5sum($this->semi_random_test_name_second));
		echo "OK\n";
		echo "SHA1sum ";
		$this->assertFalse($rclone->sha1sum($this->semi_random_test_name_second));
		echo "OK\n";
		
		
		// leave the remote clean
        $this->assertTrue($rclone->purge('/'.$this->semi_random_test_name_second));
		
		// test cleanup
		echo "Cleanup ";
		// Purge fails with Dropbox
        $this->assertFalse($rclone->cleanup());
		echo "OK\n";
    }
}
