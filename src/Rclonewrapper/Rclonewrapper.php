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
    public function createdir($path)
    {
        $createdir = $this->execute('mkdir '.$this->remote.$path);

        if (isset($createdir[1]) && !$createdir[1]) {
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
    public function deletedir($path)
    {
        $deletedir = $this->execute('rmdir '.$this->remote.$path);

        if (isset($deletedir) && !$deletedir[1]) {
            return true;
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

        // if ($returnValue !== 0) {
            // throw new \Exception(implode("\r\n", $output));
        // }

        return [$output, $returnValue];
    }
}
