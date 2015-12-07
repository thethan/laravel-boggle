<?php

namespace App\Dictionary;

class TextDictionary implements DictionaryInterface
{
    protected $dictionary;


    public static function checkWord($word)
    {
        $dictionary = self::returnDictionaryArray();

    }

    /**
     * make the dictionary into a read
     * @return array
     */
    protected static function returnDictionaryArray()
    {


    }
}