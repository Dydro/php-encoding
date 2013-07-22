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
 * ASCII85 encoding/decoding utility
 *
 * @package Dydro\Encoding
 */
class ASCII85 implements Encoding
{
    /**
     * Decodes a string from it's ASCII85-encoded equivalent
     *
     * @param string $data The data to decode
     * @return string The decoded data
     */
    public static function decode($data)
    {
        // TODO: Implement decode() method.
    }

    /**
     * Encoding a string to it's ASCII85-encoded equivalent
     *
     * @param string $data The data to encode
     * @return string The encoded data
     */
    public static function encode($data)
    {
        $return = '';

        // break the string into 4 char chunks (use this for encoding the pieces
        $chunks = str_split($data, 4);

        foreach ($chunks as $chunk) {
            // pad any leftovers that are less than 4 characters with the null character
            if (strlen($chunk) < 4) {
                $chunk = str_pad($chunk, 4, "\0");
            }

            // get the Big-Endian int32 representation of the chunk
            $int32Rep = unpack('N', $chunk)[1];

            // replace any null chunks with `z`
            if ($int32Rep === 0) {
                $return .= 'z';
                continue;
            }

            // Credit to Zend Framework for this snippet
            // Count down, the char code is the exponentiation of the position + 33
            // The int32Rep then gets the modulus appended, and the char is added to the string
            for ($j = 4; $j >= 0; $j--) {
                $foo = (int) (($int32Rep / pow(85, $j)) + 33);
                $int32Rep %= pow(85, $j);
                $return .= chr($foo);
            }
        }

        // add the ending document
        $return .= '~>';

        // return the line-split encoded string with the line-break taken off the end
        return rtrim(chunk_split($return, 76, PHP_EOL), PHP_EOL);
    }
}