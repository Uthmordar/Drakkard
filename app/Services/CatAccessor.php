<?php

namespace Drakkard\Services;

use Drakkard\Category;

class CatAccessor{
    
    public static function getCat($name){
        $cats=Category::where('name', '=', $name)->take(1)->get();
        return $cats[0];
    }
}