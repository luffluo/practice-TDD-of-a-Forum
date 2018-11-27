<?php

namespace App\Inspections;

class Spam implements Inspection
{
    protected $inspections = [
        InvalidKeywords::class,
        KeyHeldDown::class,
    ];

    public function detect($string)
    {
        foreach ($this->inspections as $inspection) {
            app($inspection)->detect($string);
        }

        return false;
    }
}
