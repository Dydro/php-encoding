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

use Dydro\Encoding\Base85;

/**
 * Tests the ASCII85 encoding/decoding utility
 *
 * @package Dydro\Encoding\Test
 */
class Base85Test extends \PHPUnit_Framework_TestCase
{
//    /**
//     * Tests decoding a string
//     */
//    public function testDecode()
//    {
//        $this->assertEquals('This is a string', ASCII85::decode('<+oue+DGm>@3BW5EbTE('));
//    }
//
    /**
     * @dataProvider adobeProvider
     */
    public function testAdobeEncode($string, $encoded)
    {
        $base85 = new Base85(Base85::SPEC_ADOBE);
        $this->assertEquals($encoded, $base85->encode($string));
    }

    /**
     * @dataProvider btoaProvider
     */
    public function testBtoaEncode($string, $encoded)
    {
        $base85 = new Base85(Base85::SPEC_BTOA);
        $this->assertEquals($encoded, $base85->encode($string));
    }

    /**
     * @dataProvider rawProvider
     */
    public function testRawEncode($string, $encoded)
    {
        $base85 = new Base85(Base85::SPEC_RAW);
        $this->assertEquals($encoded, $base85->encode($string));
    }

    public function adobeProvider()
    {
        return [
            ['This is a string', '<~<+oue+DGm>@3BW5EbTE(~>'],
            ['Another test case with        spaces', '<~6#LU_BOu3,FCfN8+Cei$AKZ22FD)d>+<VdL+<Y`E@:Nki~>'],
            ['Extend char set †', '<~7<iocDIal"BOPp(F(KG9idd[~>'],
        ];
    }

    public function btoaProvider()
    {
        return [
            ['This is a string', "xbtoa Begin\n<+oue+DGm>@3BW5EbTE(\nxbtoa End N 16 10 E 68 S 5dc R 5c4e97"],
            ['Another test case with        spaces', "xbtoa Begin\n6#LU_BOu3,FCfN8+Cei\$AKZ22FD)d>y+<Y`E@:Nki\nxbtoa End N 36 24 E 7c S bec R 5764f6b1b31"],
            ['Extend char set †', "xbtoa Begin\n7<iocDIal\"BOPp(F(KG9idd[\nxbtoa End N 19 13 E be S 7c7 R 2dab2c8"],
        ];
    }

    public function rawProvider()
    {
        return [
            ['This is a string', '<+oue+DGm>@3BW5EbTE('],
            ['Another test case with        spaces', '6#LU_BOu3,FCfN8+Cei$AKZ22FD)d>+<VdL+<Y`E@:Nki'],
            ['Extend char set †', '7<iocDIal"BOPp(F(KG9idd['],
        ];
    }
}