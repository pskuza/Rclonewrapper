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
     * Class constructor.
     *
     * @param string $rclone
     * @param string $config
     */
    public function __construct($rclone, $config)
    {
        $this->rclone = $rclone;
        $this->config = $config;

    }
}
