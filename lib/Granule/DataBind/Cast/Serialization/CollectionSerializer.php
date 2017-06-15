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

use Granule\DataBind\Cast\{FullType, Type};
use Granule\Util\{Collection, StrictTypedValue};

class CollectionSerializer extends Serializer implements DependencyResolverAware {
    /** @var DependencyResolver */
    private $resolver;

    public function setResolver(DependencyResolver $resolver): void {
        $this->resolver = $resolver;
    }

    public function matches(Type $type): bool {
        return $type->is(Collection::class);
    }

    /**
     * @param Collection $object
     * @return array
     */
    public function serialize($object) {
        $data = [];
        if ($vType = FullType::fromData($object)->getValueType()) {
            $serializer = $this->resolver->resolve($vType);
            foreach ($object->toArray() as $item) {
                $data[] = $serializer->serialize($item);
            }
        } else {
            foreach ($object->toArray() as $item) {
                $data[] = $this->resolver->resolve(
                    FullType::fromData($item)
                )->serialize($item);
            }
        }

        return $data;
    }

    protected function unserializeItem($data, Type $type) {
        if ($vType = $type->getValueType()) {
            /** @var Collection\CollectionBuilder $builder */
            $builder = call_user_func([$type->getName(), 'builder']);
            $vSerializer = $this->resolver->resolve($vType);
            foreach ($data as $v) {
                $builder->add($vSerializer->unserialize($v, $vType));
            }

            return $builder->build();
        }

        return call_user_func([$type->getName(), 'fromArray'], $data);
    }
}