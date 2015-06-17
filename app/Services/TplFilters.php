<?php

namespace Drakkard\Services;

class TplFilters{
    public static function toNormal($str){
        return strtolower(preg_replace( '/([A-Z])/', ' $1', lcfirst( $str )));
    }
    
    public static function urlFormat($str){
        if(filter_var($str, FILTER_VALIDATE_URL)){
            try{
                if(getimagesize($str)){
                    return "<img src='$str'/>";
                }
                return "<a href='$str'>$str</a>";
            }catch (\Exception $e){
                return "(status error) : <a href='#' class='disabled'>$str</a>";
            }
        }
        
        return $str;
    }
}