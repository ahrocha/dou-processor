<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use App\Models\Upload;
use App\Jobs\ProcessaUploadJob;
use Illuminate\Support\Facades\Storage;

class UploadService
{
    public function create(UploadedFile $arquivo): Upload
    {
        $nomeOriginal = $arquivo->getClientOriginalName();
        $caminho = $arquivo->store();

        $upload = Upload::create([
            'nome_original' => $nomeOriginal,
            'caminho_arquivo' => $caminho,
        ]);

        ProcessaUploadJob::dispatch($upload);

        return $upload;
    }
}
