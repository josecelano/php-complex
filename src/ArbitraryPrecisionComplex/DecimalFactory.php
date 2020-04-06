<?php

namespace ArbitraryPrecisionComplex;

use Decimal\Decimal;

class DecimalFactory {
    const PRECISION = 28;

    /**
     * @param int|float|string $primitive
     * @param int $precision
     * @return Decimal
     */
    public static function from($primitive, int $precision = self::PRECISION) {
        return new Decimal($primitive, $precision);
    }

    public static function zero(int $precision = self::PRECISION): \Decimal\Decimal {
        return new \Decimal\Decimal('0', $precision);
    }

    public static function pi(int $precision = self::PRECISION): \Decimal\Decimal {
        return new \Decimal\Decimal(Pi::value($precision), $precision);
    }
}