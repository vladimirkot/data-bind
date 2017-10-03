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

namespace Granule\DataBind\Extractor;

use Granule\DataBind\Extractor;
use Granule\DataBind\XMLHelper;

abstract class BasicExtractor implements Extractor {
    public function toYamlString(): string {
        return yaml_emit($this->toSimpleType());
    }

    public function toYamlFile(string $path): void {
        file_put_contents($path, $this->toYamlString());
    }

    public function toJsonString(): string {
        return json_encode($this->toSimpleType());
    }

    public function toJsonFile(string $path): void {
        file_put_contents($path, $this->toJsonString());
    }

    public function toXml(): string {
//        $xml = new \SimpleXMLElement('<root/>');
//        $xml->registerXPathNamespace();
        $data = $this->toSimpleType();

        return XMLHelper::arrayToXml($data)->asXML();

//        $isAssoc = function (array $array) {
//            if (array() === $array) return false;
//            return array_keys($array) !== range(0, count($array) - 1);
//        };
//
//        $array2xml = function (array $array, \SimpleXMLElement $xml) use (&$array2xml): void {
//            foreach($array as $key => $value){
//                if (is_int($key)) {
//                    $key = 'gdb-element';
//                }
//                if(is_array($value)){
//                    $array2xml($value, $xml->addChild($key));
//                } else {
//                    $xml->addChild($key, $value);
//                }
//            }
//        };
//
//        $array2xml($data, $xml);
//
//        print_r($data);
//
//        return $xml->asXML();
    }
}