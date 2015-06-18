<?php
namespace Drakkard\Services;

use Drakkard\Card;
use Illuminate\Support\Facades\Config;
use Drakkard\Services\TplFilters;

class CardTplGenerator{
    public static function generateCardContent(Card $card, $params=[]){
        $params['header-class']=(!empty($params['header-class']))? $params['header-class'] : 'text-content';
        $params['url-class']=(!empty($params['url-class']))? $params['url-class'] : 'card-source';
        $params['cat-ul-class']=(!empty($params['cat-ul-class']))? $params['cat-ul-class'] : 'cat-list';
        
        $tpl="<div class='" . implode(' ', $params['header-class']) . "'>";
        self::addName($card, $tpl);
        self::addUrl($card, $tpl, $params);
        self::addCat($card, $tpl, $params);
        self::addDescription($card, $tpl);
        $tpl.="</div>";
        self::addCardContent($card, $tpl);
        return $tpl;
    }
    
    public static function generateCardContentAjax(Card $card, $params=[]){
        $html="<article class='card bg-{$card->card->getQualifiedName()} bg-{$card->card->getDirectParent()}'>
            <ul class='nav-card'>
                <li>
                    <a href='" . route('detachCard', ['id'=>$card->id]). "' class='link-nav-card bg-red'><span class='glyphicon glyphicon-trash'></span></a>
                </li>
                <li>
                    <a href='" . route('card.show', ['id'=>$card->id]) . "' class='link-nav-card bg-blue'><span class='glyphicon glyphicon-eye-open'></span></a>
                </li>
            </ul>";
        $html.=self::generateCardContent($card, ['header-class'=>['text-content'], 'cat-ul-class'=>['cat-list'], 'url-class'=>['card-source']]);
        $html.="</article>";
        return $html;
    }
    
    public static function addName($card, &$tpl){
        $tpl.="<h4 class='text-uppercase'>";
        if($card->card->name){
            if(is_array($card->card->name)){
                $tpl.=$card->card->name[0];
            }else{
                $tpl.=$card->card->name;
            }
        }else{
            $tpl.=TplFilters::toNormal($card->card->getQualifiedName());
        }
        $tpl.="</h4>";
    }
    
    public static function addUrl($card, &$tpl, $params){
        $url=(is_array($card->url))? $card->url[0] : $card->url;
        $tpl.="<span>Source: <a href='$url' class='" . implode(' ', $params['url-class']) . "'>$url</a></span>";
    }
    
    public static function addCat($card, &$tpl, $params){
        $tpl.="<ul class='". implode(' ', $params['cat-ul-class']) . "'>";
        foreach($card->categories()->get() as $cat){
            $tpl.="<li><a href='" .route('category.show', ['id'=>$cat->id]) . "'>" . ucfirst(TplFilters::toNormal($cat->name)) . "</a></li>";
        }
        $tpl.="</ul>";
    }
    
    public static function addDescription($card, &$tpl){
        if(!$card->card->image && !$card->card->video && $card->card->description && !$card->card->location){
            $tpl.="<p>{$card->card->description}</p>";
        }
    }
    
    public static function addCardContent($card, &$tpl){
        if($card->card->location){
            self::addLocation($card, $tpl);
        }elseif($card->card->streetAddress){
            self::addStreetAddress($card, $tpl);
        }elseif($card->card->video){
            self::addVideo($card, $tpl);
        }elseif($card->card->image){
            self::addImage($card, $tpl);
        }
    }
    
    public static function addLocation($card, &$tpl){
        $tpl.="<iframe width='100%' height='233' frameborder='0' style='border:0' src='https://www.google.com/maps/embed/v1/place?q=" . urlencode($card->card->location) . "&key=" . Config::get('services.google_api_key'). "'></iframe>";
    }
    
    public static function addStreetAddress($card, &$tpl){
        $tpl.="<div class='text-content'><p>Location: <a href='{$card->card->streetAddress}'>Google map</a></p></div>";
    }
    
    public static function addVideo($card, &$tpl){
        $tpl.="<section class='media-block video-block'>";
        if(strpos($card->card->video, 'youtube')){
            $tpl.="<div class='image-prop video-vignette' data-vid='{$card->card->video}' style='background: url(http://img.youtube.com/vi/" . substr($card->card->video, strrpos($card->card->video, '/')+1). "/0.jpg) no-repeat center;background-size: cover;'></div>";
        }else{
            $tpl.="<video>";
            foreach($card->card->video as $vid){
                $tpl.="<source src='$vid'/>";
            }
            $tpl.="</video>";
        }
        $tpl.="</section>";
    }
    
    public static function addImage($card, &$tpl){
        $tpl.="<section class='media-block'>";
        if(is_array($card->card->image)){
            $tpl.="<div class='image-prop image-multiple' style='background: url({$card->card->image[0]}) no-repeat center;background-size: cover;'></div>
            <ul class='list-image'>";
            foreach($card->card->image as $src){
                $tpl.="<li class='sub-image'><img src='{$src}'/></li>";
            }
            $tpl.="</ul>";
        }else{
            $tpl.="<div class='image-prop' style='background: url({$card->card->image})no-repeat center;'></div>";
        }
        $tpl.="</section>";
    }
}