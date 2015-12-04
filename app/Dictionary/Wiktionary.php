<?php

namespace App\Dictionary;


abstract class Wiktionary implements DictionaryInterface
{
    protected $output;

    public static function checkWord($word)
    {
        $output = self::call($word);
        if(self::checkPage($output)){
            return $word;
        }
    }

    protected static function call($word)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://en.wiktionary.org/w/api.php?action=query&titles=$word&format=json");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        $output = json_decode($output);
        curl_close($ch);

        return $output;
    }

    protected static function checkPage($output)
    {
        // -1 is the Page of the pages if not exist
        if( property_exists($output->query->pages, '-1')) {
            return false;
        } else {
            return true;
        }
        //Want to make sure we are returning the same word
//        $obj = get_object_vars($output->query->pages);
//
//        $key = key($obj);
//
//        return $obj[$key]->title;


    }
}