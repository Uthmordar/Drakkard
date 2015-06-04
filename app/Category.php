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
}
