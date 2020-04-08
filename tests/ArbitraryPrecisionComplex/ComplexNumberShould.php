<?php

namespace Tests\ArbitraryPrecisionComplex;

use ArbitraryPrecisionComplex\Complex;
use ArbitraryPrecisionComplex\DecimalFactory;
use ArbitraryPrecisionComplex\Pi;
use Decimal\Decimal;

class ComplexNumberShould extends BaseTestClass {
    /** @test */
    public function should_have_a_real_and_imaginary_parts() {
        $c = new Complex(new Decimal('-2'), new Decimal('1'));

        $this->assertTrue((new Decimal('-2'))->equals($c->getReal()));
        $this->assertTrue((new Decimal('1'))->equals($c->getImaginary()));
    }

    /** @test */
    public function be_added_to_another_complex_number() {
        $c1 = new Complex(new Decimal('1'), new Decimal('2'));
        $c2 = new Complex(new Decimal('3'), new Decimal('4'));

        $c = $c1->add($c2);

        $this->assertTrue((new Decimal('4'))->equals($c->getReal()));
        $this->assertTrue((new Decimal('6'))->equals($c->getImaginary()));
    }

    /** @test */
    public function be_real_if_imaginary_part_is_equals_to_zero() {
        $c = new Complex(new Decimal('1'), new Decimal('0'));

        $this->assertTrue($c->isReal());
    }

    /** @test */
    public function be_complex_if_it_has_imaginary_part() {
        $c = new Complex(new Decimal('0'), new Decimal('1'));

        $this->assertTrue($c->isComplex());
    }

    /**
     * @test
     * @dataProvider theta_samples
     * @param Complex $complex
     * @param Decimal $thetaExpected
     */
    public function returns_the_theta_of_a_complex_number(Complex $complex, Decimal $thetaExpected) {
        $theta = $complex->theta();

        $this->assertTrue($thetaExpected->equals($theta),
            "Expected theta " . $thetaExpected->toString() . " is not equal to: " . $theta);
    }

    public function theta_samples() {
        return [
            // real zero (imaginary axis)

            // (0,0) -> 0
            [new Complex(DecimalFactory::from(0), DecimalFactory::from(0)), DecimalFactory::from(0)],
            // (0,1) -> PI/2
            [new Complex(DecimalFactory::from(0), DecimalFactory::from(1)), Pi::value()->div(2)],
            // (0,-1) -> -(PI/2)
            [new Complex(DecimalFactory::from(0), DecimalFactory::from(-1)), Pi::value()->div(2)->negate()],

            // real positive

            // (1,1) -> PI/4
            [new Complex(DecimalFactory::from(1), DecimalFactory::from(1)), Pi::value()->div(4)],
            // (1,-1) -> -PI/4
            [new Complex(DecimalFactory::from(1), DecimalFactory::from(-1)), Pi::value()->div(4)->negate()],
            // (1,0) -> 0
            [new Complex(DecimalFactory::from(1), DecimalFactory::from(0)), DecimalFactory::from(0)],

            // real negative

            // (-1,1) -> 3*PI/4
            [new Complex(DecimalFactory::from(-1), DecimalFactory::from(1)), Pi::value()->mul(3)->div(4)],
            // (-1,-1) -> -3*PI/4
            [new Complex(DecimalFactory::from(-1), DecimalFactory::from(-1)), Pi::value()->mul(3)->div(4)->negate()],
            // (-1,0) -> PI
            [new Complex(DecimalFactory::from(-1), DecimalFactory::from(0)), Pi::value()],
        ];
    }

    /**
     * This is a double check for the function. We use an external library to be sure the theta is right.
     * @test
     * @dataProvider some_complex_numbers
     * @param float $realPart
     * @param float $imaginaryPart
     */
    public function returns_the_right_theta_of_a_complex_number($realPart, $imaginaryPart) {
        $z1 = new Complex(DecimalFactory::from($realPart), DecimalFactory::from($imaginaryPart));
        $thirdPartyLibraryComplex = new \Complex\Complex($realPart, $imaginaryPart);

        $this->assertEqualsWithDelta($thirdPartyLibraryComplex->theta(), $z1->theta()->toFloat(), 0.00000000000001);
    }

    public function some_complex_numbers() {
        return [
            [0, 0],
            [1, 0],
            [1, 1],
            [0, 1],
            [-1, 1],
            [0, -1],
            [-1, -1],
            [0, -1],
            [1, -1],
        ];
    }

    /**
     * This test is used to check a number with bigger precision than the max float precision for 64 bits.
     * The float data type can commonly store a value up to 1.7976931348623E+308 (platform dependent),
     * and have a maximum precision of 14 digits.
     *
     * @test
     */
    public function returns_the_theta_of_a_complex_number_beyond_float_precision() {

        // Theta of (0,0) is 0
        $z0 = new Complex(DecimalFactory::from(0, 56), DecimalFactory::from(0, 56));

        // Theta of (-1,0) is PI
        $z180 = new Complex(DecimalFactory::from(-1, 56), DecimalFactory::from(0, 56));

        $this->assertEquals(DecimalFactory::from(0, 56), $z0->theta()->toString());
        $this->assertEquals(Pi::value()->toString(), $z180->theta()->toString());
    }
}

