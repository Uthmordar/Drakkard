<?php

namespace Drakkard\Http\Controllers;

use Drakkard\Http\Controllers\Controller;
use Drakkard\Services\AdviceCard;
use Drakkard\Category;
use Drakkard\Card;
use Drakkard\Services\CatHierarchy;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller {

    private $catH;
    private $advice;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(CatHierarchy $catH, AdviceCard $advice) {
        $this->catH = $catH;
        $this->advice = $advice;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {
        $cards = Card::orderBy('updated_at', 'desc')->paginate(6);
        foreach ($cards as $card) {
            $card->card = unserialize($card->card);
        }
        $catMenu = $this->catH->getHierarchy();

        $adviceList = [];
        if (Auth::check()) {
            $adviceList['popular'] = $this->advice->getPopular();
            foreach ($adviceList['popular'] as $card) {
                $card->card = unserialize($card->card);
            }
            $adviceList['Relatives to your tastes'] = $this->advice->getByTaste();
            foreach ($adviceList['Relatives to your tastes'] as $card) {
                $card->card = unserialize($card->card);
            }
        }

        return view('category/show', compact('cards', 'catMenu', 'adviceList'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store() {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {
        $cards = Category::findOrFail($id)->cards()->orderBy('updated_at', 'desc')->paginate(6);
        foreach ($cards as $card) {
            $card->card = unserialize($card->card);
        }
        $catMenu = $this->catH->getHierarchy();

        $adviceList = [];
        if (Auth::check()) {
            $adviceList['popular'] = $this->advice->getPopular();
            foreach ($adviceList['popular'] as $card) {
                $card->card = unserialize($card->card);
            }
            $adviceList['Relatives to your tastes'] = $this->advice->getByTaste();
            foreach ($adviceList['Relatives to your tastes'] as $card) {
                $card->card = unserialize($card->card);
            }
        }

        return view('category/show', compact('cards', 'catMenu', 'adviceList'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {
        //
    }

}
