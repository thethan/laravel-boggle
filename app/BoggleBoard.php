<?php


namespace App;


class BoggleBoard
{


    public $words = array();
    public $lettersLong = 3;
    protected $size = 16;
    protected $boardHeight = 4;
    protected $boardWidth = 4;
    protected $squares = array();
    protected $checked = array();
    protected $inUse, $word, $allUses, $wordPool, $wordArray;

    /**
     * Generate a board
     *
     * @return array
     */
    public function __construct($size = 16)
    {
        ini_set('max_execution_time', 300); //300 seconds = 5 minutes

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
            $this->inUse[1] = $square->id;
            $this->word[1] = $square->letter;
            $this->addToInUse($square->id, 1);

            // add the second
            foreach ($square->adjacent as $adj) {
                $this->clearInUse(2);
                $key = $this->lastUsedKey() + 1;
                $value = $this->lastUsedValue();
                $this->addToInUse($adj, $key);

            }
            $i = 0;
            //get the icons after for the first and second key
            while ($i < 14) {
                if ($i === 7) {
                    print "$i \n\n\n";
                }
                $allUses = $this->allUses[$square->id];
                foreach ($allUses as $use) {

                    $this->inUse = $use;
                    $value = $this->lastUsedValue($use);
                    $key = $this->lastUsedKey($use) + 1;

                    $sq = $this->getSquare($value);
                    $sq_adjacents = $this->adjacentSquare($sq->adjacent, $key);

                    $sq_adjacents2 = $this->adjacentSquare($sq_adjacents, $key++);

                    $sq_adjacents3 = $this->adjacentSquare($sq_adjacents2, $key++);

                    $sq_adjacents4 = $this->adjacentSquare($sq_adjacents3, $key++);

                    $sq_adjacents5 = $this->adjacentSquare($sq_adjacents4, $key++);

                    var_dump($this->inUse);

                    $sq_adjacents6 = $this->adjacentSquare($sq_adjacents5, $key++);
                }


                $i++;
            }

        }
//        var_dump($this->allUses);
    }

    public function clearInUse($placement = 1)
    {
        for ($i = $placement; $i <= $this->size; $i++) {
            unset($this->inUse[$i]);
        }
    }

    public function addToInUse($id, $place)
    {
        if (!in_array($id, $this->inUse)) {

            $this->clearInUse($place);

            $square = $this->getSquare($id);
            $this->inUse[$place] = $square->id;


            $this->allUses[array_values($this->inUse)[0]][] = $this->inUse;

            $used = array_values($this->inUse);
            $result = array_diff($square->adjacent, $this->inUse);
            if ($place > 2) {
                foreach ($this->inUse as $key => $id) {
                    $this->word[$key] = $this->getLetter($id);
                }
                $word = implode('', $this->word);
                $this->checkWord($word);

            }


            return $result;

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

    public function getLetter($id)
    {
        $sq = $this->getSquare($id);
        return $sq->letter;
    }

    /**
     * Checks to the dictionary to see if word exists.
     * The money maker if you will
     *
     * Using Wikipedia because it had a quasi api
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

    public function lastUsedKey($inUse = null)
    {
        if (empty($inUse)) {
            $inUse = $this->inUse;
        }
        end($inUse);

        return key($inUse);
    }

    public function lastUsedValue($inUse = null)
    {
        if (empty($inUse)) {
            $inUse = $this->inUse;
        }
        return end($inUse);;
    }

    public function adjacentSquare($adjacents, $position)
    {

        if (is_array($adjacents)) {
            foreach ($adjacents as $id) {

                $square = $this->getSquare($id);

                return $this->loopThroughAdjacent($square->adjacent, $position);
            }
        }
    }

    public function loopThroughAdjacent($adjacentArray, $place)
    {
        foreach ($adjacentArray as $id) {
            return $this->addToInUse($id, $place);
        }
    }

    public function getFromAllUse($id)
    {
        return $this->allUses[$id];
    }

    public function clearWord($placement = 1)
    {
        for ($i = $placement; $i <= $this->size; $i++) {
            unset($this->word[$i]);
        }
    }

    public function squareAdjacent()
    {
        foreach ($this->squares as $square) {
            return $square;
        }
    }


}
