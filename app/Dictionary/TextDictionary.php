<?php

namespace App\Dictionary;

class TextDictionary implements DictionaryInterface
{
    protected $dictionary;
    protected $textFileLocation = '../../resources/dictionary.php';

    public function __construct()
    {
        $this->dictionary = explode("\n\n", readfile($this->textFileLocation));
    }

    public static function checkWord($word)
    {
        $dictionary = self::returnDictionaryArray();

        if(in_array($word, $dictionary)){
            return $word;
        }
        return false;
    }

    protected static function returnDictionaryArray()
    {

        $file = readfile(base_path() . '/resources/dictionary.txt');
        return explode("\r\n", $file);
    }
}