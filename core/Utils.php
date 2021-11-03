<?php

namespace Core;

class Utils
{
    /**
     *
     * <h3>get string between any char</h3>
     * <h3>example:</h3>
     *  <p> echo getTextBetweenChar('hello my name is {mister john}', '{', '}') </p>
     *  <p>result: mister john </p>
     *
     * @param string $text
     * @param string $initChar
     * @param string $finalChar
     * @return string
     */
    public static function getTextBetweenChar(string $text, string $initChar, string $finalChar): string
    {
        $in = strpos($text, $initChar) + 1;
        $out = strpos($text, $finalChar);
        $len = $out - $in;
        return  substr($text, $in, $len);
    }
    public static function starts_with(string $text, string $textToSearch): bool
    {
        if(strpos($text, $textToSearch) === 0)
            return true;
        else
            return false;
    }
}