<?php

namespace App\Tests;


use App\Service\SearchProduct;
use PHPUnit\Framework\TestCase;

class isValidString extends TestCase
{
    public function testSomething(): void
    {
//        $this->assertEquals(true, SearchHotel::validStr('vafa'));
        $this->assertEquals(true, SearchProduct::validStr('vafa sihcxcvx'));
    }
}
