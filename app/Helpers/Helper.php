<?php

function getqty($i,$products,$qty){
        $a = array_search($i, $products);
        $b = $qty[$a];
        return $b;
    }