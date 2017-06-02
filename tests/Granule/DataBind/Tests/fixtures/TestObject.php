<?php

namespace Granule\DataBind\Tests\fixtures;

class TestObject {
    /** @var string */
    private $somestring;
    /** @var int */
    protected $someint;
    /** @var TestInternalObject[] */
    private $layers = [];
    /** @var  */
    private $layer;
    /** @var \DateTimeImmutable */
    private $birthdate;

    public function getLayer(): TestInternalObject {
        return $this->layer;
    }
}