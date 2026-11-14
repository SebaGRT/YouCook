<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('ia:probar', function () {
    if (blank(config('services.groq.key'))) {
        $this->error('Falta GROQ_API_KEY en el .env');

        return;
    }

    $modelo = config('services.groq.model');
    $inicio = microtime(true);

    $respuesta = Http::withToken(config('services.groq.key'))->timeout(20)->post('https://api.groq.com/openai/v1/chat/completions', [
        'model' => $modelo,
        'messages' => [['role' => 'user', 'content' => 'Responde solo con la palabra: listo']],
    ]);

    $segundos = round(microtime(true) - $inicio, 1);

    if ($respuesta->failed()) {
        $this->error("Groq ({$modelo}): error {$respuesta->status()} ~ ".Str::limit($respuesta->body(), 200));

        return;
    }

    $this->info("Groq ({$modelo}): «{$respuesta->json('choices.0.message.content')}» en {$segundos} s");
})->purpose('Prueba la conexión con Groq');
