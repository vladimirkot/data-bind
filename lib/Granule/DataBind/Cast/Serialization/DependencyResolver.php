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

namespace Granule\DataBind\Cast\Serialization;

use Granule\DataBind\Cast\Detection\{AccessorTypeDetector, PropertyDocCommentDetector};
use Granule\DataBind\Cast\Type;

class DependencyResolver {
    /** @var Serializer[] */
    private $serializers = [];

    public function __construct(DependencyResolverBuilder $builder) {
        $this->serializers = $builder->getSerializers();

        foreach ($this->serializers as $serializer) {
            if ($serializer instanceof DependencyResolverAware) {
                $serializer->setResolver($this);
            }
        }
    }

    public static function emptyBuilder(): DependencyResolverBuilder {
        return new DependencyResolverBuilder();
    }

    public static function builder(): DependencyResolverBuilder {
        return static::emptyBuilder()
            ->add(new DateTimeSerializer())
            ->add(new PrimitiveTypeSerializer())
            ->add(new CollectionSerializer())
            ->add(new MapSerializer())
            ->add(new EnumSerializer())
            ->addBottom(new POPOSerializer(
                new AccessorTypeDetector(
                    new PropertyDocCommentDetector()
                )
            ));
    }

    public function resolve(Type $type): Serializer {
        foreach ($this->serializers as $serializer) {
            if ($serializer->matches($type)) {
                return $serializer;
            }
        }

        throw SerializerNotFoundException::fromType($type);
    }
}