<?php

namespace Drakkard\Services;

class CardatorServices{
    /**
     * 
     * @param type $url
     * @param type $json
     * @return type
     */
    public function getCardsFromUrl($url, $json=false){
        $cardator=new \Cardator(new \CardGenerator, new \CardProcessor, new \Parser);
        $cardator->crawl($url);
        $cardator->doPostProcess();
        
        return $cardator->getCards($json);
    }
}