<?php

function calcMatch($userp, $workp){
    $innerproduct = 0;
    foreach($userp as $k => $v){
        $innerproduct += $userp[$k] * $workp[$k];
    }
    return $innerproduct/(calcSize($userp) * calcSize($workp));
}

function calcSize($arr){
    $size = 0;
    foreach($arr as $v){
        $size += pow($v, 2);
    }
    return sqrt($size);
}