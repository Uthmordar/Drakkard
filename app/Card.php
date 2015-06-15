<?php namespace Drakkard;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Card extends Model {
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'cards';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['card', 'type', 'url', 'name'];
    
    public static function boot(){
        parent::boot();
        Card::observe(new Events\Observers\CardObserver);
    }

    public function users(){
        return $this->belongsToMany('Drakkard\User');
    }

    public function categories(){
        return $this->belongsToMany('Drakkard\Category');
    }


    public function catCount(){
        if(count($this->categories())){
            foreach($this->categories()->get() as $cat){
                $cat->card_count=$cat->cards()->count();
                $cat->save();
            }
        }
    }

    public function createCard(\Uthmordar\Cardator\Card\lib\iCard $data, $card, Category $category){
        $card->type=$data->type;
        $url=(is_array($data->url))? $data->url[0] : $data->url;
        $card->url=$url;
        $card->name=$data->type . $url;
        $card->card=serialize($data);
        $card->created_at=time();
        $card->updated_at=time();
        $card->save();
        $card->users()->attach(Auth::user());
        
        $rCat=$category->registerCategories($data->getParents(), $data->getQualifiedName());
        $card->categories()->attach($rCat);
        $card->save();

        return $card;
    }
    
    /**
     * test if given page has already been crawled
     * @param type $url
     * @return boolean
     * @throws \Exception
     */
    public function alreadyExist($url){
        $card=Card::where('url', '=', $url)->get();
        if(count($card)){
            throw new \InvalidArgumentException('This page has already been crawled, but you will be bind to it.');
        }
    }
    
    /**
     * bind current user to an already existing card
     * @param type $url
     */
    public function bindUserByUrl($url){
        $cards=Card::where('url', '=', $url)->get();
        foreach($cards as $card){
            if(!$card->users()->find(Auth::user()->id)){
                $card->users()->attach(Auth::user());
            }
        }
        return count($cards);
    }
    
    public function unbindUser(){
        if($this->users()->find(Auth::user()->id)){
            $this->users()->detach(Auth::user());
        }
    }
    
    public function bindUser(){
        if(!$this->users()->find(Auth::user()->id)){
            $this->users()->attach(Auth::user());
        }
    }
}
