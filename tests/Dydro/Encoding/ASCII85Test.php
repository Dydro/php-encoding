<?php
/**
 * PHP-Encoding - Various encoding/decoding methods
 *
 * @author Troy McCabe <troy@dydro.com>
 * @copyright 2013 Dydro LLC. All rights reserved.
 * @license BSD 3-Clause License
 * @link http://github.com/dydro/php-encoding
 * @package Dydro\Encoding\Test
 */

namespace Dydro\Encoding\Test;
use Dydro\Encoding\ASCII85;

/**
 * Tests the ASCII85 encoding/decoding utility
 *
 * @package Dydro\Encoding\Test
 */
class ASCII85Test extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests decoding a string
     */
    public function testDecode()
    {

    }

    /**
     * Tests encoding a string
     */
    public function testEncode()
    {
        $this->assertEquals('<+oue+DGm>@3BW5EbTE(~>', ASCII85::encode('This is a string'));
    }
}