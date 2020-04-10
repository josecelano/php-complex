<?php

namespace Tests\ArbitraryPrecisionComplex;

use ArbitraryPrecisionComplex\Complex;
use ArbitraryPrecisionComplex\DecimalFactory;
use ArbitraryPrecisionComplex\Pi;
use Decimal\Decimal;

class ComplexPowShould extends BaseTestClass {

    /** @test */
    public function work_using_zero_with_scientific_notation() {
        $_1 = Complex::fromInt(1, 0);

        $zero = new Decimal('0E-42');
        $this->assertTrue($zero->isZero());

        // Different ways to represent number 1 real. = imaginary part in scientific notation
        $c1 = Complex::fromStringNumber('-1.0000000000000000000000000000', '0E-41'); // 0 * 10^⁻41
        $c2 = Complex::fromStringNumber('-1.000000000000000000000000000000', '0.000000000000000000000000000000');
        $c3 = Complex::fromStringNumber('-1.0', '0.0');

        // 1² = 1
        $f1 = $c1->pow(2);
        $f2 = $c2->pow(2);
        $f3 = $c3->pow(2);

        $this->assertEqualWithDelta($_1, $f1);
        $this->assertEqualWithDelta($_1, $f2);
        $this->assertEqualWithDelta($_1, $f3);
    }

    /** @test */
    public function work_using_zero_with_scientific_notation_but_it_does_not_for_some_cases() {
        $_1 = Complex::fromInt(1, 0);

        $zero = new Decimal('0E-42');
        $this->assertTrue($zero->isZero());

        // Different ways to represent number 1 real. = imaginary part in scientific notation
        $c1 = Complex::fromStringNumber('-1.000000000000000000000000000000', '0E-84');  // 0 * 10^⁻84
        $c2 = Complex::fromStringNumber('-1.0000000000000000000000000000', '0E-42');    // 0 * 10^⁻42

        // 1² = 1
        $f1 = $c1->pow(2);
        $f2 = $c2->pow(2);

        $this->assertEqualWithDelta($_1, $f1);
        $this->assertEqualWithDelta($_1, $f2);
    }

    /**
     * @param Complex $expected
     * @param Complex $result
     * @param string $delta
     */
    protected function assertEqualWithDelta(Complex $expected, Complex $result, $delta = '0.00000000000000000000001'): void {
        $this->assertTrue(
            $result->compareTo($expected, DecimalFactory::from($delta)),
            sprintf("Result value %s is not equal to expected value %s", $result->__toString(), $expected->__toString())
        );
    }
}

