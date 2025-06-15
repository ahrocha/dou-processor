<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Upload;
use App\Models\Artigo;
use App\Services\ProcessamentoUploadService;
use ZipArchive;
use Illuminate\Support\Facades\Artisan;

class ProcessamentoUploadServiceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Artisan::call('migrate');
    }

    public function test_processa_upload_valido_e_persiste_artigo()
    {
        Storage::fake('local');

        $zipPath = storage_path('app/test_upload.zip');
        $tempDir = storage_path('app/temp_test');
        @mkdir($tempDir, 0775, true);

        $xmlContent = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<root>
  <article id="123" name="Teste" idOficio="OF001" pubName="DOU" artType="Tipo" pubDate="2024-06-01" artClass="Classe" artCategory="Categoria" artSize="1" artNotes="Nota" numberPage="1" pdfPage="1" editionNumber="100">
    <body>
      <Identifica>Identificação</Identifica>
      <Data>2024-06-01</Data>
      <Ementa>Ementa do artigo</Ementa>
      <Titulo>Título</Titulo>
      <SubTitulo>Subtítulo</SubTitulo>
      <Texto>Texto completo do artigo</Texto>
    </body>
  </article>
</root>
XML;

        file_put_contents("$tempDir/valid.xml", $xmlContent);

        $zip = new ZipArchive();
        $zip->open($zipPath, ZipArchive::CREATE);
        $zip->addFile("$tempDir/valid.xml", "valid.xml");
        $zip->close();

        $upload = Upload::create([
            'nome_original' => 'test_upload.zip',
            'caminho_arquivo' => 'test_upload.zip',
        ]);

        copy($zipPath, storage_path('app/' . $upload->caminho_arquivo));

        $service = new ProcessamentoUploadService();
        $service->processar($upload);

        $this->assertDatabaseHas('artigos', [
            'upload_id' => $upload->id,
            'article_id' => '123',
            'titulo' => 'Título',
            'sub_titulo' => 'Subtítulo',
        ]);
    }
}
