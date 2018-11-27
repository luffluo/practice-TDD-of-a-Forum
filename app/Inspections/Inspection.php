<?php

namespace App\Inspections;

interface Inspection
{
    public function detect($string);
}
