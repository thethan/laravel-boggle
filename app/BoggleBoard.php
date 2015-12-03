<?php


namespace App;


use App\Dictionary\Wiktionary;
use Illuminate\Support\Facades\Request;

class BoggleBoard
{


    protected $size = 16;

    protected $boardHeight = 4;

    protected $boardWidth = 4;

    protected $squares = array();

    protected $words = array();

    public $lettersLong = 3;

    protected $inUse, $word, $allUses, $wordPool;

    /**
     * Generate a board
     *
     * @return array
     */
    public function __construct($size = 16)
    {

        $squares = [];

        for ($i = 1; $i <= $size; $i++) {
            $squares[$i - 1] = new Square($i, $this->boardWidth, $this->boardHeight);
        }

        $this->squares = $squares;


    }

    public function getBoard()
    {
        return $this->squares;
    }


    public function getWords()
    {
        return $this->words;
    }


    protected function appendInUse($array, $id)
    {
        if (in_array($id, $array)) {
            return false;
        } else {
            $array[] = $id;
            $this->appendAllUses($array);

            return true;
        }
    }

    protected function appendAllUses($array)
    {
        if (!in_array($array, $this->allUses)) {
            $this->allUses[] = $array;
            $this->checkArrayForWord($array);

        }
    }

    protected function checkArrayForWord($array)
    {
        $word = '';
        foreach ($array as $id) {
            $square = $this->getSquare($id);
            $word .= $square->letter;
        }
        $this->word = $word;
        $this->checkWord($word);
    }

    /**
     * Loop through the available patterns and determins if in use
     */
    public function solve()
    {
        $this->allUses = [];
        $this->wordPool = [];
        foreach ($this->squares as $square) {

            $this->inUse = [$square->id];
            $this->appendInUse([], $square->id);

            //for the ease of it.
            for ($i = 1; $i <= 4; $i++) {

                $this->loopThroughAdjacent(1);
            }

            exit;
        }

    }


    /**
     * @param $position
     * @param $adjacent
     * @return mixed
     */
    protected function loopThroughAdjacent($position)
    {
        foreach ($this->allUses as $use) {
            $last = last($use);
            $square = $this->getSquare($last);
            foreach ($square->adjacent as $adjacent) {

                $this->appendInUse($use, $adjacent);
            }


        }
    }

    /**
     * Checks to the dictionary to see if word exists.
     */
    protected function checkWord()
    {
        if (strlen($this->word) >= 3) {
            if (!empty(Word::checkWord($this->word))) {
                $this->words[] = $this->word;
            }
        }
    }

    /**
     * Pass in the Id and it will return the square based on the index.
     * @param $id
     * @return mixed
     */
    public function getSquare($id)
    {
        $index = $id - 1;
        return $this->squares[$index];
    }


}
