<?php

namespace Granule\DataBind\Tests;

use Granule\DataBind\Cast\Serialization\DependencyResolver;
use Granule\DataBind\Converter;
use Granule\DataBind\Tests\fixtures\TestInternalObject;
use Granule\DataBind\Tests\fixtures\TestObject;
use PHPUnit\Framework\TestCase;

/**
 * @group integration
 */
class ConverterTest extends TestCase {

    /** @var Converter */
    private static $converter;

    public static function setUpBeforeClass() {
        $resolver = DependencyResolver::builder()->build();
        self::$converter = new Converter($resolver);
    }

    public function getFixture(): array {
        return [[[
            'somestring' => 'some text',
            'someint' => 1235,
            'layers' => [
                ['name'=> 'layer 1'],
                ['name'=> 'layer 18']
            ],
            'layer' => ['name'=> 'the one'],
            'birthdate' => 'Friday, 20-Jul-84 00:00:00 UTC'
        ]]];
    }

    /**
     * @test
     * @dataProvider getFixture
     *
     * @param array $fixture
     */
    public function is_should_deserialize_basic_structure(array $fixture): void {
        /** @var TestObject $deserealized */
        $deserealized = self::$converter
            ->fromArray($fixture)
            ->toObject(TestObject::class);

        $this->assertInstanceOf(TestObject::class, $deserealized);
        $this->assertInstanceOf(TestInternalObject::class, $deserealized->getLayer());

        $reflector = new \ReflectionClass($deserealized);

        $somestring = $reflector->getProperty('somestring');
        $somestring->setAccessible(true);
        $value = $somestring->getValue($deserealized);
        $this->assertTrue(is_string($value));
        $this->assertEquals('some text', $value);

        $someint = $reflector->getProperty('someint');
        $someint->setAccessible(true);
        $value = $someint->getValue($deserealized);
        $this->assertTrue(is_integer($value));
        $this->assertEquals(1235, $value);

        $layers = $reflector->getProperty('layers');
        $layers->setAccessible(true);
        $value = $layers->getValue($deserealized);
        $this->assertTrue(is_array($value));
        $this->assertEquals(2, count($value));
        $this->assertInstanceOf(TestInternalObject::class, $value[0]);
        $this->assertEquals('layer 1', $value[0]->getName());

        $layer = $reflector->getProperty('layer');
        $layer->setAccessible(true);
        $value = $layer->getValue($deserealized);
        $this->assertInstanceOf(TestInternalObject::class, $value);
        $this->assertEquals('the one', $value->getName());
    }

    /**
     * @test
     * @dataProvider getFixture
     * @depends is_should_deserialize_basic_structure
     *
     * @param array $fixture
     */
    public function is_should_serialize_basic_object(array $fixture): void {
        /** @var TestObject $deserealized */
        $deserealized = self::$converter
            ->fromArray($fixture)
            ->toObject(TestObject::class);

        $serialized = self::$converter
            ->fromObject($deserealized)
            ->toSimpleType();

        $this->assertTrue(is_array($serialized));
        $this->assertTrue($serialized == $fixture);
    }
}