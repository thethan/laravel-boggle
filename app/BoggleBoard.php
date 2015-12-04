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

    protected $checked = array();

    public $words = array();

    public $lettersLong = 3;

    protected $inUse, $word, $allUses, $wordPool, $wordArray;

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

    public function clearInUse($placement = 1)
    {
        var_dump($this->inUse);
        for ($i = $placement; $i < $this->size; $i++) {
            unset($this->inUse[$i]);
        }
    }

    public function clearWord($placement = 1)
    {
        for ($i = $placement; $i < $this->size; $i++) {
//            var_dump($this->word);
            unset($this->word[$i]);
        }
    }

    /**
     * Loop through the available patterns and determins if in use
     */
    public function solve()
    {
        $this->allUses = [];
        $this->wordPool = [];
        var_dump(count($this->squares));

        $this->word = [];
        $this->inUse = [];

        foreach ($this->squares as $square) {
            $this->clearInUse(1);
            $this->clearWord(1);
            $this->inUse[1] = $square->id;
            $this->word[1] = $square->letter;
            if ($square->id < 11) {
                print "$square->id \n";

                $placement = 2;
                for ($i = 0; $i < count($square->adjacent); $i++) {
                    $adjacent = $square->adjacent[$i];
                    if (!in_array($adjacent, $this->inUse)) {
                        $this->clearInUse($placement);
                        $this->clearWord($placement);

                        $square_two = $this->getSquare($adjacent);
                        var_dump('letter', $square_two);
                        $this->inUse[$placement] = $square_two->id;

                        $this->word[$placement] = $square_two->letter;


                        for ($two = 0; $two < count($square_two->adjacent); $two++) {
                            $placement = 3;
                            $adjacent_two = $square_two->adjacent[$two];

                            $square_three = $this->getSquare($adjacent_two);
                            var_dump('adjacent', $square_two);
                            if (!in_array($adjacent_two, $this->inUse) && in_array($adjacent_two, $square_two->adjacent)) {

                                $this->clearInUse($placement);
                                $this->clearWord($placement);

                                $this->inUse[$placement] = $square_three->id;
                                $this->word[$placement] = $square_three->letter;


                                $word = implode('', $this->word);

                                $this->checkWord($word);


//                                for ($three = 0; $three < count($square_three->adjacent); $three++) {
//                                    $placement = 4;
//                                    $adjacent = $square_three->adjacent[$three];
//                                    if (!in_array($adjacent, $this->inUse)) {
//                                        $this->clearInUse($placement);
//                                        $this->clearWord($placement);
//                                        $this->inUse[$placement] = $adjacent;
//
//                                        $square_four = $this->getSquare($adjacent);
//                                        $this->word[$placement] = $square_four->letter;
//
//
//                                        $word = implode('', $this->word);
//
//                                        $this->checkWord($word);
//
//                                        print 4 . "\n";
//                                    }
//                                }


                            }
                        }
                    }

                }
            }
        }
        var_dump($this->words);
        exit;

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

    public function squareAdjacent()
    {
        foreach ($this->squares as $square) {
            return $square;
        }
    }

    /**
     * Checks to the dictionary to see if word exists.
     */
    protected function checkWord($word)
    {
        if (strlen($word) >= 3 && !in_array($word, $this->checked)) {
            if (!empty(Word::checkWord($word))) {
                $this->words[] = $word;
            }
        }
        $this->checked[] = $word;
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

    public function getLetter($id)
    {
        $square = $this->getSquare($id);
        return $square->letter;
    }

    protected function nextLetters()
    {
        $return = [];
        foreach ($this->adjacent as $adjacent) {
            $square = $this->getSquare($adjacent);
            $letter = $square->letter;

            $return[] = $letter;

        }
        return $return;

    }
}
