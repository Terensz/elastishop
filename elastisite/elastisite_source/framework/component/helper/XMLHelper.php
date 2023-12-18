<?php
namespace framework\component\helper;

class XMLHelper
{
    public static function createSimpleXML(string $xmlString)
    {
        // echo '<pre>';
        // echo $xmlString;exit;
        return simplexml_load_string($xmlString);
        // return simplexml_load_string($xmlString, \SimpleXMLElement::class, LIBXML_NOCDATA);
    }

    // public static function formatXML(\SimpleXMLElement $simpleXml) : ? string
    // {
    //     $replyData = json_decode(json_encode($simpleXml, false));

    //     return $replyData;
    // }

    // public static function createAPIXML(string $xmlString) : ? string
    // {
    //     $simpleXml = self::createSimpleXML($xmlString);

    //     return $simpleXml ? self::formatXML($simpleXml) : null;
    // }
}
