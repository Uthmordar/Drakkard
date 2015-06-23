<?php

namespace Drakkard\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Drakkard\Card;
use Drakkard\Services\CatHierarchy;
use Drakkard\Services\AdviceCard;

class HomeController extends Controller {

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
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard to the user.
     *
     * @return Response
     */
    public function index() {
        $cards = Auth::user()->cards()->orderBy('updated_at', 'desc')->paginate(6);
        foreach ($cards as $card) {
            $card->card = unserialize($card->card);
        }
        $catMenu = $this->catH->getHierarchy();

        $adviceList = [];
        $adviceList['popular'] = $this->advice->getPopular();
        foreach ($adviceList['popular'] as $card) {
            $card->card = unserialize($card->card);
        }
        $adviceList['Relatives to your tastes'] = $this->advice->getByTaste();
        foreach ($adviceList['Relatives to your tastes'] as $card) {
            $card->card = unserialize($card->card);
        }

        return view('dashboard/home', compact('cards', 'catMenu', 'adviceList'));
    }
    
    public function fav(){
        $wholeCards=Card::with('users')->get()->sortBy(function($card){
            return $card->users->count();
        });
        $i=0;
        $cards=[];
        foreach ($wholeCards as $card) {
            if($i>5){ break; }
            $i++;
            $card->card = unserialize($card->card);
            $cards[]=$card;
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

        return view('card/fav', compact('cards', 'catMenu', 'adviceList'));
    }

    public function detachCard($id) {
        $card = Card::findOrFail($id);
        $card->unbindUser();
        \Session::flash('messageCardCreate', "<p class='message success bg-success'><span class='glyphicon glyphicon-ok' style='color:green;'></span>Card remove with success from your personal space.</p>");
        return \Redirect::back();
    }

    public function attachCard($id) {
        $card = Card::findOrFail($id);
        $card->bindUser();
        \Session::flash('messageCardCreate', "<p class='message success bg-success'><span class='glyphicon glyphicon-ok' style='color:green;'></span>Card add to your personal space.</p>");
        return \Redirect::back();
    }

}
