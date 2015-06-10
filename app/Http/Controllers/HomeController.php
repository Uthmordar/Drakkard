<?php namespace Drakkard\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use Drakkard\Card;

class HomeController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Home Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders your application's "dashboard" for users that
	| are authenticated. Of course, you are free to change or remove the
	| controller as you wish. It is just here to get your app started!
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(){
            $this->middleware('auth');
	}

	/**
	 * Show the application dashboard to the user.
	 *
	 * @return Response
	 */
	public function index(){
            $cards=Auth::user()->cards()->orderBy('updated_at', 'desc')->get();
            foreach($cards as $card){
                $card->card=unserialize($card->card);
            }
            return view('dashboard/home', compact('cards'));
	}

        public function detachCard($id){
            $card=Card::findOrFail($id);
            $card->unbindUser();
            \Session::flash('messageCardCreate', "<p class='success bg-success'><span class='glyphicon glyphicon-ok' style='color:green;'></span>Card remove with success from your personal space.</p>");
            return \Redirect::to('home');
        }
}
