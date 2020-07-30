<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $this->url('https://www.facebook.com/');
            $element = $this->byCssSelector('body');

        $this->assertTrue(true);
    }
}
