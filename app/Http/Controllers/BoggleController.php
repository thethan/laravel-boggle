<?php

namespace App\Http\Controllers;

use App\BoggleBoard;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class BoggleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listSquares()
    {
        $board  = new BoggleBoard();

        return response()->json($board->getBoard()  );
    }

    public function index()
    {

        $boggleBoard = new BoggleBoard();
        return view('welcome', compact('boggleBoard'));
    }

    public function saveWord(Requests\WordRequest $request)
    {

        if( $request->session()->has('words')) {
            $words = $request->session()->get('words');
        }  else {
            $words = [];
        }

        $obj = json_decode($request->getContent('Word'));


        $request->session()->push('words', $obj->Word);

        return $request->session()->get('words');
    }

    public function clear()
    {
        session(['words' => array()]);
    }

}
