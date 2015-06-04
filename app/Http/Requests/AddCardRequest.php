<?php
namespace Drakkard\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddCardRequest extends FormRequest{
    protected $rules=[
        'url' => 'required|url',
    ];
    
    public function rules(){
        return $this->rules;
    }
    
    public function authorize(){
        return true;
    }
}