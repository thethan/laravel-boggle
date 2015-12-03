<?php

namespace App\Dictionary;
/**
 * Incase we use different dictionaries for words.
 *
 * Interface DictionaryInterface
 *
 *
 * @package App\Dictionary
 */
interface DictionaryInterface
{

    /**
     * @param $word
     * @return string|null
     */
    public static function checkWord($word);

}