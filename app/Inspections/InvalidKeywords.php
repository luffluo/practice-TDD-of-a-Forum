<?php

namespace App\Inspections;

class InvalidKeywords implements Inspection
{
    protected $keywords = [
        'fuck'
    ];

    public function detect($string)
    {
        foreach ($this->keywords as $keyword) {
            if (false !== stripos($string, $keyword)) {
                throw new \Exception('Your reply contains spam');
            }
        }
    }
}
