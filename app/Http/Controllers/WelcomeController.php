<?php namespace Drakkard\Http\Controllers;

class WelcomeController extends Controller {
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('guest');
    }

    /**
     * Show the application welcome screen to the user.
     *
     * @return Response
     */
    public function index(){
        try{
            $cardator=new \Cardator(new \CardGenerator, new \CardProcessor, new \Parser);
            $cardator->crawl('http://test.tanguygodin.fr/test.html');
            $cardator->doPostProcess();

            $cards=$cardator->getCards();
            foreach($cards as $c){
                var_dump($c);
            }
        }catch(\RuntimeException $e){
            var_dump($e->getMessage());
        }
        return view('welcome');
    }
}
