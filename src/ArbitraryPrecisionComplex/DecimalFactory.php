<?php

namespace ArbitraryPrecisionComplex;

use Decimal\Decimal;

class DecimalFactory {
    const DEFAULT_PRECISION = 28;

    /**
     * @param int|float|string $primitive
     * @param int $precision
     * @return Decimal
     */
    public static function from($primitive, int $precision = self::DEFAULT_PRECISION) {
        return new Decimal($primitive, $precision);
    }

    public static function zero(int $precision = self::DEFAULT_PRECISION): Decimal {
        return new Decimal('0', $precision);
    }

    public static function one(int $precision = self::DEFAULT_PRECISION): Decimal {
        return new Decimal('1', $precision);
    }

    public static function pi(int $precision = self::DEFAULT_PRECISION): Decimal {
        return new Decimal(Pi::value($precision), $precision);
    }

    /**
     * precision vs scale problem
     *
     * We are using the console command bc. You can define the scale for operations, but scale it's not the same as
     * precision. We need to truncate results from 'bc' command otherwise we get the error:
     *
     * "Warning: Loss of data on string conversion in ... on line "
     *
     * See:https://php-decimal.io/#introduction
     * Precision is defined as the number of significant figures, and scale is the number of digits behind
     * the decimal point. This means that a number like 1.23E-1000 would require a scale of 1002 but a precision of 3.
     * This library uses precision; bcmath uses scale.
     *
     * @param $value
     * @param int $precision
     * @return Decimal
     */
    public static function truncatedDecimal($value, int $precision = self::DEFAULT_PRECISION) {

        $significantFiguresNum = self::countSignificantFigures($value);

        if ($significantFiguresNum > $precision) {
            $value = substr($value, 0, strlen($value) - ($significantFiguresNum - $precision));
        }

        return new Decimal($value, $precision);
    }

    /**
     * @param $value
     * @return int
     */
    protected static function countSignificantFigures($value): int {

        // Remove minus
        $value = ltrim($value, '-');

        // Remove decimal point
        $value = ltrim($value, '.');

        return strlen($value);
    }
}