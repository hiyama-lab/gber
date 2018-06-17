<?php

// スコアは -100 ~ 100 の整数を取る。算出できない場合はスコアでソートする際に最後に来るように設定
const UNDEFINED_SCORE = -10000;

function calcMatch($userp, $workp){
    if(calcSize($userp)){
        $innerproduct = 0;
        foreach($userp as $k => $v){
            $innerproduct += $userp[$k] * $workp[$k];
        }
        return round($innerproduct * 100 /(calcSize($userp) * calcSize($workp)));
    }else{
        return UNDEFINED_SCORE;
    }
}

function calcSize($arr){
    $size = 0;
    foreach($arr as $v){
        $size += pow($v, 2);
    }
    return sqrt($size);
}