<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Models\Upload;
use App\Models\Artigo;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RoutesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Artisan::call('migrate');
    }

    public function test_post_uploads_armazenando_arquivo_valido()
    {
        Storage::fake('local');

        $response = $this->postJson('/api/uploads', [
            'arquivo' => UploadedFile::fake()->create('S02012002.zip'),
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseCount('uploads', 1);
    }

    public function test_get_uploads_index()
    {
        Upload::factory()->count(2)->create();

        $response = $this->getJson('/api/uploads');

        $response->assertStatus(200);
        $response->assertJsonCount(2);
    }

    public function test_get_uploads_show()
    {
        $upload = Upload::factory()->create();

        $response = $this->getJson("/api/uploads/{$upload->id}");

        $response->assertStatus(200);
        $response->assertJsonFragment(['id' => $upload->id]);
    }

    public function test_get_artigos_show()
    {
        $upload = Upload::factory()->create();
        $artigo = Artigo::withoutEvents(function () use ($upload) {
            return Artigo::factory()->create([
                'upload_id' => $upload->id,
                'titulo' => 'Teste Título',
            ]);
        });

        $response = $this->getJson("/api/artigos/{$artigo->id}");

        $response->assertStatus(200);
        $response->assertJsonFragment(['titulo' => 'Teste Título']);
    }

    public function test_post_uploads_falha_com_arquivo_nao_zip()
    {
        Storage::fake('local');

        $response = $this->postJson('/api/uploads', [
            'arquivo' => UploadedFile::fake()->create('documento.txt', 100, 'text/plain'),
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('arquivo');
    }
}
