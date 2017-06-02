<?php

namespace Granule\DataBind\Tests\fixtures;

class TestInternalObject {
    /** @var string */
    private $name;

    public function getName(): string {
        return $this->name;
    }
}