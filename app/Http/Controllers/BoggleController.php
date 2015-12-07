<?php

namespace App\Http\Controllers;

use App\BoggleBoard;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Word;
use Illuminate\Support\Facades\Session;

class BoggleController extends Controller
{
    /**
     * Variable for the boggle board
     *
     * @var BoggleBoard
     */
    public $boggleBoard;

    public function __construct()
    {
        $this->boggleBoard = new BoggleBoard();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listSquares()
    {
        $board['board'] = $this->boggleBoard->getBoard();
        $this->boggleBoard->solve();
        $board['words'] = $this->boggleBoard->words;


        return response()->json( $board );
    }

    /**
     * Return the main board view
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {

        return view('welcome');
    }

    /**
     * Return the solver view
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function solver()
    {
        $this->boggleBoard->solve();
        return view('solver', compact('boggleBoard'));
    }


    public function saveWord(Requests\WordRequest $request)
    {

        if( $request->session()->has('words')) {
            $words = $request->session()->get('words');
        }  else {
            $words = [];
        }


        $obj = json_decode($request->getContent('Word'));

        if($this->boggleBoard->checkWord($obj->Word)){
            $request->session()->push('words', $obj->Word);

        }

        return $request->session()->get('words');
    }

    public function clear()
    {
        session(['words' => array()]);
    }

}
