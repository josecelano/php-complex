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

        // With integers

        $c1 = new Complex(new Decimal('1'), new Decimal('2'));
        $c2 = new Complex(new Decimal('3'), new Decimal('4'));

        $r1 = $c1->add($c2);

        $this->assertTrue((new Decimal('4'))->equals($r1->getReal()));
        $this->assertTrue((new Decimal('6'))->equals($r1->getImaginary()));

        // With floats

        $c3 = new Complex(new Decimal('0.1'), new Decimal('0.2'));
        $c4 = new Complex(new Decimal('0.3'), new Decimal('0.4'));

        $r2 = $c3->add($c4);

        $this->assertTrue((new Decimal('0.4'))->equals($r2->getReal()));
        $this->assertTrue((new Decimal('0.6'))->equals($r2->getImaginary()));
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

    /**
     * @test
     * @dataProvider some_pow_result_examples
     * @param Complex $z
     * @param $power
     * @param Complex $expectedResult
     * @throws \Exception
     */
    public function implement_exponential_function(Complex $z, $power, Complex $expectedResult) {
        $result = $z->pow($power);

        /*
        // DEBUG
        echo "\nResult:\n";
        var_dump($result->getReal()->toString(), $result->getImaginary()->toString());
        echo "Expected Result:\n";
        var_dump($expectedResult->getReal()->toString(), $expectedResult->getImaginary()->toString());
        echo "\n";
        */

        /* Calculate result for test case with third party library
        $c = new \Complex\Complex(1,1);
        $p = $c->pow(2);
        var_dump($p);
        die;
        */

        $delta = '0.00000000000000000000000001';
        $this->assertTrue($result->compareTo($expectedResult, DecimalFactory::from($delta)));
    }

    public function some_pow_result_examples() {
        return [
            // Real positive
            [Complex::fromInt(1, 0), 0, Complex::fromInt(1, 0)], // (1)^0 = 1
            [Complex::fromInt(1, 0), 1, Complex::fromInt(1, 0)], // (1)^1 = 1
            [Complex::fromInt(2, 0), 0, Complex::fromInt(1, 0)], // (2)^0 = 1
            [Complex::fromInt(2, 0), 1, Complex::fromInt(2, 0)], // (2)^1 = 2
            [Complex::fromStringNumber(0.5, 0), 0, Complex::fromStringNumber(1, 0)],    // (0.5)^0 = 1
            [Complex::fromStringNumber(0.5, 0), 1, Complex::fromStringNumber(0.5, 0)],  // (0.5)^1 = 0.5
            [Complex::fromStringNumber(0.5, 0), 2, Complex::fromStringNumber(0.25, 0)], // (0.5)^2 = 0.25

            // Real negative
            [Complex::fromInt(-1, 0), 0, Complex::fromInt(1, 0)], // (-1)^0 = 1
            [Complex::fromInt(-1, 0), 1, Complex::fromInt(1, 0)], // (-1)^1 = 1
            [Complex::fromInt(-1, 0), 2, Complex::fromInt(1, 0)], // (-1)^2 = 1

            // Imaginary
            [Complex::fromInt(0, 1), 1, Complex::fromInt(0, 1)],    // (i)^1 = i
            [Complex::fromInt(0, 1), 2, Complex::fromInt(-1, 0)],   // (i)^2 = -1
            [Complex::fromInt(0, 1), 3, Complex::fromInt(0, -1)],   // (i)^3 = -i
            [Complex::fromInt(0, 1), 4, Complex::fromInt(1, 0)],    // (i)^4 = 1

            // Complex
            [Complex::fromInt(1, 1), 1, Complex::fromInt(1, 1)], // (1,1)^1 = (1,1)
            [Complex::fromInt(1, 1), 2, Complex::fromInt('1.2246467991474E-16', 2)], // (1,1)^2 = (1.2246467991474E-16,2)
        ];
    }
}

