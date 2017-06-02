<?php
/*
 * MIT License
 *
 * Copyright (c) 2017 Eugene Bogachov
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

require_once getcwd().'/vendor/autoload.php';

class TestData {
    /** @var string */
    private $somestring;
    /** @var int */
    protected $someint;
    /** @var TestLayer2[] */
    private $layers = [];
    /** @var TestLayer2 */
    private $layer;
    /** @var Sex */
    private $sex;
    /** @var DateTimeImmutable */
    private $birthdate;

    public function getLayer(): TestLayer2 {
        return $this->layer;
    }

    public function getSex(): Sex {
        return $this->sex;
    }
}

class TestLayer2 {
    /** @var string */
    private $name;

    public function getName(): string {
        return $this->name;
    }
}

class Sex extends \PhX\Util\Enum {
    const man = 'man',
        woman = 'woman';
}


$data = [
    'somestring' => 'some text',
    'someint' => 1235,
    'layers' => [
        ['name'=> 'layer 1'],
        ['name'=> 'layer 18']
    ],
    'sex' => 'man',
    'layer' => ['name'=> 'the one'],
    'birthdate' => '20-07-1984'
];


//function instantiate(string $class, $data) {
//    $reflector = new ReflectionClass($class);
//    $object = $reflector->newInstanceWithoutConstructor();
//
//    if ($object instanceof \Granule\Util\Enum) {
//        return $class::fromValue($data);
//    }
//
//    foreach ($reflector->getProperties() as $property) {
//        $name = $property->getName();
//        $type = getDocStatement($property, 'var');
//        $value = evaluate($type, $data[$name]);
//
//        if (!array_key_exists($name, $data)) {
//            throw new \InvalidArgumentException($name);
//        }
//
//        if (!$property->isPublic()) {
//            $property->setAccessible(true);
//        }
//
//
//        $property->setValue($object, $value);
//    }
//
//    return $object;
//}
//
//function getDocStatement(ReflectionProperty $reflectionProperty, string $statement): ?string {
//    $regexp = sprintf('/@%s\s+([\\a-zA-Z0-9_?]+)\s+[.*]/s', $statement);
//    if (preg_match_all($regexp, $reflectionProperty->getDocComment(), $matches)) {
//        return $matches[1][0];
//    }
//    return null;
//}
//
//function evaluate(string $type, $value) {
//    if ($type === 'string' && !is_string($value)) {
//        return (string) $value;
//    } else if (in_array($type, ['integer', 'int']) && !is_integer($value)) {
//        return (int) $value;
//    } else if (in_array($type, ['float', 'double']) && !is_float($value)) {
//        return (float) $value;
//    } else if ($type == 'iterable' && !is_iterable($value)) {
//        return (array) $value;
//    } else if (substr($type, -2) === '[]') {
//        echo substr($type, -2), "\n";
//        $list = [];
//        $fixedType = substr($type, 0, -2);
//        foreach ($value as $key => $item) {
//            $list[$key] = evaluate($fixedType, $item);
//        }
//        return $list;
//    } else if (class_exists($type)) {
//        return instantiate($type, $value);
//    }
//
//    return $value;
//}

//$ev = new \Granule\DataBind\TypeCast\Evaluator\EnumEvaluator(
//    new \Granule\DataBind\TypeCast\Evaluator\PrimitiveTypeEvaluator(
//        new \Granule\DataBind\TypeCast\Evaluator\BasicObjectEvaluator(
//            new \Granule\DataBind\TypeCast\Fetcher\Accessor(
//                new \Granule\DataBind\TypeCast\Fetcher\DocCommentTypeFetcher()
//            )
//        )
//    )
//);

/** @var TestData $o */
//$o = evaluate(TestData::class, $data);
//$o = $ev->evaluate(\Granule\DataBind\TypeCast\Type::fromSignature(TestData::class), $data);

//print_r($o);


$resolver = \Granule\DataBind\Cast\Serialization\DependencyResolver::builder()
    ->add(new \Granule\DataBind\Cast\Serialization\EnumSerializer())
    ->add(new \Granule\DataBind\Cast\Serialization\MapSerializer())
    ->build();

$converter = new \Granule\DataBind\Converter($resolver);


//$type = \Granule\DataBind\Type\Full::fromSignature(TestData::class);
//$serializer = $cnv->resolve($type);
//$deserealized = $serializer->unserialize($data, $type);
//$serialized = $serializer->serialize($deserealized);

$deserealized = $converter->fromArray($data)->toObject(TestData::class);
$serialized = $converter->fromObject($deserealized)->toSimpleType();


print_r($deserealized);
print_r($serialized);
print_r($data);
echo ' ===> ', ($data == $serialized);




