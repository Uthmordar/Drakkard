<?php namespace Drakkard\Http\Controllers;

use Drakkard\Http\Requests;
use Drakkard\Http\Controllers\Controller;

use Drakkard\Card;
use Drakkard\Category;
use Drakkard\Services\CardatorServices;
use Drakkard\Services\CatHierarchy;
use Drakkard\Services\CardTplGenerator;
use Drakkard\Services\AdviceCard;

class CardController extends Controller {
    private $card;
    private $cardator;
    private $catH;
    private $advice;
    
    public function __construct(Card $card, CardatorServices $cardator, CatHierarchy $cat, AdviceCard $advice){
        $this->card=$card;
        $this->cardator=$cardator;
        $this->catH=$cat;
        $this->advice=$advice;
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
        $msgType="success";
        try{
            $cardsData=$cards=$this->cardator->storeCardWithCardator($input['url']);
            $cards=$cardsData['cards'];
        }catch(\RuntimeException $e){
            if(strpos($e->getMessage(), 'Header error')!==false){
                $msg="Page currently unavailable, check the given url status.";
            }else{
                $msg=$e->getMessage();
            }
            $msgType='error';
        }catch(\InvalidArgumentException $e){
            $cards=$this->card->bindUserByUrl($input['url']);
            if(!count($cards)){
                $msg="No new cards found. You were already following those cards.";
            }else{
                $msg=count($cards) . " new cards found for this url. " . $e->getMessage();
            }
        }
        if(\Request::ajax()){
            $tpl=[];
            if(!empty($cards) && $input['returnTpl']==true){
                foreach($cards as $card){
                    $card->card=unserialize($card->card);
                    $tpl[]=CardTplGenerator::generateCardContentAjax($card, ['header-class'=>['text-content'], 'cat-ul-class'=>['cat-list'], 'url-class'=>['card-source']]);
                }
            }
            $msg=(!isset($msg))? $cardsData['data']['cards'] . ' cards found in ' . round($cardsData['data']['executionTime'], 2) . " seconds. Cards created and bind to you." : $msg;
            return ['status'=>'success', 'msg'=>$msg, 'msgType'=>$msgType, 'tpl'=>$tpl];
        }
        $msg=(!isset($msg))? "{$cardsData['data']['cards']} cards found in " . round($cardsData['data']['executionTime'], 2) . " seconds. Cards created and bind to you." : $msg;
        \Session::flash('messageDash', $msg);
        \Session::flash('messageType', $msgType);
        return \Redirect::back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $card=Card::findOrFail($id);
        $card->card=unserialize($card->card);
        $catMenu=$this->catH->getHierarchy();
        
        $adviceList = [];
        $adviceList['popular'] = $this->advice->getPopular();
        foreach ($adviceList['popular'] as $c) {
            $c->card = unserialize($c->card);
        }
        $adviceList['Relatives to your tastes'] = $this->advice->getByTaste();
        foreach ($adviceList['Relatives to your tastes'] as $c) {
            $c->card = unserialize($c->card);
        }
        return view('card/show', compact('card', 'catMenu', 'adviceList'));
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