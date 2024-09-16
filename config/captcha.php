<?php

return [
    'secret' => env('NOCAPTCHA_SECRET'),
    'sitekey' => env('NOCAPTCHA_SITEKEY'),
    'options' => [
        'timeout' => 5,
    ],
    'default'   => [
        'length'    => 5,
        'width'     => 200,
        'height'    => 56,
        'quality'   => 90,
        'math'      => false,  //Enable Math Captcha
        'expire'    => 60,    //Captcha expiration
        'bgImage'   => false,  
        /* 'bgColor'   => '#FF5050',
        'fontColors' => ['#FFFF00'],    */   
    ],

    'flat' => [
        'length'    => 5,
        'width'     => 160,
        'height'    => 46,
        'quality'   => 90,
        'lines'     => 6,
        'bgImage'   => false,
        'bgColor'   => '#FF5050',//'#ecf2f4',
        'fontColors' => '#999',//['#2c3e50', '#c0392b', '#16a085', '#c0392b', '#8e44ad', '#303f9f', '#f57c00'],
        'contrast'  => 100,
    ],
];