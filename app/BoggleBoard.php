<?php


namespace App;



class BoggleBoard
{

    /**
     * @var array
     */
    public $words = array();
    /**
     * @var int
     */
    public $lettersLong = 3;
    /**
     * @var int
     */
    protected $size = 16;
    /**
     * @var int
     */
    protected $boardHeight = 4;
    /**
     * @var int
     */
    protected $boardWidth = 4;
    /**
     * @var array
     */
    protected $squares = array();
    /**
     * @var array
     */
    protected $checked = array();
    /**
     * @var
     */
    protected $inUse, $word, $allUses, $wordPool, $wordArray;
    /**
     * @var array
     */
    protected $letters = [];
    /**
     * @var array
     */
    protected $dictionary;

    public $maxWordLength = 5;

    /**
     * Generate a board
     *
     * @return array
     */
    public function __construct($size = 16)
    {
        ini_set('max_execution_time', 1200); //300 seconds = 5 minutes
        ini_set('memory_limit', '256M');

        $squares = [];

        for ($i = 1; $i <= $size; $i++) {
            $squares[$i - 1] = new Square($i, $this->boardWidth, $this->boardHeight);
        }

        $this->squares = $squares;

        $this->dictionary = new Word();


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
        $this->lettersArray();
        $this->wordPool = [];

        $this->word = [];
        $this->inUse = [];

        foreach ($this->squares as $square) {

            $this->clearInUse(1);
            $this->inUse[1] = $square->id;
            $this->allUses[$square->id] = [];
            $this->allUses[$square->id][] = $this->inUse;
            $this->word[1] = $square->letter;
            $this->addToInUse($square->id, 1);

            $i = 1;
            //get the icons after for the first and second key
            while ($i < $this->maxWordLength) {
                $this->getLastAndAdd($square->id);
                $i++;
            }

        }

    }

    /**
     * Get the array of all in use and add it
     * @param $id
     */
    public function getLastAndAdd($id)
    {
        $allUses = $this->allUses[$id];

        if (empty($allUses)) {
            $this->addToInUse($id, 1);
            $square = $this->getSquare($id);

            $this->loopThroughAdjacent($square->adjacent, 2);

            $allUses = $this->allUses[$id];
        }

        foreach ($allUses as $use) {
            $this->inUse = $use;
            $value = $this->lastUsedValue($use);
            $key = $this->lastUsedKey($use);

            $square = $this->getSquare($value);

            $this->loopThroughAdjacent($square->adjacent, $key + 1);
        }
    }

    /**
     * @param int $placement
     */
    public function clearInUse($placement = 1)
    {
        for ($i = $placement; $i <= $this->size; $i++) {
            unset($this->inUse[$i]);
        }
    }

    /**
     * Add to in Use
     *
     * @param $id
     * @param $place
     */
    public function addToInUse($id, $place)
    {
        $this->clearInUse($place);


        if(!in_array($id, $this->inUse)) {
            $this->inUse[$place] = $id;
        }

        $first_key = $this->inUse[1];


        if(!in_array($this->inUse, $this->allUses[$first_key])) {

            $this->allUses[$first_key][] = $this->inUse;
        }



        if ($place > 2) {
            foreach ($this->inUse as $key => $id) {
                $this->word[] = $this->letters[$id];
            }
            $word = implode('', $this->word);
            $this->checkWord($word);

        }

    }

    /**
     * @todo I do not think this is needed
     */
    public function lettersArray()
    {
        $this->letters = [];
        foreach ($this->squares as $square) {
            $this->letters[$square->id] = $square->letter;
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

    /**
     * Return the letter of the square
     * @param $id
     * @return string
     */
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
    public function checkWord($word)
    {

        if (strlen($word) >= 3 && !in_array($word, $this->checked)) {
            if (!empty($this->dictionary->checkWord($word))) {
                
                $this->words[] = $word;
            }
            $this->checked[] = $word;
        }
    }

    /**
     * @param null $inUse
     * @return mixed
     */
    public function lastUsedKey($inUse = null)
    {
        if (empty($inUse)) {
            $inUse = $this->inUse;
        }
        end($inUse);

        return key($inUse);
    }

    /**
     * @param null $inUse
     * @return mixed
     */
    public function lastUsedValue($inUse = null)
    {
        if (empty($inUse)) {
            $inUse = $this->inUse;
        }
        return end($inUse);
    }

    /**
     * @param $adjacents
     * @param $position
     */
    public function adjacentSquare($adjacents, $position)
    {

        foreach ($adjacents as $id) {

            $square = $this->getSquare($id);
            $this->addToInUse($id, $position);

            return $this->loopThroughAdjacent($square->adjacent, $position + 1);

        }
    }

    /**
     * @param $adjacentArray
     * @param $place
     */
    public function loopThroughAdjacent($adjacentArray, $place)
    {
        foreach ($adjacentArray as $id) {
            $this->addToInUse($id, $place);
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
