<?php

namespace ArbitraryPrecisionComplex;

use Decimal\Decimal;

class Complex {
    /** @var Decimal */
    private $real;

    /** @var Decimal */
    private $imaginary;

    public function __construct(Decimal $real, Decimal $imaginary) {
        $this->real = $real;
        $this->imaginary = $imaginary;
    }

    public function getReal(): Decimal {
        return $this->real;
    }

    public function getImaginary(): Decimal {
        return $this->imaginary;
    }

    public function add(Complex $c): Complex {
        return new self(
            $this->getReal()->add($c->getReal()),
            $this->getImaginary()->add($c->getImaginary())
        );
    }

    /**
     * Returns true if this is a real value, false if a complex value.
     */
    public function isReal(): Bool {
        return $this->imaginary->isZero();
    }

    /**
     * Returns true if this is a complex value, false if a real value
     */
    public function isComplex(): Bool {
        return !$this->isReal();
    }

    /**
     * Returns the theta of a complex number.
     * This is the angle in radians from the real axis to the representation of the number in polar coordinates.
     */
    function theta(): Decimal {

        if ($this->getReal()->isZero()) {
            // real zero (imaginary axis)
            if ($this->isReal()) {
                // (0,0)
                return DecimalFactory::zero();
            } elseif ($this->getImaginary()->isNegative()) {
                // (0,-1)
                return Pi::value()->div(-2);
            }
            // (0,1)
            return Pi::value()->div(2);
        } elseif ($this->getReal()->isPositive()) {
            // real positive
            // (1,1) (1,-1)
            return DecimalFunction::atan($this->getImaginary()->div($this->getReal()));
        } elseif ($this->getImaginary()->isNegative()) {
            // imaginary negative
            // (-1,-1)
            $absImaginary = $this->getImaginary()->abs();
            $absReal = $this->getReal()->abs();
            return (Pi::value()->sub(DecimalFunction::atan($absImaginary->div($absReal))))->negate();
        }
        // real negative
        // (-1,1)
        return Pi::value()->sub(DecimalFunction::atan($this->getImaginary()->div($this->getReal()->abs())));
    }
}