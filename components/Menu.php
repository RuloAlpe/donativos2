<?php

use yii\helpers\Url;

class Menu{
    public function getElementos(){
        $item = [];
        $item[] = [
            'visible'=>'',
            'label'=>'Clientes',
            'icon'=>'',
            'url'=>Url::base().""
        ];
    }

    public function getMenu(){
        $menu = [
            
        ];
    }
}