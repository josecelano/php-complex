<?php

namespace Tests\ArbitraryPrecisionComplex;

use ArbitraryPrecisionComplex\DecimalFactory;

class DecimalFactoryShould extends BaseTestClass {

    /** @test */
    public function should_truncate_results_to_the_default_precision() {
        // Using default precision 28
        $number = '-1.1234567891234567891234567891';

        $decimal = DecimalFactory::truncatedDecimal($number);

        $this->assertEquals('-1.12345678912345678912345678', $decimal->toString());
    }
}