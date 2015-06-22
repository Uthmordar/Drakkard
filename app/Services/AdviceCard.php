<?php

namespace Drakkard\Services;

use Drakkard\Card;
use Drakkard\Category;
use Illuminate\Support\Facades\Auth;

class AdviceCard{
    
    public function getPopular($nb=3){
        $take=(is_int($nb))? $nb : 3;
        $cards=Card::with('users')->get()->sortBy(function($card){
            return $card->users->count();
        });
        $tot=[];
        foreach($cards as $card){
            if(!$card->users()->find(Auth::user()->id)){
                $tot[]=$card;
            }
            if(count($tot)>2){
                break;
            }
        }
        return $tot;
    }
    
    public function getByTaste(){
        if(Auth::check()){
            $cards=Auth::user()->cards()->with('categories')->get();
            $cats=[];
            foreach($cards as $card){
                foreach($card->categories()->get() as $cat){
                    $cats[$cat->id]=(!empty($cats[$cat->id]))? $cats[$cat->id]+1 : 1;
                }
            }
            arsort($cats);
            $ar=array_slice($cats, 1, 3, true);
            
            return $this->iteratorTaste($ar);
        }
        return false;
    }
    
    private function iteratorTaste($ar){
        $cards=[];
        foreach($ar as $id=>$nb){
            $j=0;
            $i=count($cards);

            while(count($cards)<$i+1 && $j<5){
                $cardRequest=Category::find($id)->cards()->orderByRaw("RAND()")->get();
                foreach($cardRequest as $r){
                    if(!$r->users()->find(Auth::user()->id) && empty($cards[$r->id])){
                        $cards[$r->id]=$r;
                        break;
                    }
                }
                $j++;
            }
        }
        return $cards;
    }
}