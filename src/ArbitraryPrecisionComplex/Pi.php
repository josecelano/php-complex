<?php

namespace ArbitraryPrecisionComplex;

class Pi {

    // http://mimosa.pntic.mec.es/jgomez53/matema/conocer/pi_1500.htm
    const CONSTANT = '3.1415926535897932384626433832795028841971693993751058209749445923078164062862089986280348253421170679821480865132823066470938446095505822317253594081284811174502';

    const DEFAULT_PRECISION = 28;

    public static function value(int $precision = self::DEFAULT_PRECISION): \Decimal\Decimal {
        $pi = self::piWithPrecision($precision);
        return new \Decimal\Decimal($pi, $precision);
    }

    /**
     * @param int $precision
     * @return string
     */
    private static function piWithPrecision(int $precision): string {
        return '3.' . substr(self::CONSTANT, 2, $precision - 1);
    }
}