<?php

namespace Complex;

use Decimal\Decimal;

class Complex {
    /** @var float */
    private $real;

    /** @var float */
    private $imaginary;

    /**
     * Complex constructor.
     * @param Decimal $real
     * @param Decimal $imaginary
     */
    public function __construct(Decimal $real, Decimal $imaginary) {
        $this->real = $real;
        $this->imaginary = $imaginary;
    }

    /**
     * @return Decimal
     */
    public function getReal() {
        return $this->real;
    }

    /**
     * @return Decimal
     */
    public function getImaginary() {
        return $this->imaginary;
    }
}