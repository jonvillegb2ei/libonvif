<?php
/**
 * Created by PhpStorm.
 * User: jonvig1
 * Date: 27/06/19
 * Time: 16:19
 */

namespace LibOnvif;


class Nonce
{

    private $buffer;

    public function __construct (string $buffer)
    {
        $this->buffer = $buffer;
    }

    public function __toString ()
    {
        return $this->toString();
    }

    public function toString ()
    {
        return base64_encode($this->buffer);
    }

    public function getBuffer()
    {
        return $this->buffer;
    }

    static public function generate(int $size = 16): Nonce
    {
        $return = '';
        for($i = 0; $i<$size; $i++) {
            $return .= chr(rand(0,255));
        }
        return new self($return);
    }

}