<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/redis-test', function () {
    try {
        // Método 1: Verificar se conseguimos executar comandos básicos
        Illuminate\Support\Facades\Redis::set('test_key', 'Conexão funcionando!');
        $testValue = Illuminate\Support\Facades\Redis::get('test_key');

        // Método 2: Verificar informações do servidor
        $info = Illuminate\Support\Facades\Redis::info();

        return response()->json([
            'status' => 'conectado',
            'test_value' => $testValue,
            'redis_version' => $info['redis_version'] ?? 'Não disponível',
            'uptime_in_seconds' => $info['uptime_in_seconds'] ?? 'Não disponível',
            'connected_clients' => $info['connected_clients'] ?? 'Não disponível'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'erro',
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
});
