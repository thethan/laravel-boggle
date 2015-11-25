<?php

namespace App;


class Square extends BoggleBoard
{
    /**
     * @var integer
     */
    public $id;
    /**
     * Need
     * @var string
     */
    public $letter = '';

    /**
     * @var array
     */
    public $adjacent = array();

    /**
     * @var
     */
    protected $prev, $next, $topLeft, $topRight, $bottomLeft, $bottomRight, $top, $bottom;

    /**
     * @var
     */
    protected $row;

    /**
     * Square constructor.
     * @param $id
     */
    public function __construct($id)
    {
        $this->id = $id;
        $this->row = $this->row();

        $this->next = $id +1;
        $this->prev = $id -1;
        $this->used = false;
        $this->active = true;
        $this->getAdjacentSquares($id);
        $this->randomLetter();
    }

    /**
     *
     */
    public function randomLetter()
    {
        $letter = $s = substr(str_shuffle(str_repeat("abcdefghijklmnopqrstuvwxyz", 1)), 0, 1);
        $this->letter = $letter;
    }



    protected function pushToAdjacent($n)
    {
        if(!empty($n)){
            $this->adjacent[] = $n;
        }
    }

    public function getAdjacentSquares($id)
    {
        $n = $this->getTopLeft();
        $this->pushToAdjacent($n);

        $n = $this->getTop();
        $this->pushToAdjacent($n);

        $n = $this->getTopRight();
        $this->pushToAdjacent($n);

        $n = $this->getPrev();
        $this->pushToAdjacent($n);

        $n = $this->getNext();
        $this->pushToAdjacent($n);

        $n = $this->getBottomLeft();
        $this->pushToAdjacent($n);

        $n = $this->getBottom();
        $this->pushToAdjacent($n);

        $n = $this->getBottomRight();
        $this->pushToAdjacent($n);


    }


    private function row($id = null)
    {
        if(empty($id)){

            $id = $this->id;
        }

        $row = $id / $this->boardWidth;

        //get the index row
        if(is_int($row)){
            $row = $row-1;
        }

        return floor($row);
    }

    /**
     * @return bool
     */
    private function getTopLeft()
    {

        $topLeft = ($this->prev - $this->boardWidth);

        $topLeftRow = $this->row($topLeft);

        if($topLeft > 0 && ( 0 <= $topLeftRow && $topLeftRow == ($this->row - 1)) ) {

            return $topLeft;
        }
    }


    /**
     * @return bool
     */
    private function getTop()
    {

        $top = ($this->id - $this->boardWidth);

        $topRow = $this->row($top);

        if($top > 0 && (0  <= $topRow && $topRow < $this->row  )) {
            return $top;
        }
    }

    /**
     * @return int
     */
    private function getTopRight()
    {

        $topRight = ($this->next - $this->boardWidth);

        $topRightRow = $this->row($topRight);

        if($topRight > 0 && (0 <= $topRightRow && $topRightRow < $this->row  ) ){
            return $topRight;
        }
    }

    /**
     * @return int
     */
    private function getPrev()
    {
        $prevRow = $this->row($this->prev);

        if($this->prev > 0 && $prevRow === $this->row ) {
            return $this->prev;
        }
    }

    /**
     * @return int
     */
    private function getNext()
    {
        $nextRow = $this->row($this->next);

        if($this->next > 0 && ($nextRow )=== $this->row ) {
            return $this->next;
        }
    }

    /**
     * @return int
     */
    private function getBottomLeft()
    {

        $bottomLeft = ($this->prev + $this->boardWidth);

        $bottomLeftRow = $this->row($bottomLeft);


        if($bottomLeft <= $this->size && ( $bottomLeftRow  >  $this->row && $bottomLeftRow <= $this->boardHeight ) ) {
            return $bottomLeft;
        }
    }

    /**
     * @return int
     */
    private function getBottom()
    {

        $bottom = ($this->id + $this->boardWidth);

        $bottomRow = $this->row($bottom);

        if( $bottom <= $this->size && ( $bottomRow  >  $this->row && $bottomRow <= $this->boardHeight )) {
            return $bottom;
        }
    }

    /**
     * @return int
     */
    private function getBottomRight()
    {
        $bottomRight = ($this->next + $this->boardWidth);

        $bottomRightRow = $this->row($bottomRight);

        if($bottomRight <= $this->size && ( $bottomRightRow  == ($this->row + 1) && $bottomRightRow <= $this->boardHeight ) ) {

            return $bottomRight;
        }
    }
}
