<?php

namespace App\Jobs;

use App\Models\Upload;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Services\ProcessamentoUploadService;

class ProcessaUploadJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public function __construct(public Upload $upload)
    {
    }

    public function handle(ProcessamentoUploadService $service): void
    {
        logger()->info("Iniciando processamento do upload #{$this->upload->id} - {$this->upload->nome_original}");
        $service->processar($this->upload);
    }
}
