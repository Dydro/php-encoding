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
 * Base85 encoding/decoding utility
 *
 * @package Dydro\Encoding
 */
class Base85 implements EncodingInterface
{
    const SPEC_ADOBE = 'adobe';
    const SPEC_BTOA = 'btoa';
    const SPEC_RAW = 'raw';

    /**
     * The selected spec to use
     *
     * @var string
     */
    private $spec;

    /**
     * Create an instance
     */
    public function __construct($spec = self::SPEC_RFC1924)
    {
        $this->spec = $spec;
        if (
            $this->spec !== self::SPEC_ADOBE &&
            $this->spec !== self::SPEC_BTOA &&
            $this->spec !== self::SPEC_RAW
        ) {
            // @TODO - throw new \Dydro\Encoding\Exception\InvalidArgumentException('Invalid spec specified');
        }
    }

    /**
     * Decodes a string from it's ASCII85-encoded equivalent
     *
     * Credit to
     *
     * @param string $data The data to decode
     * @return string The decoded data
     */
    public function decode($data)
    {
        if ($this->spec === self::SPEC_ADOBE) {
            $data = substr($data, 2, strlen($data) - 4);
        }
        $return = '';
        $dataParts = str_split($data, 5);
        $base85Ords = [];

        $exclamation = ord('!');
        $tilde = ord('~');
        $u = ord('u');
        $z = ord('z');

        foreach ($dataParts as $dataPart) {
            if ($dataPart < 5) {
                str_pad($dataPart, 5, '~');
            }

            for ($i = 0; $i < 5; $i++) {
                if (!isset($base85Ords[$dataPart{$i}])) {
                    $base85Ords[$dataPart{$i}] = ord($dataPart{$i});
                }

                $ord = $base85Ords[$dataPart{$i}];

                if ($ord == $tilde) {
                    break;
                }

                if (preg_match('/^\s$/', $dataPart{$i})) {
                    continue;
                }

                if ($ord == $z && $i == 0) {
                    $return .= str_repeat("\0", 4);
                }

                if ($ord) {
                    // @TODO exception (bad char)
                }


            }
        }

        public static function decode($data, $params = null)
    {
        $output = '';

        //get rid of the whitespaces
        $whiteSpace = array("\x00", "\x09", "\x0A", "\x0C", "\x0D", "\x20");
        $data = str_replace($whiteSpace, '', $data);

        if (substr($data, -2) != '~>') {
            throw new Exception\CorruptedPdfException('Invalid EOF marker');
            return '';
        }

        $data = substr($data, 0, (strlen($data) - 2));
        $dataLength = strlen($data);

        for ($i = 0; $i < $dataLength; $i += 5) {
            $b = 0;

            if (substr($data, $i, 1) == "z") {
                $i -= 4;
                $output .= pack("N", 0);
                continue;
            }

            $c = substr($data, $i, 5);

            if(strlen($c) < 5) {
                //partial chunk
                break;
            }

            $c = unpack('C5', $c);
            $value = 0;

            for ($j = 1; $j <= 5; $j++) {
                $value += (($c[$j] - 33) * pow(85, (5 - $j)));
            }

            $output .= pack("N", $value);
        }

        //decode partial
        if ($i < $dataLength) {
            $value = 0;
            $chunk = substr($data, $i);
            $partialLength = strlen($chunk);

            //pad the rest of the chunk with u's
            //until the lenght of the chunk is 5
            for ($j = 0; $j < (5 - $partialLength); $j++) {
                $chunk .= 'u';
            }

            $c = unpack('C5', $chunk);

            for ($j = 1; $j <= 5; $j++) {
                $value += (($c[$j] - 33) * pow(85, (5 - $j)));
            }

            $foo = pack("N", $value);
            $output .= substr($foo, 0, ($partialLength - 1));
        }

        return $output;
    }
//
//        $out = "";
//        $state = 0;
//        $chn = null;
//
//        $l = strlen($in);
//
//        for ($k = 0; $k < $l; ++$k) {
//            $ch = ord($in[$k]) & 0xff;
//
//            if ($ch == ORD_tilde) {
//                break;
//            }
//            if (preg_match("/^\s$/",chr($ch))) {
//                continue;
//            }
//            if ($ch == ORD_z && $state == 0) {
//                $out .= chr(0).chr(0).chr(0).chr(0);
//                continue;
//            }
//            if ($ch < ORD_exclmark || $ch > ORD_u) {
//                $this->fpdi->error("Illegal character in ASCII85Decode.");
//            }
//
//            $chn[$state++] = $ch - ORD_exclmark;
//
//            if ($state == 5) {
//                $state = 0;
//                $r = 0;
//                for ($j = 0; $j < 5; ++$j)
//                    $r = $r * 85 + $chn[$j];
//                $out .= chr($r >> 24);
//                $out .= chr($r >> 16);
//                $out .= chr($r >> 8);
//                $out .= chr($r);
//            }
//        }
//        $r = 0;
//
//        if ($state == 1)
//            $this->fpdi->error("Illegal length in ASCII85Decode.");
//        if ($state == 2) {
//            $r = $chn[0] * 85 * 85 * 85 * 85 + ($chn[1]+1) * 85 * 85 * 85;
//            $out .= chr($r >> 24);
//        }
//        else if ($state == 3) {
//            $r = $chn[0] * 85 * 85 * 85 * 85 + $chn[1] * 85 * 85 * 85  + ($chn[2]+1) * 85 * 85;
//            $out .= chr($r >> 24);
//            $out .= chr($r >> 16);
//        }
//        else if ($state == 4) {
//            $r = $chn[0] * 85 * 85 * 85 * 85 + $chn[1] * 85 * 85 * 85  + $chn[2] * 85 * 85  + ($chn[3]+1) * 85 ;
//            $out .= chr($r >> 24);
//            $out .= chr($r >> 16);
//            $out .= chr($r >> 8);
//        }
//
//        return $out;
    }

