<?php

namespace Tests\ArbitraryPrecisionComplex;

use ArbitraryPrecisionComplex\DecimalFunction;
use Decimal\Decimal;

class DecimalFunctionShould extends BaseTestClass {
    /**
     * @test
     * @dataProvider atan_samples
     * @param $number
     */
    public function should_calculate_the_arc_tangent_of_a_real_number_with_standard_php_atan_function($number) {
        $n = new Decimal($number);

        $this->assertEqualsWithDelta(
            \atan($number),
            DecimalFunction::atan(new Decimal($number))->toFloat(),
            0.00000000000001
        );
    }

    /**
     * @test
     * @dataProvider atan_samples
     * @param $number
     * @param $atanWithMaclaurin
     */
    public function should_calculate_the_arc_tangent_of_a_real_number_using_maclaurin_series($number, $atanWithMaclaurin) {

        $this->assertEquals(
            $atanWithMaclaurin,
            DecimalFunction::atanUsingMaclaurinSeries(new Decimal($number))->toString()
        );
    }

    public function atan_samples() {
        return [
            [0, '0E-31'], // 0
            [1, '0.7856479135848857627252819354'], // PI/4
            [-1, '-0.7856479135848857627252819354'],
        ];
    }
}