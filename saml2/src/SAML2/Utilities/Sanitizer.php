<?php

/**
 * Various File Utilities
 */
class SAML2_Utilities_Sanitizer
{
    static private $htmlEvents = [
        "onafterprint",
        "onbeforeprint",
        "onbeforeunload",
        "onerror",
        "onhashchange",
        "onload",
        "onmessage",
        "onoffline",
        "ononline",
        "onpagehide",
        "onpageshow",
        "onpopstate",
        "onresize",
        "onstorage",
        "onunload",
        "onblur",
        "onchange",
        "oncontextmenu",
        "onfocus",
        "oninput",
        "oninvalid",
        "onreset",
        "onsearch",
        "onselect",
        "onsubmit",
        "onkeydown",
        "onkeypress",
        "onkeyup",
        "onclick",
        "ondblclick",
        "onmousedown",
        "onmousemove",
        "onmouseout",
        "onmouseover",
        "onmouseup",
        "onmousewheel",
        "onwheel",
        "ondrag",
        "ondragend",
        "ondragenter",
        "ondragleave",
        "ondragover",
        "ondragstart",
        "ondrop",
        "onscroll",
        "oncopy",
        "oncut",
        "onpaste",
        "onabort",
        "oncanplay",
        "oncanplaythrough",
        "oncuechange",
        "ondurationchange",
        "onemptied",
        "onended",
        "onerror",
        "onloadeddata",
        "onloadedmetadata",
        "onloadstart",
        "onpause",
        "onplay",
        "onplaying",
        "onprogress",
        "onratechange",
        "onseeked",
        "onseeking",
        "onstalled",
        "onsuspend",
        "ontimeupdate",
        "onvolumechange",
        "onwaiting",
        "onshow",
        "ontoggle"
    ];

    /**
     * Protège une chaîne de caractère contre les injections XSS
     *
     * @param  string $string
     * @return string
     */
    static function sanitize($string)
    {
        $noScript = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $string);
        $result = str_replace('javascript:', '', $noScript);

        foreach (self::$htmlEvents as $event) {
            $result = str_replace($event, 'noXSS', $result);
        }

        return $result;
    }

    /**
     * Protège une chaîne de caractère contre les injections XSS
     *
     * @param  array $array
     * @return array
     */
    static function sanitizeArray($array)
    {
        $sanitizedArray = [];

        foreach($array as $key => $value) {
            $sanitizedArray[$key] = self::sanitize($value);
        }

        return $sanitizedArray;
    }
}
