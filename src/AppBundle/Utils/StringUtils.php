<?php

namespace AppBundle\Utils;

class StringUtils
{
    private $chars;

    public function __construct()
    {
        $this->chars = array_merge(range('a', 'z'), range(0, 9));
    }

    public function randomString($len)
    {
        $chars = $this->chars;
        shuffle($chars);

        return implode('', array_slice($chars, 0, $len));
    }
}
