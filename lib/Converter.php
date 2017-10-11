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

use Granule\DataBind\{
    Extractor\ClassExtractor, Extractor\ClassListExtractor, Extractor\ScalarExtractor, Injector\BasicInjector
};

/** Conversion factory class */
class Converter {
    /** @var DependencyResolver */
    private $resolver;

    public function __construct(DependencyResolver $resolver) {
        $this->resolver = $resolver;
    }

    public function fromArray(array $data): Injector {
        return new BasicInjector($data, $this->resolver);
    }

    public function fromJSON(string $json): Injector {
        return new BasicInjector(json_decode($json, true), $this->resolver);
    }

    public function fromJSONFile(string $jsonFile): Injector {
        return self::fromJSON(file_get_contents($jsonFile));
    }

    public function fromXML(string $xml): Injector {
        throw new \Exception('Panding implementation');
    }

    public function fromXMLFile(string $xmlFile): Injector {
        return self::fromXML(file_get_contents($xmlFile));
    }

    public function fromObject($object): Extractor {
        return new ClassExtractor($this->resolver, $object);
    }

    public function fromExtractable($extractable): Extractor {
        if (is_iterable($extractable)) {
            return $this->fromObjectList($extractable);
        } elseif (is_object($extractable)) {
            return $this->fromObject($extractable);
        } elseif (is_scalar($extractable)) {
            return new ScalarExtractor($extractable);
        }
    }

    public function fromObjectList(iterable $objects): Extractor {
        return new ClassListExtractor($this->resolver, $objects);
    }
}