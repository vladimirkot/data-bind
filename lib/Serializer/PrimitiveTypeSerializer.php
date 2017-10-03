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

namespace Granule\DataBind\Serializer;

use Granule\DataBind\{
    DependencyResolver, DependencyResolverAware, Serializer, Type, Helper
};

class PrimitiveTypeSerializer extends Serializer implements DependencyResolverAware {
    /** @var DependencyResolver */
    private $resolver;

    public function setResolver(DependencyResolver $resolver): void {
        $this->resolver = $resolver;
    }

    public function matches(Type $type): bool {
        return Helper::isBuiltinType($type->getName());
    }

    public function serialize($data) {
        if (is_iterable($data)) {
            $response = [];
            foreach ($data as $k => $v) {
                $serializer = $this->resolver->resolve(Type::fromData($v));
                $response[$k] = $serializer->serialize($v);
            }

            return $response;
        }

        return $data;
    }

    public function unserializeItem($data, Type $type) {
        $type = $type->getName();
        if (in_array($type, ['bool', 'boolean']) && !is_bool($data)) {
            return (bool) $data;
        } elseif ($type === 'string' && !is_string($data)) {
            return (string) $data;
        } elseif (in_array($type, ['integer', 'int']) && !is_integer($data)) {
            return (int) $data;
        } elseif (in_array($type, ['float', 'double']) && !is_float($data)) {
            return (float) $data;
        } elseif ($type == 'iterable' && !is_iterable($data)) {
            return (array) $data;
        } elseif ($type == 'array' && !is_array($data)) {
            return (array) $data;
        }

        return $data;
    }
}