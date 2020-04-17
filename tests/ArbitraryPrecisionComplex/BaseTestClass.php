<?php

namespace Tests\ArbitraryPrecisionComplex;

use ArbitraryPrecisionComplex\Complex;
use ArbitraryPrecisionComplex\DecimalFactory;
use Decimal\Decimal;
use PHPUnit\Framework\TestCase;

class BaseTestClass extends TestCase {

    const DELTA = '0.0000000000000000000000001'; // For Decimal 28 precision

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
    protected function assertComplexEquals(Complex $expectedComplex, Complex $complex, string $message = '') {

        if ($message == '') {
            $message = sprintf("Complex number %s is not equal to expected number %s", $complex, $expectedComplex);
        }

        $this->assertTrue($complex->equals($expectedComplex), $message);
    }

    protected function assertDecimalEqualsWithDelta(Decimal $expected, Decimal $value, string $delta = self::DELTA, string $message = '') {

        if ($message == '') {
            $message = sprintf("Failed asserting that decimal number %s is equal to expected %s", $value->__toString(), $expected->__toString());
        }

        $this->assertTrue($value->sub($expected) < DecimalFactory::from($delta), $message);
    }

    protected function assertComplexEqualsWithDelta(Complex $expected, Complex $value, string $delta = self::DELTA, string $message = '') {

        if ($message == '') {
            $message = sprintf("Failed asserting that complex number %s is equal to expected %s", $value->__toString(), $expected->__toString());
        }

        $this->assertTrue($value->compareTo($expected, DecimalFactory::from($delta)), $message);
    }

    protected function assertComplexNotEqualsWithDelta(Complex $expected, Complex $value, string $delta = self::DELTA, string $message = '') {

        if ($message == '') {
            $message = sprintf("Failed asserting that complex number %s is not equal to expected %s", $value->__toString(), $expected->__toString());
        }

        $this->assertFalse($value->compareTo($expected, DecimalFactory::from($delta)), $message);
    }
}