<?php namespace Drakkard\Http\Controllers;

use Drakkard\Http\Requests;
use Drakkard\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Drakkard\Card;
use Drakkard\Category;
use Drakkard\Services\CardatorServices;

class CardController extends Controller {
    private $card;
    private $cardator;
    private $category;
    
    public function __construct(Card $card, CardatorServices $cardator, Category $category){
        $this->card=$card;
        $this->cardator=$cardator;
        $this->category=$category;
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(){

    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Requests\AddCardRequest $request){
        $input=\Input::all();
        try{
            $this->card->alreadyExist($input['url']);

            $cards=$this->cardator->getCardsFromUrl($input['url']);
            foreach($cards as $c){
                $card=new Card;
                $this->card->createCard($c, $card, $this->category);
            }
        }catch(\RuntimeException $e){
            \Session::flash('messageCardCreate', "<p class='success bg-danger'><span class='glyphicon glyphicon-remove' style='color:red;'></span>" . $e->getMessage() . "</p>");
            return \Redirect::to('home');
        }catch(\InvalidArgumentException $e){
            $this->card->bindUserByUrl($input['url']);
            \Session::flash('messageCardCreate', "<p class='success bg-success'><span class='glyphicon glyphicon-ok' style='color:green;'></span>" . $e->getMessage() . "</p>");
            return \Redirect::to('home');
        }
        \Session::flash('messageDash', "Card created.");
        \Session::flash('messageType', 'success');
        return \Redirect::to('home')->with('message', 'success');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
            //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
            //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
            //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {

    }
}