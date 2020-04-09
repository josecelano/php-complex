<?php

namespace ArbitraryPrecisionComplex;

use Decimal\Decimal;

class Complex {
    /** @var Decimal */
    private $real;

    /** @var Decimal */
    private $imaginary;

    public static function fromInt(int $real, int $imaginary) {
        return new self(DecimalFactory::from($real), DecimalFactory::from($imaginary));
    }

    public function __construct(Decimal $real, Decimal $imaginary) {
        if ($real->precision() != $imaginary->precision()) {
            throw new \InvalidArgumentException('Real and imaginary parts must have the same precision');
        }
        $this->real = $real;
        $this->imaginary = $imaginary;
    }

    public function getReal(): Decimal {
        return $this->real;
    }

    public function getImaginary(): Decimal {
        return $this->imaginary;
    }

    public function equals(Complex $c): Bool {
        return $this->getReal()->equals($c->getReal())
            && $this->getImaginary()->equals($c->getImaginary());
    }

    public function compareTo(Complex $c, Decimal $delta): Bool {
        $realDiff = $this->getReal()->sub($c->getReal());
        $imaginaryDiff = $this->getReal()->sub($c->getReal());
        return $realDiff < $delta && $imaginaryDiff < $delta;
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

    public function __toString() {
        if ($this->isReal()) {
            return $this->getReal()->toString();
        }

        if ($this->getImaginary()->isZero()) {
            return $this->getReal()->toString();
        }

        if ($this->getReal()->isZero()) {
            return $this->getImaginary()->toString() . 'i';
        }

        if ($this->getImaginary()->isNegative()) {
            return $this->getReal()->toString() . $this->getImaginary()->toString() . 'i';
        }

        return $this->getReal()->toString() . '+' .  $this->getImaginary()->toString() . 'i';
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

    /**
     * Returns a complex number raised to a power.
     * @param $power
     * @return Complex
     * @throws \Exception
     */
    public function pow($power) {
        if (!is_numeric($power)) {
            throw new \Exception('Power argument must be a real number');
        }

        // Positive or zero real
        if ($this->getImaginary()->isZero()
            && ($this->getReal()->isZero() || $this->getReal()->isPositive())) {
            $real = $this->getReal()->pow($power);
            return new Complex($real, DecimalFactory::zero());
        }

        $rPower2 = $this->getReal()->mul($this->getReal());
        $iPower2 = $this->getImaginary()->mul($this->getImaginary());

        $rValue = ($rPower2->add($iPower2))->sqrt();
        $rPower = $rValue->pow($power);

        $theta = $this->theta()->mul($power);
        if ($theta->isZero()) {
            // Return 1
            return new Complex(DecimalFactory::one(), DecimalFactory::zero());
        }

        $r = $rPower->mul(DecimalFunction::cos($theta));
        $i = $rPower->mul(DecimalFunction::sin($theta));

        return new Complex($r, $i);
    }
}