<?php namespace Drakkard\Http\Controllers;

use Drakkard\Http\Requests;
use Drakkard\Http\Controllers\Controller;

use Drakkard\Card;
use Drakkard\Category;
use Drakkard\Services\CardatorServices;
use Drakkard\Services\CatHierarchy;
use Drakkard\Services\CardTplGenerator;

class CardController extends Controller {
    private $card;
    private $cardator;
    private $category;
    private $catH;
    private $cardGen;
    
    public function __construct(Card $card, CardatorServices $cardator, Category $category, CatHierarchy $cat){
        $this->card=$card;
        $this->cardator=$cardator;
        $this->category=$category;
        $this->catH=$cat;
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
        if(\Request::ajax()){
            $msg=null;
            try{
                $cards=$cards=$this->cardator->storeCardWithCardator($input['url']);
            }catch(\RuntimeException $e){
                if(strpos($e->getMessage(), 'Header error')!==false){
                    $msg="<p class='message success bg-danger'><span class='glyphicon glyphicon-remove' style='color:red;'></span>Page currently unavailable, check the given url status. </p>";
                }else{
                    $msg="<p class='message success bg-danger'><span class='glyphicon glyphicon-remove' style='color:red;'></span>" . $e->getMessage() . "</p>";
                }
            }catch(\InvalidArgumentException $e){
                $cards=$this->card->bindUserByUrl($input['url']);
                $msg="<p class='message success bg-success'><span class='glyphicon glyphicon-ok' style='color:green;'></span> " . count($cards) . " new cards found for this url. " . $e->getMessage() . "</p>";
            }
            $tpl=[];
            if(!empty($cards) && $input['returnTpl']==true){
                foreach($cards as $card){
                    $card->card=unserialize($card->card);
                    $tpl[]=CardTplGenerator::generateCardContentAjax($card, ['header-class'=>['text-content'], 'cat-ul-class'=>['cat-list'], 'url-class'=>['card-source']]);
                }
            }
            $msg=($msg==null)? "<p class='message success bg-success'><span class='glyphicon glyphicon-ok' style='color:green;'></span>" . $cards['data']['cards'] . ' cards found in ' . $cards['data']['executionTime'] . "microseconds. Cards created and bind to you.</p>" : $msg;
            return ['status'=>'success', 'msg'=>$msg, 'tpl'=>$tpl];
        }else{
            try{
                $cards=$cards=$this->cardator->storeCardWithCardator($input['url']);
            }catch(\RuntimeException $e){
                if(strpos($e->getMessage(), 'Header error')!==false){
                    \Session::flash('messageCardCreate', "<p class='message success bg-danger'><span class='glyphicon glyphicon-remove' style='color:red;'></span>Page currently unavailable, check the given url status.</p>");
                }else{
                    \Session::flash('messageCardCreate', "<p class='message success bg-danger'><span class='glyphicon glyphicon-remove' style='color:red;'></span>" . $e->getMessage() . "</p>");
                }
                return \Redirect::back();
            }catch(\InvalidArgumentException $e){
                $cards=$this->card->bindUserByUrl($input['url']);
                \Session::flash('messageCardCreate', "<p class='message success bg-success'><span class='glyphicon glyphicon-ok' style='color:green;'></span> " . count($cards) . " new cards found for this url. " . $e->getMessage() . "</p>");
                return \Redirect::back();
            }
            \Session::flash('messageDash', "{$cards['data']['cards']} cards found in {$cards['data']['executionTime']} microseconds. Cards created and bind to you.");
            \Session::flash('messageType', 'success');
            return \Redirect::back();
        }
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
        return view('card/show', compact('card', 'catMenu'));
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