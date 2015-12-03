<?php

namespace App;

use App\Dictionary\Wiktionary;

abstract class Word
{

    /**
     * @param $word
     * @return mixed
     */
    public static function checkWord($word)
    {

        return Wiktionary::checkWord($word);

    }

    private function checkPages()
    {

    }
}
