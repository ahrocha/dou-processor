<?php

namespace App\Listeners;

use App\Events\ArtigoCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Queue;

class PublicaArtigo implements ShouldQueue
{
    public string $queue = 'artigos-criados';

    public function handle(ArtigoCreated $event): void
    {
        $artigo = $event->artigo;

        logger()->info("Enviando artigo para a fila '{$this->queue}':", [
            'id' => $artigo->id,
            'titulo' => $artigo->titulo,
            'pub_date' => $artigo->pub_date,
        ]);

        Queue::connection('rabbit')->pushRaw(json_encode([
            'id' => $artigo->id,
            'titulo' => $artigo->titulo,
            'texto' => $artigo->texto,
            'pub_date' => $artigo->pub_date,
        ]), $this->queue);
    }
}
