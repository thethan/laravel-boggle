<?php


namespace App;


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


    public function clearInUse($placement = 1)
    {
        for ($i = $placement; $i <= $this->size; $i++) {
            unset($this->inUse[$i]);
        }
    }

    public function clearWord($placement = 1)
    {
        for ($i = $placement; $i <= $this->size; $i++) {
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

        $this->word = [];
        $this->inUse = [];

        foreach ($this->squares as $square) {
            $this->clearInUse(1);
            $this->clearWord(1);
            $this->inUse[1] = $square->id;
            $this->word[1] = $square->letter;
            print "$square->id \n";

            if ($square->id < 11) {

                for ($i = 0; $i < count($square->adjacent); $i++) {
                    $adjacent = $square->adjacent[$i];
                    if (!in_array($adjacent, $this->inUse)) {
                        $this->clearInUse(2);
                        $this->clearWord(2);

                        $square_two = $this->getSquare($adjacent);

                        $this->inUse[2] = $square_two->id;
                        $this->word[2] = $square_two->letter;

//                        var_dump($square_two->adjacent);

//                        for($ii = 3; $ii <= $this->size; $ii++){
//                            $place = 3;
//                            $return = $this->loopThroughAdjacent($square_two->adjacent, $place);
//
//                        }

                        foreach ($square_two->adjacent as $id3) {
                            if (!in_array($id3, $this->inUse)) {
                                $square_three = $this->getSquare($id3);
                                $this->clearInUse(3);
                                $this->clearWord(3);

                                $this->inUse[3] = $square_three->id;
                                $this->word[3] = $square_three->letter;


                                $word = implode('', $this->word);
                                $this->checkWord($word);

                                foreach ($square_three->adjacent as $id4) {
                                    if (!in_array($id4, $this->inUse)) {
                                        $square_four = $this->getSquare($id4);
                                        $this->clearInUse(4);
                                        $this->clearWord(4);

                                        $this->inUse[4] = $square_four->id;
                                        $this->word[4] = $square_four->letter;

                                        $word = implode('', $this->word);
                                        $this->checkWord($word);

                                        foreach ($square_four->adjacent as $id5) {
                                            if (!in_array($id5, $this->inUse)) {
                                                $square_five = $this->getSquare($id5);
                                                $this->clearInUse(5);
                                                $this->clearWord(5);

                                                $this->inUse[5] = $square_five->id;
                                                $this->word[5] = $square_five->letter;

                                                $word = implode('', $this->word);
                                                $this->checkWord($word);
                                                var_dump($this->inUse);
                                            }
                                        }
                                    }
                                }

                            }
                        }
                    }
                }
            }
        }
        var_dump($this->words);
        exit;

    }


    public function loopThroughAdjacent($adjacentArray, $place)
    {
        foreach ($adjacentArray  as $id){
            if(!in_array($id, $this->inUse)){
//                $this->clearInUse($place);
//                $this->clearWord($place);

                $square = $this->getSquare($id);
                $this->inUse[$place] = $square->id;
                $this->word[$place] = $square->letter;

                $used = array_values($this->inUse);

                $result = array_diff($used, $square->adjacent);

                var_dump($result);
                return $result;
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


}
