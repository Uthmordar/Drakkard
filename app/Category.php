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
    
    public function registerCategories(array $cats, $mainCat){
        $data=[];
        $links=[];
        if(is_array($cats[0])){
            $sCats=[];
            foreach($cats as $branchCat){
                $branchCat[]=$mainCat;
                foreach($branchCat as $cat){
                    if(empty($cat)){ continue;}
                    $id=$this->setCategorie($cat);
                    $sCats[]=$id;
                    $links[]=$id;
                }
                $data[]=$sCats;
                $sCats=[];
            }
        }else{
            $cats[]=$mainCat;
            foreach($cats as $cat){
                if(empty($cat)){ continue;}

                $id=$this->setCategorie($cat);
                $links[]=$id;
                $data[0][]=$id;
            }
        }
        
        $this->setRelationship($data);
        return array_unique($links);
    }
    
    private function setCategorie($cat){
        $isExist=$this->alreadyExist($cat);
        $id=(!$isExist)? $this->createCategory($cat)->id : $isExist->id;
            
        return $id;
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
        foreach($array as $branch){
            $max=count($branch);
            for($i=0; $i<$max; $i++){
                $cat=Category::find($branch[$i]);
                if($i==0){
                    $cat->parent_id=null;
                }else{
                    $cat->parent_id=$branch[$i-1];
                }
                $cat->save();
            }
        }
    }
}
