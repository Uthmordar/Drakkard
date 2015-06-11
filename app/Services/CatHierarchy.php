<?php

namespace Drakkard\Services;

use Drakkard\Category;

class CatHierarchy{
    private $rootId;
    
    public function getHierarchy(){
        $result=[];
        $root=Category::where('name', '=', 'Thing')->get();
        $this->rootId=$root[0]->id;
        
        $firstCat=Category::where('parent_id', '=', $this->rootId)->get();
        foreach($firstCat as $cat){
            $result[$cat->id]=['name'=>$cat->name, 'count'=>$cat->card_count];
            $secondCat=Category::where('parent_id', '=', $cat->id)->get();
            foreach($secondCat as $c){
                $result[$cat->id]['children'][$c->id]=['name'=>$c->name, 'count'=>$c->card_count];
            }
        }
        return $result;
    }
}