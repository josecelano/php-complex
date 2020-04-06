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
    public function should_calculate_the_arc_tangent_of_a_real_number_with_standard_atan_function($number) {
        $n = new Decimal($number);

        $this->assertEqualsWithDelta(
            \atan($number),
            DecimalFunction::atan(new Decimal($number))->toFloat(),
            0.00000000000001
        );
    }

    /*
    public function should_calculate_the_arc_tangent_of_a_real_number_using_maclaurin_series($number) {
        $this->assertEquals(
            DecimalFunction::atan(new Decimal($number))->toString(),
            DecimalFunction::atanUsingMaclaurinSeries(new Decimal($number))->toString()
        );
    }*/

    public function atan_samples() {
        return [
            [0], // 0
            [1], // PI/4
            [-1],
        ];
    }
}