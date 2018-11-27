<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Inspections\Spam;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SpamTest extends TestCase
{
    /**
     * @var Spam
     */
    protected $spam;

    protected function setUp()
    {
        parent::setUp();

        $this->spam = new Spam();
    }

    public function test_it_checks_for_invalid_keywords()
    {
        $this->assertFalse($this->spam->detect('Innocent reply here'));

        $this->expectException('Exception');

        $this->spam->detect('Fuck you');
    }

    public function test_it_checks_for_any_being_held_down()
    {
        $this->expectException('Exception');

        $this->spam->detect('Hello world aaaaa');
    }
}
