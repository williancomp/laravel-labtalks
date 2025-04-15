<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/redis-test', function () {
    try {
        $ping = Illuminate\Support\Facades\Redis::ping();
        return "Conexão com Redis OK! Resposta: " . $ping;
    } catch (\Exception $e) {
        return "Erro na conexão com Redis: " . $e->getMessage();
    }
});
