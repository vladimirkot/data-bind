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

class DependencyResolverBuilder {
    /** @var Serializer[] */
    private $serializers = [];
    /** @var int[] */
    private $positions = [];

    public function add(Serializer $serializer): DependencyResolverBuilder {
        $this->addWithPosition($serializer, 0);
        return $this;
    }

    public function addTop(Serializer $serializer): DependencyResolverBuilder {
        $this->addWithPosition($serializer, 1);
        return $this;
    }

    public function addBottom(Serializer $serializer): DependencyResolverBuilder {
        $this->addWithPosition($serializer, -1);
        return $this;
    }

    private function addWithPosition(Serializer $serializer, int $position): void {
        $key = get_class($serializer);
        $this->serializers[$key] = $serializer;
        $this->positions[$key] = $position;
    }

    /** @return Serializer[] */
    public function getSerializers(): array {
        $serializers = [];
        $positions = $this->positions;
        arsort($positions);
        foreach ($positions as $key => $_) {
            $serializers[] = $this->serializers[$key];
        }

        return $serializers;
    }

    public function build(): DependencyResolver {
        return new DependencyResolver($this);
    }
}