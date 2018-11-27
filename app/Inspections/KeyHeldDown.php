<?php

namespace App\Inspections;

class KeyHeldDown implements Inspection
{
    public function detect($string)
    {
        if (preg_match('/(.)\\1{4,}/', $string, $m)) {
            throw new \Exception('Your reply contains spam.');
        }
    }
}
