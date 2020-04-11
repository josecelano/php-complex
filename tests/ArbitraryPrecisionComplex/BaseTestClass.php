<?php

namespace Tests\ArbitraryPrecisionComplex;

use ArbitraryPrecisionComplex\Complex;
use PHPUnit\Framework\TestCase;

class BaseTestClass extends TestCase {
    protected $object;

    protected function expect($object) {
        $this->object = $object;
        return $this;
    }

    protected function toBe($object) {
        $this->assertEquals($this->object, $object);
    }

    /**
     * @param Complex $expectedComplex
     * @param Complex $complex
     * @param string $message
     */
    protected function complexEquals(Complex $expectedComplex, Complex $complex, string $message = '') {

        if ($message == '') {
            $message = sprintf("Complex number %s is not equal to expected number %s", $complex, $expectedComplex);
        }

        $this->assertTrue($complex->equals($expectedComplex), $message);
    }
}