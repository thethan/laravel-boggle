<?php

namespace App;

use App\Dictionary\TextDictionary;
use App\Dictionary\Wiktionary;

class Word
{

    public $dictionary;
    /**
     * Word constructor.
     *
     * Did this to save memory by not initializing its own class everytime.
     */
    public function __construct()
    {
        $file = file_get_contents(base_path() . '/resources/dictionary.txt', "r");

        $this->dictionary = explode("\n", $file);

    }

    /**
     * @param $word
     * @return string|false
     */
    public function checkWord($word)
    {
        if(in_array($word, $this->dictionary)){
            return $word;
        }
        return false;

    }
}
