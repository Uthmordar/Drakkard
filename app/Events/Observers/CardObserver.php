<?php
namespace Drakkard\Events\Observers;

class CardObserver{
    public function saved($card){
        $card->catCount();
    }
    
    public function deleted($card){
        $card->catCount();
    }
}