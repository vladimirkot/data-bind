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

namespace Granule\DataBind\Cast;

use Granule\Util\{StrictTypedKey, StrictTypedValue};
use ReflectionClass;

class Type {
    /** @var string */
    private $name;

    protected function __construct(string $name) {
        $this->name = $name;
    }

    public static function fromData($data): Type {
        return new static(is_object($data) ? get_class($data) : gettype($data));
    }

    public function getName(): string {
        return $this->name;
    }

    public function getSignature(): string {
        return $this->getName();
    }

    public function toFullType(): FullType {
        return FullType::fromName($this->getName());
    }

    public function getReflection(): ReflectionClass {
        return new ReflectionClass($this->getName());
    }

    public function is(string $class): bool {
        return is_a($this->getName(), $class, true);
    }

    public function getKeyType(): ?FullType {
        if ($this->is(StrictTypedKey::class)) {
            /** @var StrictTypedKey $fake */
            $fake = $this->getReflection()
                ->newInstanceWithoutConstructor();

            return FullType::fromName($fake->getKeyType());
        }

        return null;
    }

    public function getValueType(): ?FullType {
        if ($this->is(StrictTypedValue::class)) {
            /** @var StrictTypedValue $fake */
            $fake = $this->getReflection()
                ->newInstanceWithoutConstructor();

            return FullType::fromName($fake->getValueType());
        }

        return null;
    }
}