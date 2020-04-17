<?php

namespace Tests\ArbitraryPrecisionComplex\Operation;

use ArbitraryPrecisionComplex\Complex;
use Tests\ArbitraryPrecisionComplex\BaseTestClass;

class MultiplicationTest extends BaseTestClass {

    /** @test */
    public function multiplying_by_a_real_number() {
        $c = Complex::fromInt(13, 5);
        $r = Complex::fromInt(-4, 0);

        $f = $c->multiply($r);

        $this->assertComplexEquals(Complex::fromInt(-52, -20), $f);
    }

    /** @test */
    public function multiplying_by_purely_imaginary_number() {
        $c = Complex::fromInt(3, -8);
        $r = Complex::fromInt(0, 2);

        $f = $c->multiply($r);

        $this->assertComplexEquals(Complex::fromInt(16, 6), $f);
    }

    /** @test */
    public function multiplying_by_another_complex_number() {
        $c = Complex::fromInt(1, 4);
        $r = Complex::fromInt(5, 1);

        $f = $c->multiply($r);

        $this->assertComplexEquals(Complex::fromInt(1, 21), $f);
    }
}

