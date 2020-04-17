<?php

namespace ArbitraryPrecisionComplex;

/**
 * Class DecimalFunction
 * @package ArbitraryPrecisionComplex
 */
class DecimalFunction {
    /**
     * @param \Decimal\Decimal $x
     * @return \Decimal\Decimal
     * @throws \Exception
     */
    public static function atan(\Decimal\Decimal $x): \Decimal\Decimal {
        return self::atanUsingLinuxBCCommand($x);
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

    /**
     * Sample for atan(1) and precision 28: echo "scale=28;a(1)" | bc -lq
     * @param \Decimal\Decimal $x
     * @return \Decimal\Decimal
     * @throws \Exception
     */
    public static function atanUsingLinuxBCCommand(\Decimal\Decimal $x) {
        return self::callBCFunction($x, 'a');
    }

    /**
     * Sample for sin(1) and precision 28: echo "scale=28;s(1)" | bc -lq
     * @param \Decimal\Decimal $x
     * @return \Decimal\Decimal
     * @throws \Exception
     */
    public static function sin(\Decimal\Decimal $x) {
        return self::callBCFunction($x, 's');
    }

    /**
     * Sample for cos(1) and precision 28: echo "scale=28;s(1)" | bc -lq
     * @param \Decimal\Decimal $x
     * @return \Decimal\Decimal
     * @throws \Exception
     */
    public static function cos(\Decimal\Decimal $x) {
        return self::callBCFunction($x, 'c');
    }

    /**
     * http://manpages.ubuntu.com/manpages/bionic/en/man1/bc.1.html (MATH LIBRARY)
     * @param \Decimal\Decimal $x
     * @param $functionName
     * @return \Decimal\Decimal
     * @throws \Exception
     */
    public static function callBCFunction(\Decimal\Decimal $x, $functionName) {

        $functions = ['s', 'c', 'a', 'l', 'e', 'j'];

        if (!in_array($functionName, $functions)) {
            throw new \Exception('InvalidBC math library function');
        }

        $scaleForCalculation = $x->precision() + 4;

        $cmd = 'echo "scale=' . $scaleForCalculation . ';' . $functionName . '(' . $x->toString() . ')" | BC_LINE_LENGTH=0 bc -lq';
        $output = shell_exec($cmd);
        $value = $output;

        // Remove last line break
        $value = rtrim($value);

        // Add leading zero
        if (substr($value, 0, 1) == '.') {
            $value = '0' . $value;
        }
        if (substr($value, 0, 2) == '-.') {
            $value = '-0' . substr($value, 1);
        }

        /*
        // DEBUG
        echo "\nCMD: $cmd";
        echo "\nOUT: $output";
        echo "VAL: $value";
        echo "\n";
        */

        return DecimalFactory::truncatedDecimal($value);
    }
}