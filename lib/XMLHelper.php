<?php

namespace Granule\DataBind;

final class XMLHelper {
    public static function isAssoc(array $array): bool {
        if (array() === $array) {
            return false;
        }

        return array_keys($array) !== range(0, count($array) - 1);
    }

    public static function arrayToXml(array $array): \SimpleXMLElement {
        if (self::isAssoc($array)) {
            return self::assocArrayToXml($array, new \SimpleXMLElement("<gdb-element/>"));
        } else {
            return self::indexedArrayToXml($array, new \SimpleXMLElement("<gdb-collection/>"));
        }
    }

    private static function assocArrayToXml(array $array, \SimpleXMLElement $xml): \SimpleXMLElement {
        foreach($array as $key => $value){
            if(is_array($value)){
                if (self::isAssoc($value)) {
                    self::assocArrayToXml($value, $xml->addChild($key));
                } else {
                    self::indexedArrayToXml($value, $xml->addChild($key));
                }
            } else {
                $xml->addChild($key, $value);
            }
        }

        return $xml;
    }

    private static function indexedArrayToXml(array $array, \SimpleXMLElement $xml): \SimpleXMLElement {
        foreach($array as $value){
            if(is_array($value)){
                if (self::isAssoc($value)) {
                    self::assocArrayToXml($value, $xml->addChild('gdb-element'));
                } else {
                    self::indexedArrayToXml($value, $xml->addChild('gdb-elements'));
                }
            } else {
                $xml->addChild('gdb-element', $value);
            }
        }

        return $xml;
    }
}