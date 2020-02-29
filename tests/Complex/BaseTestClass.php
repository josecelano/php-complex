<?php

namespace Tests\Complex;

use PHPUnit\Framework\TestCase;

class BaseTestClass extends TestCase {
    protected $object;

    protected function expect($object) {
        $this->object = $object;
        return $this;
    }

    protected function toBe($object) {
        $this->assertEquals($this->object, $object);
    }
}