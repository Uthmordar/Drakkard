<?php

namespace Drakkard\Services;

use Drakkard\Card;
use Drakkard\Category;

class CardatorServices{
    private $card;
    private $category;
    
    public function __construct(Card $card, Category $category){
        $this->card=$card;
        $this->category=$category;
    }
    /**
     * 
     * @param string $url
     * @return array
     */
    public function storeCardWithCardator($url){
        $this->card->alreadyExist($url);

        $cards=$this->getCardsFromUrl($url);
        $newCards=[];
        foreach($cards['cards'] as $c){
            $url=(is_array($c->url))? $c->url[0] : $c->url;
            $id=($c->name)? (is_array($c->name))? $c->name[0] : $c->name : $url;
            $name=$c->type . '_' . $id;
            $old=$this->card->alreadyExistByName($name);
            if(!count($old)){
                $card=new Card;
                $newCards[]=$this->card->createCard($c, $card, $this->category, $name, $url);
            }else{
                $this->card->updateCard($c, $old->first());
            }
        }
        
        return ['cards'=>$newCards, 'data'=>$cards['data']];
    }
    
    /**
     * 
     * @param string $url
     * @param boolean $json
     * @return array
     */
    public function getCardsFromUrl($url, $json=false){
        $cardator=new \Cardator(new \CardGenerator, new \CardProcessor, new \Parser);
        $cardator->crawl($url);
        $cardator->doPostProcess();
        
        return ['cards'=>$cardator->getCards($json), 'data'=>$cardator->getExecutionData()];
    }
}