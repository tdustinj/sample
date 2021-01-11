<?php
namespace App\Utilities;

use SimpleXMLElement;

abstract class XmlHelper
{
    // returns the string value of the xmlElement's childNode if it is set, or null if it is not set.
    public static function xmlValueOrNull(SimpleXMLElement $xmlElement, $childNodeName)
    {
        return (
            isset($xmlElement->{$childNodeName})
                ? (string) $xmlElement->{$childNodeName}
                : null
        );
    }
}

?>