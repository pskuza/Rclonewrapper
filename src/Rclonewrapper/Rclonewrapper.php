<?php

/*
 * Copyright (c) Philip Skuza <philip.skuza@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rclonewrapper;

class Rclonewrapper
{
    /**
     * The rclone binary location.
     *
     * @var string
     */
    private $rclone;

    /**
     * The rclone config location.
     *
     * @var string
     */
    private $config;

    /**
     * The rclone remote path.
     *
     * @var string
     */
    private $remote = null;

    /**
     * Class constructor.
     *
     * @param string $rclone
     * @param string $config
     */
    public function __construct($rclone, $config = 'rclone.conf')
    {
        $this->rclone = $rclone;
        $this->config = $config;
    }

    /**
     * Sets the remote.
     *
     * @param string $remote
     *
     * @return bool
     */
    public function setremote($remote)
    {
        if (!empty($remote)) {
            // check if this remotes exists
            if (in_array($remote, $this->listremotes())) {
                $this->remote = $remote;

                return true;
            }
        }

        return false;
    }

    /**
     * Creates a dir.
     *
     * @param string $path
     *
     * @return bool
     */
    public function mkdir($path)
    {
        $createdir = $this->execute('mkdir '.$this->remote.$path);

        if (isset($createdir) && !$createdir[1]) {
            return true;
        }

        return false;
    }

    /**
     * Copy a file to remote:path.
     *
     * @param string $source_path
     * @param string $path
     *
     * @return bool
     */
    public function copy($source_path, $path)
    {
        $copy = $this->execute('copy '.$source_path.' '.$this->remote.$path);

        if (isset($copy) && !$copy[1]) {
            return true;
        }

        return false;
    }
	
	/**
     * md5sum of remote:path.
     *
     * @param string $path
     *
     * @return array
     */
    public function md5sum($path)
    {
        $md5sum = $this->execute('md5sum '.$this->remote.$path);

        if (isset($md5sum) && !$md5sum[1]) {
            //parse the output to an usable array
            $list = [];
            foreach ($md5sum[0] as $md5sum_output) {
                $md5sum_output = explode(' ', ltrim($md5sum_output), 2);

                //check if supported
                if ($md5sum_output[0] === "UNSUPPORTED") {
					//remote does not support md5sum
                    return false;
                } else {
                    if (strpos($md5sum_output[1], '/') !== false) {
						$dirname = substr($md5sum_output[1], 0, strrpos($md5sum_output[1], '/') + 1);
						$filename = mb_substr($md5sum_output[1], mb_strlen($dirname));
						$list['/'][$dirname][] = ['name' => $filename, 'md5' => $md5sum_output[0]];
					} else {
						$list['/'][] = ['name' => $md5sum_output[1], 'md5' => $md5sum_output[0]];
					}
                }
            }

            return $list;
        }

        return false;
    }
	
	/**
     * sha1sum of remote:path.
     *
     * @param string $path
     *
     * @return array
     */
    public function sha1sum($path)
    {
        $sha1sum = $this->execute('sha1sum '.$this->remote.$path);

        if (isset($sha1sum) && !$sha1sum[1]) {
            //parse the output to an usable array
            $list = [];
            foreach ($sha1sum[0] as $sha1sum_output) {
                $sha1sum_output = explode(' ', ltrim($sha1sum_output), 2);

                //check if supported
                if ($sha1sum_output[0] === "UNSUPPORTED") {
					//remote does not support md5sum
                    return false;
                } else {
                    if (strpos($sha1sum_output[1], '/') !== false) {
						$dirname = substr($sha1sum_output[1], 0, strrpos($sha1sum_output[1], '/') + 1);
						$filename = mb_substr($sha1sum_output[1], mb_strlen($dirname));
						$list['/'][$dirname][] = ['name' => $filename, 'md5' => $sha1sum_output[0]];
					} else {
						$list['/'][] = ['name' => $sha1sum_output[1], 'md5' => $sha1sum_output[0]];
					}
                }
            }

            return $list;
        }

        return false;
    }

    /**
     * ls of remote:path.
     *
     * @param string $path
     *
     * @return array
     */
    public function ls($path)
    {
        $ls = $this->execute('ls '.$this->remote.$path);

        if (isset($ls) && !$ls[1]) {
            //parse the output to an usable array
            $list = [];
            foreach ($ls[0] as $ls_output) {
                $ls_output = explode(' ', ltrim($ls_output), 2);

                //check if it's a dir
                if (strpos($ls_output[1], '/') !== false) {
                    $dirname = substr($ls_output[1], 0, strrpos($ls_output[1], '/') + 1);
					$filename = mb_substr($ls_output[1], mb_strlen($dirname));
                    $list['/'][$dirname][] = ['name' => $filename, 'size' => $ls_output[0]];
                } else {
                    $list['/'][] = ['name' => $ls_output[1], 'size' => $ls_output[0]];
                }
            }

            return $list;
        }

        return false;
    }

    /**
     * lsl of remote:path.
     *
     * @param string $path
     *
     * @return array
     */
    public function lsl($path)
    {
        $lsl = $this->execute('lsl '.$this->remote.$path);

        if (isset($lsl) && !$lsl[1]) {
            //parse the output to an usable array
            $list = [];
            foreach ($lsl[0] as $lsl_output) {
                $lsl_output = explode(' ', ltrim($lsl_output), 4);

                //check if it's a dir
                if (strpos($lsl_output[3], '/') !== false) {
                    $dirname = substr($lsl_output[3], 0, strrpos($lsl_output[3], '/') + 1);
					$filename = mb_substr($lsl_output[3], mb_strlen($dirname));
                    $list['/'][$dirname][] = ['name' => $filename, 'size' => $lsl_output[0], 'time' => $lsl_output[1].' '.$lsl_output[2]];
                } else {
                    $list['/'][] = ['name' => $lsl_output[3], 'size' => $lsl_output[0], 'time' => $lsl_output[1].' '.$lsl_output[2]];
                }
            }

            return $list;
        }

        return false;
    }

    /**
     * lsd of remote:path.
     *
     * @param string $path
     *
     * @return array
     */
    public function lsd($path)
    {
        $lsd = $this->execute('lsd '.$this->remote.$path);

        if (isset($lsd) && !$lsd[1]) {
            //parse the output to an usable array
            $list = [];
            foreach ($lsd[0] as $lsd_output) {
                $lsd_output = substr($lsd_output, strpos($lsd_output, '-1'));
                $lsd_output = explode(' ', ltrim($lsd_output), 2);
                if (!empty($lsd_output[1])) {
                    $dirname = $lsd_output[1].'/';
                    $list['/'][] = $dirname;
                }
            }

            return $list;
        }

        return false;
    }

    /**
     * Purge command.
     *
     * @param string $path
     *
     * @return bool
     */
    public function purge($path)
    {
        $purge = $this->execute('purge '.$this->remote.$path);

        if (isset($purge) && !$purge[1]) {
            return true;
        }

        return false;
    }

    /**
     * Deletes a dir.
     *
     * @param string $path
     *
     * @return bool
     */
    public function rmdir($path)
    {
        $deletedir = $this->execute('rmdir '.$this->remote.$path);

        if (isset($deletedir) && !$deletedir[1]) {
            return true;
        }

        return false;
    }

    /**
     * Prints the total size and number of objects.
     *
     * @param string $path
     *
     * @return array
     */
    public function size($path)
    {
        $size = $this->execute('size '.$this->remote.$path);

        if (isset($size) && !$size[1]) {
            $count = abs((int) filter_var($size[0][0], FILTER_SANITIZE_NUMBER_INT));
            preg_match_all('/\((\d+) Bytes\)/', $size[0][1], $matches);

            return ['count' => $count, 'size' => $matches[1][0]];
        }

        return false;
    }

    /**
     * Cleanup a remote.
     *
     * @return bool
     */
    public function cleanup()
    {
        $cleanup = $this->execute('cleanup '.$this->remote);

        if (isset($cleanup) && !$cleanup[1]) {
            return true;
        }

        return false;
    }

    /**
     * Lists all remotes in the config.
     *
     * @return array
     */
    public function listremotes()
    {
        $listremotes = $this->execute('listremotes');

        return $listremotes[0];
    }

    /**
     * Returns rclone version.
     *
     * @return string
     */
    public function version()
    {
        $version = $this->execute('version');

        return reset($version[0]);
    }

    /**
     * @param string $command
     *
     * @throws RuntimeException
     *
     * @return array
     */
    protected function execute($command)
    {
        exec($this->rclone.' --config '.$this->config.' '.$command, $output, $returnValue);

        //do custom exceptions later
        // if ($returnValue !== 0) {
            // throw new \Exception(implode("\r\n", $output));
        // }

        return [$output, $returnValue];
    }
}