    /**
     * Encoding a string to it's ASCII85-encoded equivalent
     *
     * @param string $data The data to encode
     * @return string The encoded data
     */
    public function encode($data)
    {
        $return = '';

        // break the string into 4 char chunks (use this for encoding the pieces)
        $chunks = str_split($data, 4);

        // pre-calculate the powers of 85
        $eightyFivePowers = [
            pow(85, 4),
            pow(85, 3),
            pow(85, 2),
            85,
            1,
        ];

        // pre-calculate the int representation of 4 spaces
        $fourSpaces = unpack('N', '    ')[1];

        // go through each chunk, calculating return val
        foreach ($chunks as $chunk) {
            // pad any leftovers that are less than 4 characters with the null character
            $padding = 0;
            $chunkLength = strlen($chunk);
            if ($chunkLength < 4) {
                $padding = 4 - $chunkLength;
                $chunk = str_pad($chunk, 4, "\0");
            }

            // get the integer representation of the chunk
            $intRep = unpack('N', $chunk)[1];

            // replace any null chunks with `z` if the spec allows
            if ($intRep === 0 && ($this->spec === self::SPEC_ADOBE || $this->spec === self::SPEC_BTOA)) {
                $return .= 'z';
                continue;
            }

            // replace any all-space chunks with `y` if the spec allows
            if ($intRep === $fourSpaces && $this->spec === self::SPEC_BTOA) {
                $return .= 'y';
                continue;
            }

            // Count down, the char code is the exponentiation of the position + 33
            // The intRep then gets the modulus appended, and the char is added to the string
            foreach ($eightyFivePowers as $eightyFivePower) {
                $charCode = (int) (($intRep / $eightyFivePower) + 33);
                $intRep %= $eightyFivePower;
                $return .= chr($charCode);
            }

            $return = substr($return, 0, strlen($return) - $padding);
        }

        if ($this->spec === self::SPEC_ADOBE) {
            return "<~{$return}~>";
        } else if ($this->spec === self::SPEC_BTOA) {
            $checks = $this->getBTOAChecks($data);
            return "xbtoa Begin\n{$return}\nxbtoa End " .
                "N {$checks['size']} {$checks['hex']} " .
                "E {$checks['xor']} " .
                "S {$checks['checksum']} " .
                "R {$checks['rot']}";
        } else {
            return $return;
        }
    }

    private function getBTOAChecks($input)
    {
        $size = strlen($input);

        $checks = [
            'size' => $size,
            'hex' => dechex($size),
            'xor' => 0,
            'checksum' => 0,
            'rot' => 0,
        ];
        for ($i = 0; $i < $size; $i++) {
            $ord = ord($input{$i});
            $checks['xor'] ^= $ord;
            $checks['checksum'] += $ord + 1;
            $checks['rot'] *= 2;
            if ($checks['rot'] > 2147483647 && $checks['rot'] < 4294967296) {
                $checks['rot'] += 1;
            }
            $checks['rot'] += $ord;
        }

        $checks['xor'] = dechex($checks['xor']);
        $checks['checksum'] = dechex($checks['checksum']);
        $checks['rot'] = dechex($checks['rot']);

        return $checks;
    }
}