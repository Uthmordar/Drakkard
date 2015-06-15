<?php

namespace Drakkard\Services;

use Drakkard\Card;

class CardatorServices{
    private $card;
    
    public function __construct(Card $card){
        $this->card=$card;
    }
    /**
     * 
     * @param string $url
     * @return array
     */
    public function storeCardWithCardator($url){
        $this->card->alreadyExist($url);

        $cards=$this->cardator->getCardsFromUrl($url);
        foreach($cards['cards'] as $c){
            $card=new Card;
            $this->card->createCard($c, $card, $this->category);
        }
        
        return $cards;
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