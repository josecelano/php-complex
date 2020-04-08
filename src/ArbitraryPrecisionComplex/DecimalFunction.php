<?php

namespace ArbitraryPrecisionComplex;

class DecimalFunction {
    /**
     * PHP atan function returns a float, so we cannot use it.
     * @param \Decimal\Decimal $x
     * @return \Decimal\Decimal
     */
    public static function atan(\Decimal\Decimal $x): \Decimal\Decimal {
        return self::atanUsingStandardPHPFunction($x);
    }

    /**
     * PHP atan function returns a float, so we cannot use it.
     * @param \Decimal\Decimal $x
     * @return \Decimal\Decimal
     */
    public static function atanUsingStandardPHPFunction(\Decimal\Decimal $x): \Decimal\Decimal {
        return DecimalFactory::from((string)\atan($x->toFloat()));
    }

    /**
     * This method is not good for values near 1. It needs a lot of iterations.
     * And I do not know how many iterations you need to reach the desired precision.
     * @param \Decimal\Decimal $x
     * @return \Decimal\Decimal
     */
    public static function atanUsingMaclaurinSeries(\Decimal\Decimal $x) {
        $sum = DecimalFactory::zero();
        for ($n = 0; $n <= 1000; $n++) {
            $nDecimal = DecimalFactory::from($n);

            $a = DecimalFactory::from(-1)->pow($nDecimal); // (-1)^n
            $b = (DecimalFactory::from(2)->mul($nDecimal))->add(1); // 2*n + 1
            $c = $x->pow($b); // x^(2*n+1)

            $seriesItem = ($a->div($b))->mul($c);

            $sum = $sum->add($seriesItem);
        }
        return $sum;
    }
}