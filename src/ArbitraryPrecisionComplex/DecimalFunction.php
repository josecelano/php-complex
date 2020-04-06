<?php

namespace ArbitraryPrecisionComplex;

class DecimalFunction {
    /**
     * PHP atan function returns a float, so we cannot use it.
     * @param \Decimal\Decimal $x
     * @return \Decimal\Decimal
     */
    public static function atan(\Decimal\Decimal $x): \Decimal\Decimal {
        return DecimalFactory::from((string)\atan($x->toFloat()));
    }

    public static function atanUsingMaclaurinSeries(\Decimal\Decimal $x) {
        $sum = DecimalFactory::zero();
        for($n = 0; $n <= 100; $n++) {
            $a = DecimalFactory::from(pow(-1, $n));
            $b = DecimalFactory::from(2*$n + 1);
            $c = DecimalFactory::from($x->pow($b));
            $seriesItem = ($a->div($b))->mul($c);
            $sum = $sum->add($seriesItem);
        }
        return $sum;
    }
}