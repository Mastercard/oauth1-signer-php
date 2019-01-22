<?php
namespace Mastercard\Developer\OAuth1Signer;

use PHPUnit\Framework\TestCase;

class SimpleClassTest extends TestCase
{
    public function test()
    {
        $simple = new SimpleClass(10);
        $this->assertEquals(10, $simple->param);
    }
}