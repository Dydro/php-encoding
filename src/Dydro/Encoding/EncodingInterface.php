<?php
/**
 * PHP-Encoding - Various encoding/decoding methods
 *
 * @author Troy McCabe <troy@dydro.com>
 * @copyright 2013 Dydro LLC. All rights reserved.
 * @license BSD 3-Clause License
 * @link http://github.com/dydro/php-encoding
 * @package Dydro\Encoding
 */

namespace Dydro\Encoding;

/**
 * Main interface for various encodings
 *
 * @package Dydro\Encoding
 */
interface EncodingInterface
{
    /**
     * Decode a string from it's algo-equivalent
     *
     * @param string $data The data to decode
     * @return string The decoded data
     */
    public function decode($data);

    /**
     * Encode a string to it's algo-equivalent
     *
     * @param string $data The data to encode
     * @return string The encoded data
     */
    public function encode($data);
}