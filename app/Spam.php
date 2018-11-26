<?php

namespace App;

class Spam
{
    public function detect($string)
    {
        $this->detectInvalidKeywords($string);

        return false;
    }

    public function detectInvalidKeywords($string)
    {
        $invalidKeywords = [
            'fuck',
        ];

        foreach ($invalidKeywords as $invalidKeyword) {
            if (false !== stripos($string, $invalidKeyword)) {
                throw new \Exception('Your reply contains spam');
            }
        }
    }
}
