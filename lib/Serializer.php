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

namespace Granule\DataBind;

abstract class Serializer {
    public function unserialize($data, TypeDeclaration $type) {
        $response = null;
        if ($type->isInArray()) {
            if (!is_iterable($data)) {
                throw new \InvalidArgumentException($type->getName());
            }

            foreach ($data as $k => $v) {
                $response[$k] = $this->unserializeItem($v, $type);
            }
        } else {
            $response = $this->unserializeItem($data, $type);
        }

        if (is_null($response) && !$type->isNullable()) {
            throw InvalidDataException::fromTypeAndData($type, null);
        }

        return $response;
    }

    abstract protected function unserializeItem($data, Type $type);

    /**
     * @param mixed $data
     * @return mixed
     */
    abstract public function serialize($data);

    abstract public function matches(Type $type): bool;
}