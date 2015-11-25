<?php

namespace App;


class BoggleBoard
{


    protected $size = 16;

    protected $boardHeight = 4;

    protected $boardWidth = 4;



    protected $squares = array();

    /**
     * Generate a board
     *
     * @return array
     */
    public function __construct($size = 16)
    {
        $squares = [];

        for($i = 1; $i <= $size; $i++){
            $squares[$i-1] = new Square($i);
        }

        $this->squares = $squares;
    }

    public function getBoard()
    {
        return $this->squares;
    }


}
