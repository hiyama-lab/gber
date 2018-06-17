<?php

// スコアは -100 ~ 100 の整数を取る
// スコアでソートする際に、0以上の整数 -> 興味ベクトル未定義 -> 負の整数 となるように-0.1を設定
const UNDEFINED_SCORE = -0.1;

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