<?php
namespace Drakkard\Events\Observers;

class CardObserver{
    public function saving($card){
        $card->userCount();
    }
    
    public function saved($card){
        $card->catCount();
    }
    
    public function deleted($card){
        $card->catCount();
        $card->userCount();
    }
}