<?php

namespace Tests\Complex;

use Complex\Complex;
use Decimal\Decimal;

class ComplexNumberShould extends BaseTestClass {
    /** @test */
    public function should_have_a_real_and_imaginary_parts() {
        $c = new Complex(new Decimal("-2"), new Decimal("1"));

        $this->assertTrue((new Decimal("-2"))->equals($c->getReal()));
        $this->assertTrue((new Decimal("1"))->equals($c->getImaginary()));
    }
}

