<?php

use Illuminate\Support\Facades\Route;

Route::get('/hello', function () {
    return "Hello World";
});

Route::get("/product",function(){
    return response ()-> json([
        'id'=> 1,
        'name'=>'Ayakkabi',
        'price'=>1200,
        'stock'=>10,
    ]);
});