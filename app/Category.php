<?php namespace Drakkard;

use Illuminate\Database\Eloquent\Model;

class Category extends Model {
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'categories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'parent_id', 'children_id'];

    public function cards(){
        return $this->belongsToMany('Drakkard\Card');
    }
    
    public function registerCategories(array $cats){
        $data=[];
        $sCats=[];
        $links=[];
        foreach($cats as $cat){
            if(empty($cat)){ continue;}
            
            $cat=explode('::', $cat);
            foreach($cat as $sCat){
                $isExist=$this->alreadyExist($sCat);
                $id=(!$isExist)? $this->createCategory($sCat)->id : $isExist->id;
                $sCats[]=$id;
                $links[]=$id;
            }

            $data[]=$sCats;
            $sCats=[];
        }
        $this->setRelationship($data);
        return $links;
    }
    
    public function alreadyExist($catName){
        $cat=Category::where('name', '=', $catName)->first();
        if(count($cat)){
            return $cat;
        }
        return false;
    }
    
    public function createCategory($name){
        $newCat=new Category();
        $newCat->name=$name;
        $newCat->save();
        return $newCat;
    }
    
    public function setRelationship($array){
        $max=count($array);
        for($i=0; $i<$max; $i++){
            foreach($array[$i] as $c){
                $cat=Category::find($c);
                if($i==0){
                    $cat->parent_id=null;
                }else{
                    $cat->parent_id=$array[$i-1][0];
                }
                $cat->save();
            }
        }
    }
}
