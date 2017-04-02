<?php

/*
 * Copyright (c) Philip Skuza <philip.skuza@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Rclonewrapper;

use Rclonewrapper\Exception\RcloneException;

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
     * Class constructor.
     *
     * @param string $rclone
     * @param string $config
     */
    public function __construct($rclone, $config = '.rclone.conf')
    {
        $this->rclone = $rclone;
        $this->config = $config;
    }

    /**
     * Returns rclone version.
     *
     * @return string
     */
    public function version()
    {
        $version = $this->execute('version');

        return reset($version);
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

        if ($returnValue !== 0) {
            throw new Exception(implode("\r\n", $output));
        }

        return [$output, $returnValue];
    }
}
