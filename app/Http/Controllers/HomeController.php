<?php namespace Drakkard\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use Drakkard\Card;
use Drakkard\Services\CatHierarchy;

class HomeController extends Controller {
    private $catH;
    

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(CatHierarchy $catH){
        $this->catH=$catH;
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard to the user.
     *
     * @return Response
     */
    public function index(){
        $cards=Auth::user()->cards()->orderBy('updated_at', 'desc')->paginate(6);
        foreach($cards as $card){
            $card->card=unserialize($card->card);
        }
        $catMenu=$this->catH->getHierarchy();
        return view('dashboard/home', compact('cards', 'catMenu'));
    }

    public function detachCard($id){
        $card=Card::findOrFail($id);
        $card->unbindUser();
        \Session::flash('messageCardCreate', "<p class='message success bg-success'><span class='glyphicon glyphicon-ok' style='color:green;'></span>Card remove with success from your personal space.</p>");
        return \Redirect::back();
    }
    
    public function attachCard($id){
        $card=Card::findOrFail($id);
        $card->bindUser();
        \Session::flash('messageCardCreate', "<p class='message success bg-success'><span class='glyphicon glyphicon-ok' style='color:green;'></span>Card add to your personal space.</p>");
        return \Redirect::back();
    }
}
